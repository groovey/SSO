<?php

namespace Groovey\SSO\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;
use Silex\Api\BootableProviderInterface;
use Groovey\SSO\Client;
use Groovey\SSO\Server;

class SSOServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function register(Container $app)
    {
        $app['sso.client'] = function ($app) {
            return new Client($app, $app['sso.domain']);
        };

        $app['sso.server'] = function ($app) {
            return new Server($app, $app['sso.domain']);
        };
    }

    public function boot(Application $app)
    {
    }
}
