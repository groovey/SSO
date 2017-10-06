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
        $domain      = $this->domain;
        $appId       = $data['app'];
        $token       = $data['token'];
        $email       = $data['email'];
        $password    = $data['password'];
        $application = $this->getApp($appId, $token);
        $user        = $this->getUser($email);

        if (!$application) {
            return $this->response('Fail', 'Invalid Application');
        }

        if (!$user) {
            return $this->response('Fail', 'Invalid User');
        }

        $validPassword = $this->validatePassword($password, $user);
        if (!$validPassword) {
            return $this->response('Fail', 'Invalid Password');
        }

        $appUser = $this->validateAppUser($application, $user);
        if (!$validPassword) {
            return $this->response('Fail', 'Invalid Application User');
        }

        $this->updateLastLogin($user);

        return [
            'status'     => 'Success',
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'email'      => $user['email'],
        ];
    }

    private function response($status, $message)
    {
        return [
            'status'  => $status,
            'message' => $message,
        ];
    }

    private function getApp($appId, $token)
    {
        $app    = $this->app;
        $result = $app['db']::table('apps')
                            ->where([
                                ['id', '=', $appId],
                                ['status', '=', 'active'],
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
                        ['status', '=', 'active'],
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

    private function validateAppUser($application, $user)
    {
        $app           = $this->app;
        $applicationId = $application['id'];
        $userId        = $user['id'];
        $result        = $app['db']::table('apps_users')
                            ->where([
                                ['app_id', '=', $applicationId],
                                ['user_id', '=', $userId],
                            ])
                            ->limit(1)
                            ->get();

        return ($result) ? (array) $result[0] : null;
    }

    private function updateLastLogin($user)
    {
        $app    = $this->app;
        $userId = $user['id'];
        $now    = $app['date']::now();

        $app['db']::table('users')
                    ->where('id', $userId)
                    ->update(['last_login' => $now]);
    }
}
