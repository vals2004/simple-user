<?php

namespace SimpleUser;

use SimpleUser\DependencyInjection\SimpleUserExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SimpleUserBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new SimpleUserExtension();
    }
}