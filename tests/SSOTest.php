<?php

use Silex\Application;
use Symfony\Component\Console\Command\Command;
use Groovey\DB\Providers\DBServiceProvider;
use Groovey\SSO\Providers\SSOServiceProvider;
use Groovey\Support\Providers\TraceServiceProvider;
use Groovey\Support\Providers\HttpServiceProvider;
use Groovey\Tester\Providers\TesterServiceProvider;
use Groovey\JWT\Providers\JWTServiceProvider;
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
        $app->register(new TraceServiceProvider());
        $app->register(new HttpServiceProvider());

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

        $this->app = $app;
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

        echo $response;

        $this->assertRegExp('/success/', $response);
    }

    // public function testServerAuth()
    // {

    //     $app = $this->app;

    //     $data = [
    //         'app'      => 'MRD28O08TT',
    //         'email'    => 'test1@gmail.com',
    //         'password' => 'test1',
    //     ];

    //     // $response = $app['sso.server']->auth($data);

    //     // print $response;

    // }

    /*
    public function testMigrate()
    {
        $app = $this->app;

        $display = $app['tester']->command('migrate:init')->execute()->display();
        $this->assertRegExp('/Sucessfully/', $display);

        $display = $app['tester']->command('migrate:reset')->input('Y\n')->execute()->display();
        $this->assertRegExp('/All migration entries has been cleared/', $display);

        $display = $app['tester']->command('migrate:up')->input('Y\n')->execute()->display();
        $this->assertRegExp('/003/', $display);
    }

    public function testSeed()
    {
        $app = $this->app;

        $display = $app['tester']->command('seed:init')->execute()->display();
        $this->assertRegExp('/Sucessfully/', $display);

        $display = $app['tester']->command('seed:run')->input('Y\n')->execute(['class' => 'Users', 'total' => 5])->display();
        $this->assertRegExp('/End seeding/', $display);

        $display = $app['tester']->command('seed:run')->input('Y\n')->execute(['class' => 'Permissions', 'total' => 5])->display();
        $this->assertRegExp('/End seeding/', $display);
    }

    public function testAuthorize()
    {
        $app = $this->app;
        $app['acl']->authorize($userId = 1);
    }

    public function testPermissions()
    {
        $app   = $this->app;
        $datas = $app['acl']::getPermissions();
        $this->assertContains('template', $datas['template.update']);
    }

    public function testAllow()
    {
        $app = $this->app;
        $app['acl']::setPermission('sample.view', 'value', 'allow');
        $status = $app['acl']->allow('sample.view');
        $this->assertTrue($status);
    }

    public function testDeny()
    {
        $app = $this->app;
        $app['acl']::setPermission('sample.view', 'value', 'deny');
        $status = $app['acl']->deny('sample.view');
        $this->assertTrue($status);
    }

    public function testHelperAllow()
    {
        $app = $this->app;
        $app['acl']::setPermission('sample.view', 'value', 'allow');
        $status = allow('sample.view');
        $this->assertTrue($status);
    }

    public function testHelperDeny()
    {
        $app = $this->app;
        $app['acl']::setPermission('sample.view', 'value', 'deny');
        $status = deny('sample.view');
        $this->assertTrue($status);
    }

    public function testDrop()
    {
        $app = $this->app;

        $display = $app['tester']->command('migrate:down')->input('Y\n')->execute(['version' => '001'])->display();
        $this->assertRegExp('/Downgrading/', $display);

        $display = $app['tester']->command('migrate:drop')->input('Y\n')->execute()->display();
        $this->assertRegExp('/Migrations table has been deleted/', $display);
    }
    */
}
