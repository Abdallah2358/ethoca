<?php

namespace App\Jobs;

use App\Models\Company;
use App\Models\CrmAction;
use App\Models\CrmTransaction;
use App\Models\Enums\CrmActionEnum;
use App\Models\EthocaAlert;
use App\Models\Error;
use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class ProcessAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected CrmTransaction $transaction;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected EthocaAlert $alert
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        # TODO: Add a check to see if the alert is already fully processed
        # and if so, continue to the next step of processing after trying
        # the failed step again
        $transaction = $this->findTransaction($this->alert);
        # TODO : Add a check to see if the transaction is not found because of a failed query to crm and retry.
        if (!$transaction) {
            # This Assumes that the query to crm is successful but no transaction is found
            # This is a rare case and should be handled differently
            ProcessUpdateEthoca::dispatch($this->alert, 'notfound', 'not settled');
            return;
        }
        $this->alert->crm_customer_id = $transaction['customerId'];
        $this->transaction = CrmTransaction::create(
            $transaction->merge([
                'ethoca_alert_id' => $this->alert->id,
                'ethoca_id' => $this->alert->ethoca_id,
            ])->forget('items')->toArray()
        );
        // dd($this->transaction->merchantId);
        # TODO: Add a CHECK to see if the company already exists
        $company = Company::where('crm_id', $this->transaction->merchantId)->firstOrCreate(
            [
                'crm_id' => $this->transaction->merchantId,
                'name' => $this->transaction->merchant,
                'mcc' => $this->transaction->mcc,
            ]
        );

        // dd($company);
        $gateway = $this->findGateway($this->transaction->merchantId, $this->transaction->midNumber);
        // dd($gateway['midNumber']);
        if (!$gateway) {
            return;
        }
        # TODO: Add a CHECK to see if the merchant already exists
        $merchant = Merchant::where('midNumber', $gateway['midNumber'])->firstOrCreate(
            [
                'company_id' => $company->id,
                'biller_id' => $gateway['billerId'],
                'title' => $gateway['title'],
                'descriptor' => $gateway['descriptor'],
                'midNumber' => $gateway['midNumber'],
                'gatewayName' => $gateway['gatewayName'],
            ]
        );
        // dd($merchant);
        $this->alert->crmTransaction()->associate($this->transaction);
        $this->alert->merchant()->associate($merchant);
        $this->alert->save();
        // dd($this->alert);
        $this->addNoteToCustomer();
        $this->addNoteToCustomer("OTP");
        $customer = $this->getCustomerData($this->transaction->crm_customer_id);
        $this->blackListCustomerEmail($customer['emailAddress']);
        $this->addNoteToCustomer('Email Blacklisted');
        $this->blackListCustomerPhone($customer['phoneNumber']);
        $this->addNoteToCustomer('Phone Blacklisted');
        $this->blackListCustomer();
        $this->addNoteToCustomer('Customer Blacklisted');

        $this->cancelFulfillments();
        $this->addNoteToCustomer('Fulfillments Cancelled');

        $this->refundTransaction();
        $this->addNoteToCustomer('Transaction Refunded');

        $history = $this->getCustomerHistory();
        $this->confirmFulfillmentCancel($history);
        $this->addNoteToCustomer('Fulfillment Cancel Confirmed');

        $this->addNoteToCustomer('Ethoca Alert Processed');
        $this->alert->is_handled = true;
        $this->alert->save();
        $this->appendToChain(new ProcessUpdateEthoca($this->alert));
    }
    # TODO: Refactor these methods to be more generic and reduce code duplication

    # Todo : Add Functionality to check and make sure that fulfillments are cancelled
    # and all above actions are verified
    protected function findTransaction(EthocaAlert $alert): Collection|bool
    {
        $action = CrmAction::create([
            'ethoca_alert_id' => $alert->id,
            'code' => CrmActionEnum::FindTransaction,
            'name' => CrmActionEnum::getActionName(CrmActionEnum::FindTransaction),
        ]);
        $response = Http::post(env('KONNEKTIVE_API_URL', 'localhost:3000/') . 'transactions/query/', [
            'loginId' => env('KONNEKTIVE_LOGIN_ID'),
            'password' => env('KONNEKTIVE_PASSWORD'),
            'txType' => 'SALE',
            'responseType' => 'SUCCESS',
            'cardLast4' => $alert->card_last4,
            'cardBin' => $alert->card_bin,
        ]);
        if ($response->successful() && $response->json()['result'] == 'SUCCESS') {
            $message = $response->json()['message'];
            $data = collect($message['data']);
            $action->data = $data->toJson();
            $action->result = 'Found' . $message['totalResults'] . ' transactions';
            $action->save();
            if ($data->count() > 1) {
                return collect($data->sortBy(function ($e) use ($alert) {
                    $date = Carbon::parse($e['dateCreated']);
                    $targetDate = Carbon::parse($alert->transaction_timestamp);
                    return $date->diffInDays($targetDate);
                })->first());
            }
            return collect($data[0]);
        }
        Error::create([
            'model' => CrmAction::class,
            'model_id' => $action->id,
            'ethoca_id' => $alert->ethoca_id,
            'code' => $response->status(),
            'description' => "Failed to Connect to Konnektive",
            'data' => json_encode($response->json()),
        ]);
        return false;
    }

    protected function findGateway(int $merchantId, int $mid_number): Collection|bool
    {
        $action = CrmAction::create([
            'ethoca_alert_id' => $this->alert->id,
            'code' => CrmActionEnum::FindGateway,
            'name' => CrmActionEnum::getActionName(CrmActionEnum::FindGateway),
        ]);
        $response = Http::post(env('KONNEKTIVE_API_URL', 'localhost:3000/') . 'merchant/query/', [
            'loginId' => env('KONNEKTIVE_LOGIN_ID'),
            'password' => env('KONNEKTIVE_PASSWORD'),
            'billerId' => $merchantId,
        ]);
        if ($response->successful() && $response->json()['result'] == 'SUCCESS') {
            $message = $response->json()['message'];
            $data = collect($message);
            // dd($data);
            $gateway = $data;
            $action->data = $data->toJson();
            $action->result = 'Found' . 1 . ' gateways';
            $action->save();
            return $gateway;
        }
        Error::create([
            'model' => CrmAction::class,
            'model_id' => $action->id,
            'ethoca_id' => $this->alert->ethoca_id,
            'code' => $response->status(),
            'description' => "Failed to Find Gateway",
            'data' => $response->json(),
        ]);
        return false;
    }
    protected function getCustomerData($customer_id): Collection|bool
    {
        $action = CrmAction::create([
            'ethoca_alert_id' => $this->alert->id,
            'code' => CrmActionEnum::GetCustomerData,
            'name' => CrmActionEnum::getActionName(CrmActionEnum::GetCustomerData),
        ]);
        $response = Http::post(env('KONNEKTIVE_API_URL', 'localhost:3000/') . 'customer/query/', [
            'loginId' => env('KONNEKTIVE_LOGIN_ID'),
            'password' => env('KONNEKTIVE_PASSWORD'),
            'customerId' => $customer_id,
        ]);
        if ($response->successful() && $response->json()['result'] == 'SUCCESS') {
            $message = $response->json()['message'];
            $data = collect($message['data'][0]);
            $action->data = $data->toJson();
            $action->result = 'Found' . $message['totalResults'] . ' customers';
            $action->save();
            return $data;
        }
        Error::create([
            'model' => CrmAction::class,
            'model_id' => $action->id,
            'ethoca_id' => $this->alert->ethoca_id,
            'code' => $response->status(),
            'description' => "Failed to Get Customer Data",
            'data' => $response->json(),
        ]);
        return false;
    }
    protected function blackListCustomerEmail($email): bool
    {
        $action = CrmAction::create([
            'ethoca_alert_id' => $this->alert->id,
            'code' => CrmActionEnum::BlacklistCustomerEmail,
            'name' => CrmActionEnum::getActionName(CrmActionEnum::BlacklistCustomerEmail),
        ]);
        $response = Http::post(env('KONNEKTIVE_API_URL', 'localhost:3000/') . 'customer/blacklist/', [
            'loginId' => env('KONNEKTIVE_LOGIN_ID'),
            'password' => env('KONNEKTIVE_PASSWORD'),
            'blacklistType' => 'emailAddress',
            'emailAddress' => $email,
        ]);

        if ($response->successful() && $response->json()['result'] == 'SUCCESS') {
            $action->data = $response->json();
            $action->result = 'Email Blacklisted';
            $action->save();
            return true;
        }
        Error::create([
            'model' => CrmAction::class,
            'model_id' => $action->id,
            'ethoca_id' => $this->alert->ethoca_id,
            'code' => $response->status(),
            'description' => "Failed to Blacklist Customer Email, Email : " . $email,
            'data' => $response->json(),
        ]);
        return false;
    }
    protected function blackListCustomerPhone($phone): bool
    {
        $action = CrmAction::create([
            'ethoca_alert_id' => $this->alert->id,
            'code' => CrmActionEnum::BlacklistCustomerPhone,
            'name' => CrmActionEnum::getActionName(CrmActionEnum::BlacklistCustomerPhone),
        ]);
        $response = Http::post(env('KONNEKTIVE_API_URL', 'localhost:3000/') . 'customer/blacklist/', [
            'loginId' => env('KONNEKTIVE_LOGIN_ID'),
            'password' => env('KONNEKTIVE_PASSWORD'),
            'blacklistType' => 'phoneNumber',
            'phoneNumber' => $phone,
        ]);
        if ($response->successful() && $response->json()['result'] == 'SUCCESS') {
            $action->data = $response->json();
            $action->result = 'Phone Blacklisted';
            $action->save();
            return true;
        }
        Error::create([
            'model' => CrmAction::class,
            'model_id' => $action->id,
            'ethoca_id' => $this->alert->ethoca_id,
            'code' => $response->status(),
            'description' => "Failed to Blacklist Customer Phone, Phone : " . $phone,
            'data' => $response->json(),
        ]);
        return false;
    }
    protected function blackListCustomer(): bool
    {
        /**
         * Blacklist Customer
         *   cancel all current subscriptions for a customer
         */
        $action = CrmAction::create([
            'ethoca_alert_id' => $this->alert->id,
            'code' => CrmActionEnum::BlacklistCustomer,
            'name' => CrmActionEnum::getActionName(CrmActionEnum::BlacklistCustomer),
        ]);
        $response = Http::post(env('KONNEKTIVE_API_URL', 'localhost:3000/') . 'customer/blacklist/', [
            'loginId' => env('KONNEKTIVE_LOGIN_ID'),
            'password' => env('KONNEKTIVE_PASSWORD'),
            'customerId' => $this->transaction->crm_customer_id,
        ]);
        if ($response->successful() && $response->json()['result'] == 'SUCCESS') {
            $action->data = $response->json();
            $action->result = 'Customer Blacklisted';
            $action->save();
            return true;
        }
        Error::create([
            'model' => CrmAction::class,
            'model_id' => $action->id,
            'ethoca_id' => $this->alert->ethoca_id,
            'code' => $response->status(),
            'description' => "Failed to Blacklist Customer",
            'data' => $response->json(),
        ]);
        return false;
    }

    protected function cancelFulfillments(): bool
    {
        /**
         * Cancel Fulfillments
         *   cancel all current fulfillments for a customer
         */
        $action = CrmAction::create([
            'ethoca_alert_id' => $this->alert->id,
            'code' => CrmActionEnum::CancelFulfillments,
            'name' => CrmActionEnum::getActionName(CrmActionEnum::CancelFulfillments), // 'Cancel Fulfillments
        ]);
        $response = Http::post(env('KONNEKTIVE_API_URL', 'localhost:3000/') . 'fulfillment/update/', [
            'loginId' => env('KONNEKTIVE_LOGIN_ID'),
            'password' => env('KONNEKTIVE_PASSWORD'),
            'orderId' => $this->transaction->order_id,
            'fulfillmentStatus' => 'CANCELLED',
        ]);
        if ($response->successful() && $response->json()['result'] == 'SUCCESS') {
            $action->data = $response->json();
            $action->result = 'Fulfillments Cancelled';
            $action->save();
            return true;
        }
        Error::create([
            'model' => CrmAction::class,
            'model_id' => $action->id,
            'ethoca_id' => $this->alert->ethoca_id,
            'code' => $response->status(),
            'description' => "Failed to Cancel Fulfillments",
            'data' => $response->json(),
        ]);
        return false;
    }

    public function refundTransaction(): bool
    {
        $action = CrmAction::create([
            'ethoca_alert_id' => $this->alert->id,
            'code' => CrmActionEnum::RefundTransactions,
            'name' => CrmActionEnum::getActionName(CrmActionEnum::RefundTransactions), // 'Refund Transactions',
        ]);
        $response = Http::post(env('KONNEKTIVE_API_URL', 'localhost:3000/') . 'transactions/refund/', [
            'loginId' => env('KONNEKTIVE_LOGIN_ID'),
            'password' => env('KONNEKTIVE_PASSWORD'),
            'transactionId' => $this->transaction->transaction_id,
            'fullRefund' => true,
            'refundReason' => "Fraudulent Transaction",
        ]);
        if ($response->successful() && $response->json()['result'] == 'SUCCESS') {
            $action->data = $response->json();
            $action->result = 'Transaction Refunded Successfully';
            $action->save();
            return true;
        }
        Error::create([
            'model' => CrmAction::class,
            'model_id' => $action->id,
            'ethoca_id' => $this->alert->ethoca_id,
            'code' => $response->status(),
            'description' => "Failed to Refund Transaction",
            'data' => $response->json(),
        ]);
        return false;
    }
    public function getCustomerHistory(): Collection|bool
    {
        $action = CrmAction::create([
            'ethoca_alert_id' => $this->alert->id,
            'code' => CrmActionEnum::GetCustomerHistory,
            'name' => CrmActionEnum::getActionName(CrmActionEnum::GetCustomerHistory), // 'Get Customer History',
        ]);
        $response = Http::post(env('KONNEKTIVE_API_URL', 'localhost:3000/') . 'customer/history/', [
            'loginId' => env('KONNEKTIVE_LOGIN_ID'),
            'password' => env('KONNEKTIVE_PASSWORD'),
            'customerId' => $this->transaction->crm_customer_id,
            'startDate' => Carbon::now()->subDays(2)->toDateString(),
        ]);
        if ($response->successful() && $response->json()['result'] == 'SUCCESS') {
            $message = $response->json()['message'];
            $data = collect($message['data']);
            $action->data = $data->toJson();
            $action->result = 'Found' . $message['totalResults'] . ' history items';
            $action->save();
            return $data;
        }
        Error::create([
            'model' => CrmAction::class,
            'model_id' => $action->id,
            'ethoca_id' => $this->alert->ethoca_id,
            'code' => $response->status(),
            'description' => "Failed to Get Customer History",
            'data' => $response->json(),
        ]);
        return false;
    }

    protected function confirmFulfillmentCancel(Collection $history): bool
    {
        $action = CrmAction::create([
            'ethoca_alert_id' => $this->alert->id,
            'code' => CrmActionEnum::ConfirmFulfillmentCancel,
            'name' => CrmActionEnum::getActionName(CrmActionEnum::ConfirmFulfillmentCancel), // 'Confirm Fulfillment Cancel',
        ]);
        $fulfillmentCancelled = false;
        foreach ($history as $item) {
            if ($this->isFulfillmentCancelMessage($item['message'], $this->transaction->order_id)) {
                $fulfillmentCancelled = true;
                $action->result = 'Fulfillment Cancel Confirmed';
                $action->save();
                return true;
            }
        }
        return false;
    }


    protected function addNoteToCustomer($note = null): bool
    {
        $action = CrmAction::create([
            'ethoca_alert_id' => $this->alert->id,
            'name' => CrmActionEnum::getActionName(CrmActionEnum::AddNoteToCustomer),
            'code' => CrmActionEnum::AddNoteToCustomer,
        ]);
        $response = Http::post(env('KONNEKTIVE_API_URL', 'localhost:3000/') . 'customer/addnote/', [
            'loginId' => env('KONNEKTIVE_LOGIN_ID'),
            'password' => env('KONNEKTIVE_PASSWORD'),
            'customerId' => $this->transaction->crm_customer_id,
            'note' => $note ?? $this->generateNoteMessage(),
        ]);
        if ($response->successful() && $response->json()['result'] == 'SUCCESS') {
            $action->data = $response->json();
            $action->result = 'Note Added';
            $action->save();
            return true;
        }
        Error::create([
            'model' => CrmAction::class,
            'model_id' => $action->id,
            'ethoca_id' => $this->alert->ethoca_id,
            'code' => $response->status(),
            'description' => "Failed to Add Note to Customer, Note : " . $note ?? $this->generateNoteMessage(),
            'data' => $response->json(),
        ]);
        return false;
    }
    protected function generateNoteMessage(): string
    {
        $alertType = $this->alert->alert_type == 'issuer_alert' ? 'Issuer' : 'Customer Dispute';
        $cardIssuer = $this->alert->issuer;
        $amount = $this->alert->amount;
        $transactionType = $this->alert->transaction_type;
        $initiatedBy = $this->alert->initiated_by;

        return "Ethoca Alert: $alertType Alert\n Card Issuer: $cardIssuer\n Amount: $amount\n Transaction Type: $transactionType\n Initiated By: $initiatedBy\n";
    }

    function isFulfillmentCancelMessage($message, $orderNumber)
    {
        // Escape the order number to safely use it in a regex
        $escapedOrderNumber = preg_quote($orderNumber, '/');

        // Define the regex pattern with the variable order number
        $pattern = '/^Fulfillment for order ' . $escapedOrderNumber . ' cancelled$/';

        // Check if the message matches the pattern
        if (preg_match($pattern, $message)) {
            return true;
        } else {
            return false;
        }
    }
}
