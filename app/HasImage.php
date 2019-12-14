<?php


namespace App;


trait HasImage
{
    public static function bootHasImage()
    {
        static::deleting(function ($model) {
            $model->image()->delete();
        });
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
