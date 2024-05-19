<?php

namespace Database\Seeders;

use App\Models\CrmTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $csvFile = database_path('seeders/raw_data.csv');
        $trans_count = 0;
        // Open the CSV file
        if (($handle = fopen($csvFile, 'r')) !== FALSE) {
            // Get the headers from the first row
            $headers = fgetcsv($handle, 1000, ',');

            // Process each row of the CSV file
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $trans_count++;
                // Combine the headers with the data to create an associative array
                try {
                    $record = array_combine($headers, $data);
                } catch (\Exception $e) {
                    continue;
                }
                // Insert the record into the raw_data table
                CrmTransaction::create([
                    'transactionId' => $record['transactionId'] ?? null,
                    'parentTxnId' => $record['parentTxnId'] ?? null,
                    'merchant' => $record['merchant'] ?? null,
                    'merchantDescriptor' => $record['merchantDescriptor'] ?? null,
                    'midNumber' => $record['midNumber'] ?? null,
                    'merchantId' => $record['merchantId'] ?? null,
                    'dateCreated' => $record['dateCreated'] ?? null,
                    'dateUpdated' => $record['dateUpdated'] ?? null,
                    'txnType' => $record['txnType'] ?? null,
                    'responseType' => $record['responseType'] ?? null,
                    'responseText' => $record['responseText'] ?? null,
                    '3DTxnResult' => $record['3DTxnResult'] ?? null,
                    'merchantTxnId' => $record['merchantTxnId'] ?? null,
                    'clientTxnId' => $record['clientTxnId'] ?? null,
                    'authCode' => $record['authCode'] ?? null,
                    'avsResponse' => $record['avsResponse'] ?? null,
                    'cvvResponse' => $record['cvvResponse'] ?? null,
                    'totalAmount' => $record['totalAmount'] ?? null,
                    'surcharge' => $record['surcharge'] ?? null,
                    'orderId' => $record['orderId'] ?? null,
                    'orderAgentName' => $record['orderAgentName'] ?? null,
                    'clientOrderId' => $record['clientOrderId'] ?? null,
                    'billingCycleNumber' => $record['billingCycleNumber'] ?? null,
                    'recycleNumber' => $record['recycleNumber'] ?? null,
                    'campaignId' => $record['campaignId'] ?? null,
                    'campaignName' => $record['campaignName'] ?? null,
                    'campaignCategoryName' => $record['campaignCategoryName'] ?? null,
                    'firstName' => $record['firstName'] ?? null,
                    'lastName' => $record['lastName'] ?? null,
                    'orderType' => $record['orderType'] ?? null,
                    'customerId' => $record['customerId'] ?? null,
                    'emailAddress' => $record['emailAddress'] ?? null,
                    'phoneNumber' => $record['phoneNumber'] ?? null,
                    'address1' => $record['address1'] ?? null,
                    'address2' => $record['address2'] ?? null,
                    'city' => $record['city'] ?? null,
                    'state' => $record['state'] ?? null,
                    'country' => $record['country'] ?? null,
                    'postalCode' => $record['postalCode'] ?? null,
                    'ipAddress' => $record['ipAddress'] ?? null,
                    'paySource' => $record['paySource'] ?? null,
                    'cardBin' => $record['cardBin'] ?? null,
                    'cardLast4' => $record['cardLast4'] ?? null,
                    'cardType' => $record['cardType'] ?? null,
                    'cardIsPrepaid' => $record['cardIsPrepaid'] ?? null,
                    'cardIsDebit' => $record['cardIsDebit'] ?? null,
                    'achBankName' => $record['achBankName'] ?? null,
                    'achRoutingNumber' => $record['achRoutingNumber'] ?? null,
                    'achAccountNumber' => $record['achAccountNumber'] ?? null,
                    'sourceTitle' => $record['sourceTitle'] ?? null,
                    'affId' => $record['affId'] ?? null,
                    'sourceValue1' => $record['sourceValue1'] ?? null,
                    'sourceValue2' => $record['sourceValue2'] ?? null,
                    'sourceValue3' => $record['sourceValue3'] ?? null,
                    'sourceValue4' => $record['sourceValue4'] ?? null,
                    'sourceValue5' => $record['sourceValue5'] ?? null,
                    'custom1' => $record['custom1'] ?? null,
                    'custom2' => $record['custom2'] ?? null,
                    'custom3' => $record['custom3'] ?? null,
                    'custom4' => $record['custom4'] ?? null,
                    'custom5' => $record['custom5'] ?? null,
                    'isChargedback' => $record['isChargedback'] ?? null,
                    'chargebackAmount' => $record['chargebackAmount'] ?? null,
                    'chargebackDate' => $record['chargebackDate'] ?? null,
                    'chargebackReasonCode' => $record['chargebackReasonCode'] ?? null,
                    'chargebackNote' => $record['chargebackNote'] ?? null,
                    'refundReason' => $record['refundReason'] ?? null,
                    'currencyCode' => $record['currencyCode'] ?? null,
                    'currencySymbol' => $record['currencySymbol'] ?? null,
                    'paySourceId' => $record['paySourceId'] ?? null,
                    'mcc' => $record['mcc'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        // Close the CSV file
        fclose($handle);
    }
}
