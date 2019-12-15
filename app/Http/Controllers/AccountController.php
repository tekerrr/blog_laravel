<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAccount;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        return view('account.edit', ['user' => auth()->user()]);
    }

    public function update(UpdateAccount $request)
    {
        $attributes = $request->validated();
        $attributes['about'] = $request->get('about');

        auth()->user()->update($attributes);

        flash('Данные аккаунта успешно обновлены.');

        return back();
    }
}
