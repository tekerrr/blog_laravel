<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePassword extends FormRequest
{
    public function rules()
    {
        return [
            'current-password' => ['required', 'string', 'min:4'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ];
    }
}
