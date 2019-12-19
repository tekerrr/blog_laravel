<?php


namespace Tests;


use Faker\Factory;

trait WithRoles
{
    private function createAdmin() : \App\User
    {
        return factory(\App\User::class)->create()->addRole('admin');
    }

    private function createAuthor() : \App\User
    {
        return factory(\App\User::class)->create()->addRole('author');
    }

    private function createUser() : \App\User
    {
        return factory(\App\User::class)->create();
    }

    private function actingAsAdmin() : \App\User
    {
        $this->actingAs($admin = $this->createAdmin());

        return $admin;
    }

    private function actingAsAuthor() : \App\User
    {
        $this->actingAs($author = $this->createAuthor());

        return $author;
    }

    private function actingAsUser() : \App\User
    {
        $this->actingAs($user = $this->createUser());

        return $user;
    }

    public function RoleProvider()
    {
        $faker = Factory::create();

        return [
            'guest'  => ['guest', false],
            'user'   => ['user', true],
            'author' => ['author', true],
            'admin'  => ['admin', true],
        ];
    }

    public function AuthRoleProvider()
    {
        $faker = Factory::create();

        return [
            'user'   => ['user'],
            'author' => ['author'],
            'admin'  => ['admin'],
        ];
    }

    protected function actingAsRole($role) : ?\App\User
    {
        $roles = [
            'guest'  => null,
            'user'   => 'actingAsUser',
            'author' => 'actingAsAuthor',
            'admin'  => 'actingAsAdmin',
        ];

        return $roles[$role] ? $this->{$roles[$role]}() : null;
    }
}
