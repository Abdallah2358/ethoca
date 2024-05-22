<?php
namespace App\Http\Webhooks;

use Spatie\WebhookClient\SignatureValidator\SignatureValidator as SpatieSignatureValidator;
use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookConfig;

class SignatureValidator implements SpatieSignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        return true;
    }

}

