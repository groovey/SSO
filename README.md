# SSO

Groovey SSO Package

## Installation

    $ composer require groovey/sso

## Usage

```php
 <?php

require_once __DIR__.'/vendor/autoload.php';

use Silex\Application;
use Groovey\DB\Providers\DBServiceProvider;
use Groovey\SSO\Providers\SSOServiceProvider;
use Groovey\Support\Providers\TraceServiceProvider;
use Groovey\Support\Providers\HttpServiceProvider;
use Groovey\Support\Providers\DateServiceProvider;
use Groovey\Tester\Providers\TesterServiceProvider;
use Groovey\JWT\Providers\JWTServiceProvider;
use Groovey\Security\Providers\SecurityServiceProvider;

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


$data = [
            'app'      => '1',
            'token'    => 'nwd0ZiPkoBDqGrhw',
            'email'    => 'test1@gmail.com',
            'password' => 'test1',
        ];

$response = $app['sso.client']->auth($data);

print_r($response);

```