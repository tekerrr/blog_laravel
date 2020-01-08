<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettings;
use App\Settings;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function edit()
    {
        $settings = config('content');

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(UpdateSettings $request)
    {
        Settings::setContent('paginator.items', $request->get('paginator_items'));
        Settings::setContent('navbar.articles', $request->get('navbar_articles'));
        Settings::setContent('custom_paginator.items', $request->get('custom_paginator_items'));

        flash('Настройки сохранены.');
        return back();
    }
}
