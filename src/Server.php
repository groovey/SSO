<?php

namespace Groovey\SSO;

use Pimple\Container;

class Server
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
        $domain = $this->domain;
    }
}
