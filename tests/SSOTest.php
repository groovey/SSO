<?php

use Silex\Application;
use Groovey\DB\Providers\DBServiceProvider;
use Groovey\SSO\Providers\SSOServiceProvider;
use Groovey\Support\Providers\TraceServiceProvider;
use Groovey\Support\Providers\HttpServiceProvider;
use Groovey\Support\Providers\DateServiceProvider;
use Groovey\Tester\Providers\TesterServiceProvider;
use Groovey\JWT\Providers\JWTServiceProvider;
use Groovey\Security\Providers\SecurityServiceProvider;
use Groovey\Migration\Commands\Init;
use Groovey\Migration\Commands\Reset;
use Groovey\Migration\Commands\Up;
use Groovey\Migration\Commands\Down;
use Groovey\Migration\Commands\Drop;
use Groovey\Seeder\Commands\Init as SeedInit;
use Groovey\Seeder\Commands\Run;

class SSOTest extends PHPUnit_Framework_TestCase
{
    public $app;

    public function setUp()
    {
        $app = new Application();
        $app['debug'] = true;

        $app->register(new TesterServiceProvider());
        $app->register(new DateServiceProvider());
        $app->register(new TraceServiceProvider());
        $app->register(new HttpServiceProvider());
        $app->register(new SecurityServiceProvider());

        $app->register(new JWTServiceProvider(), [
                'jwt.key' => 'W9NFjPk8Az5DPTbF',
            ]);

        $app->register(new SSOServiceProvider(), [
                'sso.domain' => 'http://sso.onekey.dev',
            ]);

        $app->register(new DBServiceProvider(), [
            'db.connection' => [
                'host'      => 'localhost',
                'driver'    => 'mysql',
                'database'  => 'test_sso',
                'username'  => 'root',
                'password'  => '',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'logging'   => true,
            ],
        ]);

        $app['tester']->add([
                new Init($app),
                new Reset($app),
                new Up($app),
                new SeedInit($app),
                new Run($app),
                new Down($app),
                new Drop($app),
            ]);

        Database::init($app);

        $this->app = $app;
    }

    public function tearDown()
    {
        Database::terminate($this->app);
    }

    public function testClientAuth()
    {
        $app = $this->app;

        $data = [
            'app'      => '1',
            'token'    => 'nwd0ZiPkoBDqGrhw',
            'email'    => 'test1@gmail.com',
            'password' => 'test1',
        ];

        $response = $app['sso.client']->auth($data);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('email', $response);
        $this->assertEquals('Success', $response['status']);
    }

    public function testServerAuth()
    {
        $app = $this->app;

        $data = [
            'app'      => '1',
            'token'    => 'nwd0ZiPkoBDqGrhw',
            'email'    => 'test1@gmail.com',
            'password' => 'test1',
        ];

        $response = $app['sso.server']->auth($data);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('email', $response);
        $this->assertEquals('Success', $response['status']);
    }
}
