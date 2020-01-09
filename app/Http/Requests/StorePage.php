<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePage extends FormRequest
{
    use ParagraphTagTrait;

    public function rules()
    {
        return [
            'title'    => 'required|min:5|max:100',
            'body'     => 'required',
        ];
    }
}
