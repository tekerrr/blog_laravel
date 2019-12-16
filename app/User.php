<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasImage;

    protected $fillable = ['name', 'email', 'password', 'about'];
    protected $hidden = ['password', 'remember_token'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (User $user) {
            $user->subscription()->delete();
        });
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscriber::class, 'email', 'email');
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('role', $role)->exists();
    }

    public function hasRoles(array $roles): bool
    {
        return $this->roles()->whereIn('role', $roles)->exists();
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

    public function isStuff(): bool
    {
        return $this->hasRoles(['admin', 'author']);
    }

    public function isSubscriber()
    {
        return $this->subscription()->exists();
    }
}
