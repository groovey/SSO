<?php

namespace Groovey\SSO\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;
use Silex\Api\BootableProviderInterface;
use Groovey\SSO\Client;

class SSOServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function register(Container $app)
    {
        $app['sso.client'] = function ($app) {
            return new Client($app, $app['sso.domain']);
        };
    }

    public function boot(Application $app)
    {
    }
}
