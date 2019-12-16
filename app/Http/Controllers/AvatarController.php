<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AvatarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update()
    {
        if (! ($path = request()->file('avatar')->store('public/avatars'))) {
            flash('Ошибка при загрузке файла', 'danger');
            return back();
        }

        // TODO добавить проверку на размер

        auth()->user()->updateImage($path);

        flash('Аватар обновлён.');
        return back();
    }

    public function destroy()
    {
        auth()->user()->deleteImage();

        flash('Аватар удалён.');
        return back();
    }
}
