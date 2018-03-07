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

// Generate random string
$router->get('appKey', function () {
    return str_random('32');
});

// route for creating access_token
$router->post('accessToken', 'AccessTokenController@createAccessToken');

$router->group([
    'middleware' => [
        'auth:api',
        'throttle:60'
    ]
], function ($router) {
    // Users
    $router->group([
        'prefix' => 'users',
        'namespace' => '\App\Http\Controllers'
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

