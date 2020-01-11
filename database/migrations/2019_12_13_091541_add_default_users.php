<?php

use Illuminate\Database\Migrations\Migration;

class AddDefaultUsers extends Migration
{
    public function up()
    {
        \App\User::create([
            'name'     => 'Admin',
            'email'    => config('admin.email'),
            'password' => Hash::make(config('admin.password')),
            'about'    => 'Admin',
        ])->addRole('admin');

        \App\User::create([
            'name'     => 'Author',
            'email'    => config('admin.author.email'),
            'password' => Hash::make(config('admin.author.password')),
            'about'    => 'Author',
        ])->addRole('author');
    }

    public function down()
    {
        \App\User::where('name', 'Admin')->delete();
        \App\User::where('name', 'Author')->delete();
    }
}
