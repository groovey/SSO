<?php

namespace Groovey\SSO\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;
use Silex\Api\BootableProviderInterface;
use Groovey\SSO\SSO;

class SSOServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function register(Container $app)
    {
        $app['sso'] = function ($app) {
            return new SSO($app, $app['sso.domain']);
        };
    }

    public function boot(Application $app)
    {
    }
}
