<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticle extends FormRequest
{
    use ParagraphTagTrait;
    use StoreFileTrait;

    public function rules()
    {
        return [
            'title'    => 'required|min:5|max:100',
            'abstract' => 'required|max:255',
            'body'     => 'required',
            'image'    => 'required|file|image|max:' . config('content.article.image.max_size'),
        ];
    }
}
