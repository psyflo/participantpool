<?php

use Illuminate\Support\Facades\Facade;

return [

    /*
    |--------------------------------------------------------------------------
    | Contact information
    |--------------------------------------------------------------------------
    |
    | Will be displayed at startpage and after participant has registered
    |
    */

    'contact_email' => env('PARTICIPANTPOOL_CONTACT_EMAIL', 'contact@example.com'),

    /*
    |--------------------------------------------------------------------------
    | Participant update link configuration
    |--------------------------------------------------------------------------
    |
    | Number of days the update link can be used by participant
    |
    */

    'updatelink_days' => env('PARTICIPANTPOOL_UPDATELINK_DAYS', 14),

    /*
    |--------------------------------------------------------------------------
    | Log entries limit
    |--------------------------------------------------------------------------
    |
    | Maximal number of entries which have to read from log files
    |
    */

    'log_entries_limit' => env('PARTICIPANTPOOL_LOG_ENTRIES_LIMIT', 1000),

];
