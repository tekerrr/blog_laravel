<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUser;
use App\Service\CustomPaginator;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index(CustomPaginator $paginator)
    {
        $users = $paginator->paginate(User::query());

        return view('admin.users.index', compact('users', 'paginator'));
    }

    public function edit(User $user)
    {
        $userRoles = $user->getRoles();

        $roles = \App\Role::all()->map(function ($role) use ($userRoles) {
            $role->isActive = in_array($role->role, $userRoles);
            return $role;
        });

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUser $request, User $user)
    {
        $attributes = $request->validated();
        $attributes['about'] = $request->get('about');

        $user->update($attributes);

        flash('Данные пользователя ' . $user->name . ' успешно обновлены.');
        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        flash('Пользователь '. $user->name . ' удалён', 'danger');
        return back();
    }

    public function setActiveStatus(User $user)
    {
        return request()->has('active') ? $this->activate($user) : $this->deactivate($user);
    }

    protected function activate(User $user)
    {
        $user->activate();

        flash('Пользователь '. $user->name . ' активирован');
        return back();
    }

    protected function deactivate(User $user)
    {
        $user->deactivate();

        flash('Пользователь '. $user->name . ' деактивирован', 'danger');
        return back();
    }
}
