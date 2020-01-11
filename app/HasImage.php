<?php

namespace App;

trait HasImage
{
    public static function bootHasImage()
    {
        static::deleting(function ($model) {
            $model->deleteImage();
        });
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function saveImage($path)
    {
        $this->deleteImage();
        $this->image()->create(['path' => $path]);
        $this->refresh();
    }

    public function deleteImage()
    {
        optional($this->image)->delete();
    }

    public function getImageUrl()
    {
        return $this->image ? \Storage::url($this->image->path) : null;
    }
}
