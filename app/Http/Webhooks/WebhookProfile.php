<?php
namespace App\Http\Webhooks;

use Illuminate\Http\Request;
use \Spatie\WebhookClient\WebhookProfile\WebhookProfile as SpatieWebhookProfile;

class WebhookProfile implements SpatieWebhookProfile
{
    public function shouldProcess(Request $request): bool
    {

        return true;
    }
}
