<?php


namespace Tests;


use Illuminate\Http\UploadedFile;

trait WithImage
{
    protected $image;

    protected function getImage(string $name = 'image.jpg')
    {
        $this->image = \Storage::putFile('/public', UploadedFile::fake()->image('image.jpg'));
        return $this->image;
    }

    protected function getUploadedImage(string $name = 'image.jpg')
    {
        return UploadedFile::fake()->image($name);
    }

    /** @after */
    public function tearDownWithImage()
    {
        $this->createApplication();

        if ($this->image) {
            \Storage::delete($this->image);
            \Storage::assertMissing($this->image);
        }
    }
}
