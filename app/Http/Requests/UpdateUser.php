<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUser extends FormRequest
{
    public function rules()
    {
        return [
            'name'  => ['required', 'string', 'max:255', Rule::unique('users')->ignore($this->user)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user)],
        ];
    }
}
