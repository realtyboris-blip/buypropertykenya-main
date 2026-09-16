<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Email Addresses
    |--------------------------------------------------------------------------
    |
    | List of email addresses that should receive admin notifications
    |
    */
    'admin_emails' => array_filter(array_map('trim', explode(',', env('ADMIN_EMAILS', '')))),

    /*
    |--------------------------------------------------------------------------
    | Send Admin Notifications
    |--------------------------------------------------------------------------
    |
    | Enable or disable admin notifications
    |
    */
    'send_admin_notifications' => env('SEND_ADMIN_NOTIFICATIONS', true),
];