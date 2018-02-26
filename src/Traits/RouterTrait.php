<?php

namespace SimpleUser\Traits;


trait RouterTrait
{
    /**
     * @param string $name
     * @return bool
     */
    protected function routeExists(string $name): bool
    {
        $router = $this->get('router');

        return (null === $router->getRouteCollection()->get($name)) ? false : true;
    }
}