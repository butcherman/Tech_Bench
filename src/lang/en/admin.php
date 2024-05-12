<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Administration Language Lines
    |--------------------------------------------------------------------------
    */

    'fake-password' => 'This Is A Really Long String to Make You Think You Can Get A Password',

    'user' => [
        'created' => 'User :user Created',
        'updated' => 'User :user Updated',
        'disabled' => 'User :user Disabled',
        'restored' => 'User :user Restored',
        'password_reset' => 'Password for :user Reset',
        'welcome_sent' => 'Welcome Email Queued for Delivery',
        'notification_sent' => 'Notification Queued for Delivery',
        'password_policy' => 'Password Policy Updated',
        'settings_updated' => 'User Settings Updated',
    ],

    'user-role' => [
        'created' => 'New User Role Created',
        'updated' => 'User Role Updated',
        'destroyed' => 'User Role Deleted',
        'in-use' => 'This Role is currently assigned to at least one user and 
                     cannot be deleted',
    ],

    'config' => [
        'updated' => 'Application Configuration Updated',
        'logo' => 'New Logo Applied',
    ],

    'security' => [
        'updated' => 'Certificate successfully saved.  Restart Tech Bench to 
                      activate new cert.',
        'deleted' => 'Certificate and Private Key deleted.  Restart Tech Bench 
                      to generate a new Self Signed Certificate',
    ],

    'email' => [
        'test' => 'Email sent successfully.  Please check your inbox',
        'updated' => 'Email Settings saved',
    ],

    'logging' => [
        'updated' => 'Logging Settings have been updated',
    ],

    'backups' => [
        'settings-successful' => 'Backup Settings Saved',
    ],

    'file-type' => [
        'created' => 'New File Type Created',
        'updated' => 'File Type Updated',
        'destroyed' => 'File Type Deleted',
    ],

    'phone-type' => [
        'created' => 'New Phone Number Type Created',
        'updated' => 'Phone Number Type Updated',
        'destroyed' => 'Phone Number Type Deleted',
    ],
];
