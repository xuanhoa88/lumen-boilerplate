<?php
namespace Lumia\Passport;

use Laravel\Lumen\Routing\Router;

class RouteRegistrar
{

    /**
     *
     * @var Router
     */
    private $router;

    /**
     *
     * @var array
     */
    private $options;

    /**
     * Create a new route registrar instance.
     *
     * @param
     *            $router
     * @param array $options
     */
    public function __construct(Router $router, array $options = [])
    {
        $this->router = $router;
        $this->options = $options;
    }

    /**
     * Register routes for transient tokens, clients, and personal access tokens.
     *
     * @return void
     */
    public function all()
    {
        $this->forAuthorization();
        $this->forAccessTokens();
        $this->forTransientTokens();
        $this->forClients();
        $this->forPersonalAccessTokens();
    }

    /**
     *
     * @param string $path
     * @return string
     */
    private function prefix($path)
    {
        if (strstr($path, '\\') === false && isset($this->options['namespace']))
            return $this->options['namespace'] . '\\' . $path;
        
        return $path;
    }

    /**
     * Register the routes needed for authorization.
     *
     * @return void
     */
    public function forAuthorization()
    {
        $this->router->group([
            'middleware' => [
                'auth'
            ]
        ], function ($router) {
            $router->get('/authorize', [
                'uses' => $this->prefix('AuthorizationController@authorize')
            ]);
            
            $router->post('/authorize', [
                'uses' => $this->prefix('ApproveAuthorizationController@approve')
            ]);
            
            $router->delete('/authorize', [
                'uses' => $this->prefix('DenyAuthorizationController@deny')
            ]);
        });
    }

    /**
     * Register the routes for retrieving and issuing access tokens.
     *
     * @return void
     */
    public function forAccessTokens()
    {
        $this->router->post('/token', $this->prefix('AccessTokenController@issueToken'));
        
        $this->router->group([
            'middleware' => [
                'auth'
            ]
        ], function () {
            $this->router->get('/tokens', $this->prefix('AuthorizedAccessTokenController@forUser'));
            $this->router->delete('/tokens/{token_id}', $this->prefix('AuthorizedAccessTokenController@destroy'));
        });
    }

    /**
     * Register the routes needed for refreshing transient tokens.
     *
     * @return void
     */
    public function forTransientTokens()
    {
        $this->router->post('/token/refresh', [
            'middleware' => [
                'auth'
            ],
            'uses' => $this->prefix('TransientTokenController@refresh')
        ]);
    }

    /**
     * Register the routes needed for managing clients.
     *
     * @return void
     */
    public function forClients()
    {
        $this->router->group([
            'middleware' => [
                'auth'
            ]
        ], function () {
            $this->router->get('/clients', $this->prefix('ClientController@forUser'));
            $this->router->post('/clients', $this->prefix('ClientController@store'));
            $this->router->put('/clients/{client_id}', $this->prefix('ClientController@update'));
            $this->router->delete('/clients/{client_id}', $this->prefix('ClientController@destroy'));
        });
    }

    /**
     * Register the routes needed for managing personal access tokens.
     *
     * @return void
     */
    public function forPersonalAccessTokens()
    {
        $this->router->group([
            'middleware' => [
                'auth'
            ]
        ], function () {
            $this->router->get('/scopes', $this->prefix('ScopeController@all'));
            $this->router->get('/personal-access-tokens', $this->prefix('PersonalAccessTokenController@forUser'));
            $this->router->post('/personal-access-tokens', $this->prefix('PersonalAccessTokenController@store'));
            $this->router->delete('/personal-access-tokens/{token_id}', $this->prefix('PersonalAccessTokenController@destroy'));
        });
    }
}