<?php
namespace App\Models\Enums;

class CrmActionEnum
{
    const AddNoteToCustomer = 0;
    const FindTransaction = 1;
    const FindGateway = 2;
    const GetCustomerData = 3;
    const BlacklistCustomerEmail = 4;
    const BlacklistCustomerPhone = 5;
    const BlacklistCustomer = 6;
    const CancelFulfillments = 7;
    const RefundTransactions = 8;
    const GetCustomerHistory = 9;
    const ConfirmFulfillmentCancel = 10;
    public static function getActionName($action)
    {
        switch ($action) {
            case self::AddNoteToCustomer:
                return 'Add Note To Customer';
            case self::FindTransaction:
                return 'Find Transaction';
            case self::FindGateway:
                return 'Find Gateway';
            case self::GetCustomerData:
                return 'Get Customer Data';
            case self::BlacklistCustomerEmail:
                return 'Blacklist Customer Email';
            case self::BlacklistCustomerPhone:
                return 'Blacklist Customer Phone';
            case self::BlacklistCustomer:
                return 'Cancel Customer Subscriptions';
            case self::CancelFulfillments:
                return 'Cancel Fulfillments';
            case self::RefundTransactions:
                return 'Refund Transactions';
            case self::GetCustomerHistory:
                return 'Get Customer History';
            case self::ConfirmFulfillmentCancel:
                return 'Confirm Fulfillment Cancel';
            default:
                return 'Unknown';
        }
    }
    public static function getActionPath($action)
    {
        switch ($action) {
            case self::AddNoteToCustomer:
                return 'customer/addnote/';
            case self::FindTransaction:
                return 'transactions/query/';
            case self::FindGateway:
                return 'merchant/query/';
            case self::GetCustomerData:
                return 'customer/query/';
            case self::BlacklistCustomerEmail:
            case self::BlacklistCustomerPhone:
            case self::BlacklistCustomer:
                return 'customer/blacklist/';
            case self::CancelFulfillments:
                return 'fulfillment/update/';
            case self::RefundTransactions:
                return 'transactions/refund/';
            case self::GetCustomerHistory:
                return 'customer/history/';
            case self::ConfirmFulfillmentCancel:
            default:
                return '';
        }
    }

}
