<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = ['email'];

    public function getRouteKeyName()
    {
        return 'email';
    }
}
