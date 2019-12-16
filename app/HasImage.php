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

    public function getImageUrl()
    {
        return $this->image ? \Storage::url($this->image->path) : null;
    }

    public function updateImage($path)
    {
        $this->deleteImage();
        $this->image()->create(['path' => $path]);
    }

    public function deleteImage()
    {
        if ($this->image) {
            \Storage::delete($this->image->path);
            $this->image()->delete();
        }
    }
}
