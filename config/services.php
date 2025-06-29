<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'whatsapp' => [
        'token' => env('WHATSAPP_API_TOKEN'),
        'phone_id' => env('WHATSAPP_API_PHONE_ID'),
        'version' => env('WHATSAPP_API_VERSION'),
        'local_verify_token' => env('WHATSAPP_API_LOCAL_VERIFICATION_TOKEN'),
        'url' => "https://graph.facebook.com/" . env('WHATSAPP_API_VERSION') . "/" . env('WHATSAPP_API_PHONE_ID') . "/messages",
    ],
    'witai' => [
        'token' => env('WITAI_TOKEN'),
        'url' => env('WITAI_URL')
    ],
    'ollama' => [
        'url' => env('OLLAMA_URL'),
        'model' => env('OLLAMA_MODEL'),
        'model2' => env('OLLAMA_MODEL_2'),
        'model3' => env('OLLAMA_MODEL_3'),
        'model4' => env('OLLAMA_MODEL_4'),
        'prefix' => env('OLLAMA_PREFIX')
    ],
    'messenger' => [
        'token' => env('MESSENGER_PAGE_ACCESS_TOKEN'),
        'local_verify_token' => env('MESSENGER_LOCAL_VERIFICATION_TOKEN'),
        'url' => "https://graph.facebook.com/" . env('MESSENGER_VERSION')."/me/messages",
        'version' => env('MESSENGER_VERSION')
    ]
];
