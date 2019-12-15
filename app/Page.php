<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use CanBeActivated;

    protected $fillable = ['title', 'body', 'is_active'];
}
