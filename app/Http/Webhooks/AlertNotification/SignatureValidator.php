<?php
namespace App\Http\Webhooks\AlertNotification;

use Spatie\WebhookClient\SignatureValidator\SignatureValidator as SpatieSignatureValidator;
use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookConfig;

class SignatureValidator implements SpatieSignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        # TODO : Add Auth logic here
        if (env('APP_ENV') == "local") {
            return true;
        }
        $authToken = "Basic " . env('WEBHOOK_CLIENT_SECRET');
        if ($request->header('Authorization') == $authToken) {
            return true;
        }
        return false;
    }

}

