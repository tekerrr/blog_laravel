<?php

namespace App\Http\Requests;

trait StoreFileTrait
{
    public function storeFile(string $path, string $field = 'image') : ?string
    {
        return $this->has($field) ? $this->file($field)->store($path) : null;
    }
}
