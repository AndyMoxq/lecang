<?php

return [


    /*
    |--------------------------------------------------------------------------
    | AccessKey
    |--------------------------------------------------------------------------
    |
    | This value is the AccessKey of your Lecang API.You can get it from Lecang OMS.[https://app.lecangs.com/oms/home]
    |
    */
    'access_key' => env('LECANG_ACCESS_KEY', 'your_access_key'),

    /*
    |--------------------------------------------------------------------------
    | SecretKey
    |--------------------------------------------------------------------------
    |
    | This value is the SecretKey of your Lecang API.You can get it from Lecang OMS.[https://app.lecangs.com/oms/home]
    |
    */
   'secret_key' => env('LECANG_SECRET_KEY', 'your_secret_key'),

    /*
    |--------------------------------------------------------------------------
    | API URL
    |----------
    |
    | This value is the API URL of Lecang API.
    |
    */
    'api_url' => env('LECANG_API_URL', 'https://app.lecangs.com/api'),


    /*
    |--------------------------------------------------------------------------
    | IS DEV
    |----------
    |
    | This value is the flag to indicate whether the current environment is a development environment.
    |
    */
    'is_dev' => false,

    /*
    |--------------------------------------------------------------------------
    | API URL
    |----------
    |
    | This value is the API URL of Lecang API.
    |
    */
    'dev_api_url' => env('LECANG_API_URL', 'https://apprelease.lecangs.com/api'),
];