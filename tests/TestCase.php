<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * role: guest, user, author, admin
     * status: null, author, admin, stuff
     * @return array
     */
    public function visitorProvider()
    {
        return [
            'guest'  => ['guest', collect()],
            'user'   => ['user', collect(['auth'])],
            'author' => ['author', collect(['auth', 'author', 'stuff'])],
            'admin'  => ['admin', collect(['auth', 'admin', 'stuff'])],
        ];
    }

    /**
     * role: guest, user
     * auth: true, false
     * @return array
     */
    public function baseVisitorProvider()
    {
        return [
            'guest'  => ['guest', false],
            'user'   => ['user', true],
        ];
    }

    /**
     * role: user, author, admin
     * status: null, stuff, admin
     * @return array
     */
    public function userProvider()
    {
        return [
            'user'   => ['user', collect()],
            'author' => ['author', collect(['author', 'stuff'])],
            'admin'  => ['admin', collect(['admin', 'stuff'])],
        ];
    }

    /**
     * bool: true, false
     * @return array
     */
    public function booleanProvider()
    {
        return [
            'true'  => [true],
            'false' => [false],
        ];
    }

    protected function createUser(array $attributes = []) : \App\User
    {
        return factory(\App\User::class)->create($attributes);
    }

    protected function createUserWithRole(string $role = 'user', array $attributes = []) : \App\User
    {
        $user = $this->createUser($attributes);

        if ($role && $role != 'user') {
            $user->addRole($role);
        }

        return $user;
    }

    protected function actingAsUser(array $attributes = []) : \App\User
    {
        $this->actingAs($user = $this->createUser($attributes));

        return $user;
    }

    protected function actingAsRole($role = 'user', array $attributes = []) : ?\App\User
    {
        if ($role == 'guest') {
            return null;
        }

        $this->actingAs($user = $this->createUserWithRole($role, $attributes));

        return $user;
    }
}
