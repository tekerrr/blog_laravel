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
        request()->validate([
            'avatar' => 'required|file|image|max:' . config('content.avatar.max_size'),
        ]);

        $path = request()->file('avatar')->store('public/avatars');
        auth()->user()->saveImage($path);

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
