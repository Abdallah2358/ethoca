<?php
namespace App\Http\Webhooks\AlertNotification;

use Exception;
use Illuminate\Http\Request;
use \Spatie\WebhookClient\WebhookProfile\WebhookProfile as SpatieWebhookProfile;
use \Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile as DefualtWebhookProfile;

class WebhookProfile implements SpatieWebhookProfile
{

    public function shouldProcess(Request $request): bool
    {
        $prof = new DefualtWebhookProfile();
        return  $prof->shouldProcess($request);
    }
}

