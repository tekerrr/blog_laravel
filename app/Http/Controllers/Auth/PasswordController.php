<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePassword;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('auth.password.edit');
    }

    public function update(UpdatePassword $request)
    {
        if (! \Hash::check($request->get('current-password'), auth()->user()->getAuthPassword())) {
            flash('Неверный пароль.', 'danger');
            return back();
        }

        auth()->user()->update(['password' => \Hash::make($request->get('password'))]);

        flash('Пароль успешно изменён.');
        return redirect()->route('account.edit');
    }
}
