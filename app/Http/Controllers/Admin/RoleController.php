<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Role;

class RoleController extends Controller
{
    public function add(User $user, Role $role)
    {
        $user->addRole($role);

        flash('Роль ' . $role->name . ' добавлена');
        return back();
    }

    public function remove(User $user, Role $role)
    {
        $user->removeRole($role);

        flash('Роль ' . $role->name . ' удалена', 'danger');
        return back();
    }
}
