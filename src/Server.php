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
        $domain   = $this->domain;
        $appId    = $data['app'];
        $email    = $data['email'];
        $password = $data['password'];

        $app = $this->getApp($appId, $token);

        $user = $this->getUser($email, $password);

        print_r($user);
    }

    private function getApp($appId, $token)
    {
        $app    = $this->app;
        $result = $app['db']::table('apps')
                            ->where([
                                ['id', '=', $appId],
                                ['token', '=', $token],
                            ])
                            ->limit(1)
                            ->get();

        return $result;
    }

    private function getUser($email, $password)
    {
        $app    = $this->app;
        $result = $app['db']::table('users')
                    ->where([
                        ['email', '=', $email],
                        ['password', '=', $password],
                    ])
                    ->limit(1)
                    ->get();

        print_r($result);

        return $result;
    }

    // private function validateAppUser()
}
