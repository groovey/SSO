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
        $app      = $this->app;
        $domain   = $this->domain;
        $url      = $domain.'/auth';
        $payload  = $app['jwt']->encode($data);
        $response = $app['http']->request('POST', $url,
                [
                    'headers'  => ['content-type' => 'application/json', 'Accept' => 'application/json'],
                    'body' => $payload
                ]
            );
        $code     = $response->getStatusCode();
        $header   = $response->getHeaderLine('content-type');
        $body     = $response->getBody();

        return (string) $body;
    }
}
