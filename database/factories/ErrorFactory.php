<?php

namespace Database\Factories;

use App\Models\EthocaError;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ErrorFactory extends Factory
{
    protected $model = EthocaError::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $error_codes = [
            2 => "Unexpected System Error",
            6 => "Data Validation Failed",
            301 => "Invalid User Credentials",
            401 => "SOAP request format error: Length restriction - the value has a min and/or max length.",
            403 => "SOAP request format error: Decimal format restriction - the value is a decimal that conforms to the specified schema format.",
            404 => "SOAP request format error: Enumeration restriction - the value must be listed in the specified schema enumeration.",
            405 => "SOAP request format error: Pattern restriction - the value must conform to the specified schema regular expression.",
            406 => "SOAP request format error: Date format restriction - the value is a date type with a specified schema date format.",
            408 => "SOAP request format error: Requirement restriction - the attribute has a requirement restriction.",
            409 => "SOAP request format error: Number Format error - the value must be a number.",
            413 => "SOAP request format error: Payload too large XX. A maximum of 25 outcomes can be submitted in the payload. XX = number of outcomes > 25",
            501 => "Transaction ID Not Unique",
            502 => "Transaction Out of Order",
            503 => "Transaction Denied",
            504 => "Unable to Process Transaction",
            655 => "Ethoca ID incorrect / No Event Matches Provided IDs",
            658 => "Invalid Notification Date",
            662 => "Invalid End Date",
            690 => "The requested service is not supported. Note: This commonly indicates an IP address not allowed through the firewalls/proxys",
            694 => "The fraud type is not valid",
            695 => "All fields must be supplied",
            698 => "The out of bounds type is not valid. Note: Check the field lengths in the payload"
        ];
        $code = fake()->randomElement(array_keys($error_codes));
        return [
            'code' => $code,
            'description' => $error_codes[$code],
        ];
    }
}
