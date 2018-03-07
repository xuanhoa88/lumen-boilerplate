<?php
namespace Lumia;

use Illuminate\Support\ServiceProvider;

class LumiaServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
        $this->registerServiceProviders();
    }

    protected function registerCommands()
    {
        $this->commands(\Lumia\Console\RouteListCommand::class);
    }

    protected function registerServiceProviders()
    {
        app()->register(\Lumia\Uuid\UuidServiceProvider::class);
    }
}