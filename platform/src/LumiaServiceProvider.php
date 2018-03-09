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
        $this->commands(\Lumia\Console\KeyGenerateCommand::class);
        $this->commands(\Lumia\Console\RouteListCommand::class);
        $this->commands(\Lumia\Console\VendorPublishCommand::class);
    }

    protected function registerServiceProviders()
    {
        $this->app->register(\Lumia\Packages\Uuid\UuidServiceProvider::class);
        // $this->app->register(\Lumia\Packages\Passport\PassportServiceProvider::class);
    }
}