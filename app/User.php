<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('role', $role)->exists();
    }

    public function addRole(string $role): User
    {
        $this->roles()->attach(\App\Role::firstOrCreate(['role' => $role]));

        return $this;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isAuthor(): bool
    {
        return $this->hasRole('author');
    }
}
