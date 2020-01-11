<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use HasImage;
    use CanBeActivated;

    protected $fillable = ['name', 'email', 'password', 'about', 'is_active'];
    protected $hidden = ['password', 'remember_token'];

    protected static function boot()
    {
        parent::boot();

        static::updated(function (User $user) {
            if (
                ! empty($user->isDirty('email'))
                && ($sub = Subscriber::where('email', $user->getOriginal('email'))->first())
            ) {
                $sub->update(['email' => $user->email]);
            }
        });

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

    public function getRoles(bool $asText = false)
    {
        $roles = $this->roles()->pluck('role');

        return $asText ? $roles->implode(', ') : $roles->toArray();
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('role', $role)->exists();
    }

    public function hasRoles(array $roles): bool
    {
        return $this->roles()->whereIn('role', $roles)->exists();
    }

    /**
     * @param Role|string $role
     * @return User
     */
    public function addRole($role): User
    {
        $this->roles()->attach($role instanceof \App\Role ? $role->id : \App\Role::firstOrCreate(['role' => $role])->id);

        return $this;
    }

    /**
     * @param Role|string $role
     * @return User
     */
    public function removeRole($role): User
    {
        if ($role instanceof \App\Role || ($role = \App\Role::first('name', $role)) instanceof \App\Role) {
            $this->roles()->detach($role->id);
        }

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
