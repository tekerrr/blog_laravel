<?php

namespace App\Http\Controllers;

class AvatarController extends Controller
{
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
