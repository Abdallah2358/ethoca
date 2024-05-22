<?php
namespace App\Http\Webhooks;

use Illuminate\Http\Request;
use \Spatie\WebhookClient\WebhookProfile\WebhookProfile as SpatieWebhookProfile;
use \Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile as DefualtWebhookProfile;

class WebhookProfile implements SpatieWebhookProfile
{

    public function shouldProcess(Request $request): bool
    {
        $prof = new DefualtWebhookProfile();
        // dd($request->headers);
        return  $prof->shouldProcess($request);
        // return true;
    }
}
