<?php

namespace Groovey\SSO;

use Pimple\Container;

class Client
{
    private $app;
    private $yaml;
    private $domain;

    public function __construct(Container $app, $domain)
    {
        $this->app    = $app;
        $this->domain = $domain;
    }

    public function auth(array $data)
    {
        $app = $this->app;

        return [
            'status'     => 'success',
            'first_name' => 'Harold Kim',
            'last_name'  => 'Cantil',

        ];
    }
}
