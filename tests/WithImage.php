<?php


namespace Tests;


use Illuminate\Http\UploadedFile;

trait WithImage
{
    public function getImage(string $name = 'image.jpg')
    {
        return \Storage::putFile('/public', UploadedFile::fake()->image('image.jpg'));
    }

    public function deleteImage(string $path)
    {
        \Storage::delete($path);
        \Storage::assertMissing($path);
    }
}
