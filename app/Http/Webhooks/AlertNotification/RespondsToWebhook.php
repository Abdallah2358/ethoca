<?php
namespace App\Http\Webhooks\AlertNotification;

use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\WebhookResponse\RespondsToWebhook as SpatieRespondsToWebhook;
use Symfony\Component\HttpFoundation\Response;

class RespondsToWebhook implements SpatieRespondsToWebhook
{
    public function respondToValidWebhook(Request $request, WebhookConfig $config): Response
    {
        // Handle the incoming webhook
        
        return response()->json(['ethoca_id' => $request->input('ethoca_id'), 'status' => 'received']);
    }
}
