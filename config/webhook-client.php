<?php
use App\Http\Webhooks\AlertNotification\SignatureValidator as ANSignatureValidator;
use App\Http\Webhooks\AlertNotification\WebhookProfile as ANWebhookProfile;
use App\Http\Webhooks\AlertNotification\RespondsToWebhook as ANRespondsToWebhook;
use App\Jobs\ANProcessWebhookJob;
use App\Http\Webhooks\CrmAction\SignatureValidator as CrmSignatureValidator;
use App\Http\Webhooks\CrmAction\WebhookProfile as CrmWebhookProfile;
use App\Http\Webhooks\CrmAction\RespondsToWebhook as CrmRespondsToWebhook;
use App\Jobs\CrmProcessWebhookJob;

return [
    'configs' => [
        [
            /*
             * This package supports multiple webhook receiving endpoints. If you only have
             * one endpoint receiving webhooks, you can use 'default'.
             */
            'name' => 'Ethoca-Alert-Notification',

            /*
             * We expect that every webhook call will be signed using a secret. This secret
             * is used to verify that the payload has not been tampered with.
             */
            'signing_secret' => env('WEBHOOK_CLIENT_SECRET'),

            /*
             * The name of the header containing the signature.
             */
            'signature_header_name' => 'Signature',

            /*
             *  This class will verify that the content of the signature header is valid.
             *
             * It should implement \Spatie\WebhookClient\SignatureValidator\SignatureValidator
             */
            'signature_validator' => ANSignatureValidator::class,

            /*
             * This class determines if the webhook call should be stored and processed.
             */
            'webhook_profile' => ANWebhookProfile::class,

            /*
             * This class determines the response on a valid webhook call.
             */
            'webhook_response' => ANRespondsToWebhook::class,

            /*
             * The classname of the model to be used to store webhook calls. The class should
             * be equal or extend Spatie\WebhookClient\Models\WebhookCall.
             */
            'webhook_model' => \Spatie\WebhookClient\Models\WebhookCall::class,

            /*
             * In this array, you can pass the headers that should be stored on
             * the webhook call model when a webhook comes in.
             *
             * To store all headers, set this value to `*`.
             */
            'store_headers' => [
                // 'Authorization',
                // 'php-auth-user',
                // 'php-auth-pw',
            ],

            /*
             * The class name of the job that will process the webhook request.
             *
             * This should be set to a class that extends \Spatie\WebhookClient\Jobs\ProcessWebhookJob.
             */
            'process_webhook_job' => ANProcessWebhookJob::class,
        ],
        [
            'name' => 'CRM-Action',
            'signing_secret' => env('WEBHOOK_CLIENT_SECRET'),
            'signature_header_name' => 'Signature',
            'signature_validator' => CrmSignatureValidator::class,
            'webhook_profile' => CrmWebhookProfile::class,
            'webhook_response' => CrmRespondsToWebhook::class,
            'webhook_model' => \Spatie\WebhookClient\Models\WebhookCall::class,
            'store_headers' => [
                // 'Authorization',
                // 'php-auth-user',
                // 'php-auth-pw',
            ],
            'process_webhook_job' => CrmProcessWebhookJob::class,

        ],
    ],

    /*
     * The integer amount of days after which models should be deleted.
     *
     * It deletes all records after 1 week. Set to null if no models should be deleted.
     */
    'delete_after_days' => 30,

    /*
     * Should a unique token be added to the route name
     */
    'add_unique_token_to_route_name' => false,
];
