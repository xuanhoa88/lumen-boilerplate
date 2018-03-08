<?php
namespace Lumia\Passport;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;

class PassportServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Connection::class, function () {
            return $this->app['db.connection'];
        });
        
        if (preg_match('/5\.6\.\d+/', $this->app->version())) {
            $this->app->singleton(\Illuminate\Hashing\HashManager::class, function ($app) {
                return new \Illuminate\Hashing\HashManager($app);
            });
        }
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Lumia\Passport\Console\Purge::class
            ]);
        }
    }

    /**
     *
     * @return void
     */
    public function register()
    {
        // Register Service Providers
        $this->app->register(\Laravel\Passport\PassportServiceProvider::class);
        
        // Register Middlewares
        $this->app->routeMiddleware([
            'scopes' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
            'scope' => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class
        ]);
        
        // Register routes
        \Lumia\Passport\Passport::routes($this->app);
    }
}
