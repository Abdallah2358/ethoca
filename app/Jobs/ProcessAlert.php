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
        CrmTransaction::create([
            'ethoca_alert_id' => $this->alert->id,
            'crm_customer_id' => $this->alert->crm_customer_id,
            'merchant_id' => $this->alert->merchant_id,
            'transaction_id' => $transaction['transactionId'],
            'transaction_timestamp' => $transaction['dateCreated'],
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
    }
    protected function findTransaction(EthocaAlert $alert): Collection
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
        return collect([]);
    }
}
