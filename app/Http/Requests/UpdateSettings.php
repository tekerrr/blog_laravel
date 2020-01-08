<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettings extends FormRequest
{
    public function rules()
    {
        return [
            'paginator_items'        => ['required', 'numeric', 'min:1'],
            'navbar_articles'        => ['required', 'numeric', 'min:1'],
            'custom_paginator_items' => ['required', Rule::in(config('content.custom_paginator.options'))],
        ];
    }
}
