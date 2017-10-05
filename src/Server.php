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
        $token    = $data['token'];
        $email    = $data['email'];
        $password = $data['password'];

        $appInfo = $this->getApp($appId, $token);
        print_r($appInfo);

        $user = $this->getUser($email);
        print_r($user);

        $validPassword = $this->validatePassword($password, $user);
        var_dump($validPassword);
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

        return ($result) ? (array) $result[0] : null;
    }

    private function getUser($email)
    {
        $app      = $this->app;
        $result   = $app['db']::table('users')
                    ->where([
                        ['email', '=', $email],
                    ])
                    ->limit(1)
                    ->get();

        return ($result) ? (array) $result[0] : null;
    }

    private function validatePassword($password, $user)
    {
        $app    = $this->app;
        $status = $app['password']->verify($password, $user['password']);

        return $status;
    }
}
