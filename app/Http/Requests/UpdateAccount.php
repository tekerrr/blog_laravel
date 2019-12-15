<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccount extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore(auth()->user())],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(auth()->user())],
        ];
    }
}
