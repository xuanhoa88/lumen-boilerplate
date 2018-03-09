<?php
return [
    'defaults' => [
        'guard' => 'oauth2',
        'passwords' => 'users'
    ],
    
    'guards' => [
        'oauth2' => [
            'driver' => 'passport',
            'provider' => 'users'
        ]
    ],
    
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\User::class
        ]
    ]
];