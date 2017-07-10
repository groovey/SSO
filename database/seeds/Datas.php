<?php

// $ php groovey seed:run Datas

class Datas extends Seeder
{
    public function init()
    {
        $faker = $this->faker;

        $this->define('apps', function () use ($faker) {

            return [
                'status'      => 'active',
                'name'        => 'App 1',
                'description' => $faker->sentence,
                'token'       => '1234567890',

            ];

        }, $truncate = true);


        $this->define('users', function ($data) use ($faker) {

            $email    = $data->email;
            $password = $data->password;

            return [
                'status'      => 'active',
                'first_name'  => $faker->firstName,
                'last_name'   => $faker->lastName,
                'email'       => $email,
                'password'    => $password,
                'last_login'  => $faker->dateTime,
                'created_at'  => $faker->dateTime,
                'modified_at' => $faker->dateTime,

            ];

        }, $truncate = true);


        $this->define('apps_users', function($data) use ($faker) {
            return [
                'app_id'  => $data->app_id,
                'user_id' => $data->user_id
            ];

        }, $truncate = true);

    }

    public function usersFixtures($index){

        $fixtures = [
            [
                'email'    => 'test1@gmail.com',
                'password' => 'test1password',
            ],
            [
                'email'    => 'test2@gmail.com',
                'password' => 'test2password',
            ]
        ];

        return $fixtures[$index];
    }

    public function run()
    {
        $this->seed(function ($counter) {

            $appId = $this->factory('apps')->create();

            for ($i =0; $i < 2; $i++) {

                $data   = $this->usersFixtures($i);
                $userId = $this->factory('users')->create($data);

                $this->factory('apps_users')->create(['app_id' => $appId, 'user_id' => $userId]);
            }
        });
    }
}
