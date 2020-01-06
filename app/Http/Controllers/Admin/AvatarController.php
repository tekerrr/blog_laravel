<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;

class AvatarController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function update(User $user)
    {
        request()->validate([
            'avatar' => 'required|file|image|max:' . config('content.avatar.max_size'),
        ]);

        $path = request()->file('avatar')->store('public/avatars');
        $user->saveImage($path);

        flash('Аватар обновлён.');
        return back();
    }

    public function destroy(User $user)
    {
        $user->deleteImage();

        flash('Аватар удалён.');
        return back();
    }
}
