<?php
namespace App\Models\Enums;

class CrmActionEnum
{
    const AddNoteToCustomer = 0;
    const FindTransaction = 1;
    const GetCustomerData = 2;
    const BlacklistCustomerEmail = 3;
    const BlacklistCustomerPhone = 4;
    const BlacklistCustomer = 5;
    const CancelFulfillments = 6;
    const RefundTransactions = 7;
    const GetCustomerHistory = 8;
    const ConfirmFulfillmentCancel  = 9;
    public static function getActionName($action)
    {
        switch ($action) {
            case self::AddNoteToCustomer:
                return 'Add Note To Customer';
            case self::FindTransaction:
                return 'Find Transaction';
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


}
