<?php
/*
 * |--------------------------------------------------------------------------
 * | Application Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register all of the routes for an application.
 * | It is a breeze. Simply tell Lumen the URIs it should respond to
 * | and give it the Closure to call when that URI is requested.
 * |
 */
$router->get('/', function () {
    return app()->version();
});

/**
 * Authentication
 */
$router->group([
    'namespace' => 'Lumia\Http\Controllers\Auth',
    'prefix' => '/auth',
    'as' => 'auth'
], function ($router) {
    $router->post('/login', [
        'as' => 'login',
        'uses' => 'LoginController@login'
    ]);
    $router->group([
        'prefix' => '/password',
        'as' => 'password'
    ], function ($router) {
        $router->post('/reset', [
            'as' => 'reset',
            'uses' => 'ResetPasswordController@reset'
        ]);
        $router->post('/forgot', [
            'as' => 'forgot',
            'uses' => 'ForgotPasswordController@forgot'
        ]);
    });
    $router->post('/logout', [
        'middleware' => [
            'auth:api'
        ],
        'as' => 'logout',
        'uses' => 'LogoutController@logout'
    ]);
});

$router->group([
    'middleware' => [
        'auth:api'
    ],
    'namespace' => 'App\Http\Controllers'
], function ($router) {
    // Users
    $router->group([
        'prefix' => 'users'
    ], function ($router) {
        $router->post('/', [
            'uses' => 'UserController@store',
            'middleware' => [
                'scope:users',
                'users:create'
            ]
        ]);
        $router->get('/', [
            'uses' => 'UserController@index',
            'middleware' => [
                'scope:users',
                'users:list'
            ]
        ]);
        $router->get('/{id}', [
            'uses' => 'UserController@show',
            'middleware' => [
                'scope:users',
                'users:read'
            ]
        ]);
        $router->put('/{id}', [
            'uses' => 'UserController@update',
            'middleware' => [
                'scope:users',
                'users:write'
            ]
        ]);
        $router->delete('/{id}', [
            'uses' => 'UserController@destroy',
            'middleware' => [
                'scope:users',
                'users:delete'
            ]
        ]);
    });
});

