<?php

namespace App\Jobs;

use App\Models\CrmAction;
use App\Models\CrmTransaction;
use App\Models\EthocaAlert;
use App\Models\EthocaError;
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
        public EthocaAlert $alert
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $transaction = $this->findTransaction($this->alert);
        if ($transaction->isEmpty()) {
            // Et
            return;
        }
        $this->transaction = CrmTransaction::create([
            'ethoca_alert_id' => $this->alert->id,
            'crm_customer_id' => $this->alert->crm_customer_id,
            'merchant_id' => $this->alert->merchant_id,
            'transaction_id' => $transaction['transactionId'],
            'transaction_timestamp' => $transaction['dateCreated'],
            'order_id' => $transaction['orderId'],
            'amount' => $transaction['amount'],
            'currency' => $transaction['currency'],
            'status' => $transaction['status'],
            'auth_code' => $transaction['authCode'],
            'card_last4' => $this->alert->card_last4,
            'card_bin' => $this->alert->card_bin,
            'arn' => $this->alert->arn,
            'chargeback_reason_code' => $this->alert->chargeback_reason_code,
            'chargeback_amount' => $this->alert->chargeback_amount,
            'chargeback_currency' => $this->alert->chargeback_currency,
        ]);

        $this->addNoteToCustomer();
        $this->addNoteToCustomer("OTP");
        $customer = $this->getCustomerData($this->transaction->crm_customer_id);


        $this->blackListCustomerEmail($customer->email);
        $this->addNoteToCustomer('Email Blacklisted');

        $this->blackListCustomerPhone($customer->phone);
        $this->addNoteToCustomer('Phone Blacklisted');
        $this->blackListCustomer();
        $this->addNoteToCustomer('Customer Blacklisted');

        $this->cancelFulfillments();
        $this->addNoteToCustomer('Fulfillments Cancelled');
        

    }
    # TODO: Refactor these methods to be more generic and reduce code duplication

    protected function findTransaction(EthocaAlert $alert): Collection|bool
    {
        $action = CrmAction::create([
            'ethoca_alert_id' => $alert->id,
            'name' => 'Find Transaction',
        ]);
        $response = Http::post('https://api.konnektive.com/transactions/query/', [
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
                return $data->sortBy(function ($e) use ($alert) {
                    $date = Carbon::parse($e['dateCreated']);
                    $targetDate = Carbon::parse($alert->transaction_timestamp);
                    return $date->diffInDays($targetDate);
                })->first();
            }
            return collect($data[0]);
        }
        EthocaError::create([
            'model' => CrmAction::class,
            'model_id' => $action->id,
            'ethoca_id' => $alert->ethoca_id,
            'code' => $response->status(),
            'description' => "Failed to Connect to Konnektive",
            'data' => $response->json(),
        ]);
        return false;
    }
    protected function getCustomerData($customer_id): Collection|bool
    {
        $action = CrmAction::create([
            'ethoca_alert_id' => $this->alert->id,
            'name' => 'Get Customer Data',
        ]);
        $response = Http::post('https://api.konnektive.com/customer/query/', [
            'loginId' => env('KONNEKTIVE_LOGIN_ID'),
            'password' => env('KONNEKTIVE_PASSWORD'),
            'customerId' => $customer_id,
        ]);
        if ($response->successful() && $response->json()['result'] == 'SUCCESS') {
            $message = $response->json()['message'];
            $data = collect($message['data']);
            $action->data = $data->toJson();
            $action->result = 'Found' . $message['totalResults'] . ' customers';
            $action->save();
            return $data;
        }
        EthocaError::create([
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
            'name' => 'Blacklist Customer Email',
        ]);
        $response = Http::post('https://api.konnektive.com/customer/blacklist/', [
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
        EthocaError::create([
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
            'name' => 'Blacklist Customer Phone',
        ]);
        $response = Http::post('https://api.konnektive.com/customer/blacklist/', [
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
        EthocaError::create([
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
            'name' => 'Cancel Customer Subscriptions',
        ]);
        $response = Http::post('https://api.konnektive.com/customer/blacklist/', [
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
        EthocaError::create([
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
            'name' => 'Cancel Fulfillments',
        ]);
        $response = Http::post('https://api.konnektive.com/fulfillment/update/', [
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
        EthocaError::create([
            'model' => CrmAction::class,
            'model_id' => $action->id,
            'ethoca_id' => $this->alert->ethoca_id,
            'code' => $response->status(),
            'description' => "Failed to Cancel Fulfillments",
            'data' => $response->json(),
        ]);
        return false;
    }
    protected function addNoteToCustomer($note = null): bool
    {
        $action = CrmAction::create([
            'ethoca_alert_id' => $this->alert->id,
            'name' => 'Add Note to Customer',
        ]);
        $response = Http::post('https://api.konnektive.com/customer/note/', [
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
        EthocaError::create([
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
}
