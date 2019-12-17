<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['path'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Image $image) {
            \Storage::delete($image->path);
        });
    }
}
