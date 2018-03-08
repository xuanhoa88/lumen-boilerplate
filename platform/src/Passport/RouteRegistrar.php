<?php

namespace Lumia\Passport;

class RouteRegistrar extends \Laravel\Passport\RouteRegistrar
{
    /**
     * Create a new route registrar instance.
     *
     * @param  $router
     * @param  array $options
     */
    public function __construct($router)
    {
        $this->router = $router;
    }
}
