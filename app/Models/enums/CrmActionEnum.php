<?php
namespace App\Models\Enums;

class CrmActionEnum
{
    const FindTransaction = 1;
    const GetCustomerData = 2;
    const BlacklistCustomerEmail = 3;
    const BlacklistCustomerPhone = 4;
    const BlacklistCustomer = 5;

    const CancelFulfillments = 6;
    public static function getActionName($action)
    {
        switch ($action) {
            case self::FindTransaction:
                return 'Find Transaction';
            case self::GetCustomerData:
                return 'Get Customer Data';
            case self::BlacklistCustomerEmail:
                return 'Blacklist Customer Email';
            case self::BlacklistCustomerPhone:
                return 'Blacklist Customer Phone';
            case self::BlacklistCustomer:
                return 'Blacklist Customer';
            case self::CancelFulfillments:
                return 'Cancel Fulfillments';
            default:
                return 'Unknown';
        }
    }


}
