<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | The number of seconds until the user is required to reauthenticate
    | with their password.
    |
    */

    'timeout' => 3600,

    /*
    |--------------------------------------------------------------------------
    | Timer Reset
    |--------------------------------------------------------------------------
    |
    | This option controls if the timer is reset after every successful
    | visit to a page with the reauthorization middleware.
    |
    */

    'reset' => true,

    /*
    |--------------------------------------------------------------------------
    | Route
    |--------------------------------------------------------------------------
    |
    | The route the middleware will redirect to when the user needs to be
    | reauthenticated.
    |
    */

    'route' => 'reauthenticate',

];
