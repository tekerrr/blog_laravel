<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Subscriber extends Model
{
    use Notifiable;

    protected $fillable = ['email'];

    public function getRouteKeyName()
    {
        return 'email';
    }
}
