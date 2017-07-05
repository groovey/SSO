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

$app = new Application();
$app['debug'] = true;

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


```