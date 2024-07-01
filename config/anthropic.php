<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Anthropic Key
    |--------------------------------------------------------------------------
    |
    | Here you may specify your Anthropic Key. This will be
    | used to authenticate with the Anthropic - you can find your API key
    | inside the Anthropic Console settings, at https://console.anthropic.com/settings/keys
    */

    'api_key' => env('ANTHROPIC_API_KEY'),
    'api_version' => env('ANTHROPIC_API_VERSION', 'v1'),
    'model_version' => env('ANTHROPIC_MODEL_VERSION', '2023-06-01'),
    'max_tokens' => env('ANTHROPIC_MAX_TOKENS', 1024),

    /*
    |--------------------------------------------------------------------------
    | Request Specific Information
    |--------------------------------------------------------------------------
    |
    | You may specify additional request-specific information, such as the
    | model to use as a default for chat requests,
    | the timeout used to specify the maximum number of seconds to wait
    | for a response and more.
    */

    'chat_model' => env('ANTHROPIC_CHAT_MODEL', 'claude-3-5-sonnet-20240620'),
    'request_timeout' => env('ANTHROPIC_REQUEST_TIMEOUT', 30),
];
