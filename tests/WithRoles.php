<?php


namespace Tests;


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
}
