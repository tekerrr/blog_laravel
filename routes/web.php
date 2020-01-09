<?php

// Base redirects
Route::redirect('/', '/articles');

// Auth
Auth::routes(['reset' => false, 'confirm' => false, 'verify' => false]);

// Subscription
Route::post('/subscribe', 'SubscriberController@subscribe')->name('subscribe');
Route::get('/unsubscribe/{subscriber}', 'SubscriberController@unsubscribeByLink')->name('unsubscribe.link');

// Content
Route::resource('articles', 'ArticleController')->only('index', 'show');
Route::resource('pages', 'PageController')->only('show');

// Input Elements
Route::patch('/per-page', 'CustomPaginatorController@perPage')->name('custom-controller.per-page');

// User paths
Route::middleware('auth')
    ->group(function () {
        // Account
        Route::get('/account', 'AccountController@edit')->name('account.edit');
        Route::patch('/account', 'AccountController@update')->name('account.update');

        // Password
        Route::get('/password', 'Auth\PasswordController@edit')->name('password.edit');
        Route::patch('/password', 'Auth\PasswordController@update')->name('password.update');

        // Avatar
        Route::patch('/avatar', 'AvatarController@update')->name('avatar.update');
        Route::delete('/avatar', 'AvatarController@destroy')->name('avatar.destroy');

        // Subscription
        Route::post('/unsubscribe', 'SubscriberController@unsubscribe')->name('unsubscribe');

        // Comment
        Route::post('/articles/{article}/comments', 'CommentController@store')->name('comments.store');
    });

// Author paths
Route::prefix('/admin')
    ->namespace('Admin')
    ->name('admin.')
    ->middleware(['auth', 'role:author'])
    ->group(function () {
        // Articles
        Route::resource('articles', 'ArticleController');
        Route::patch('/articles/{article}/set-active-status', 'ArticleController@setActiveStatus')->name('articles.set-active-status');

        // Comments
        Route::resource('comments', 'CommentController')->only('index', 'destroy');
        Route::patch('/comments/{comment}/set-active-status', 'CommentController@setActiveStatus')->name('comments.set-active-status');

        // Static pages
        Route::resource('pages', 'PageController');
        Route::patch('/pages/{page}/set-active-status', 'PageController@setActiveStatus')->name('pages.set-active-status');
    });

// Admin paths
Route::prefix('/admin')
    ->namespace('Admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        // Users
        Route::resource('users', 'UserController')->except('create', 'store', 'show');
        Route::patch('/users/{user}/set-active-status', 'UserController@setActiveStatus')->name('users.set-active-status');

        // User's avatars
        Route::patch('/users/{user}/avatar', 'AvatarController@update')->name('avatar.update');
        Route::delete('/users/{user}/avatar', 'AvatarController@destroy')->name('avatar.destroy');

        // User's roles
        Route::patch('/users/{user}/roles/{role}/add', 'RoleController@add')->name('users.roles.add');
        Route::patch('/users/{user}/roles/{role}/remove', 'RoleController@remove')->name('users.roles.remove');

        // Subscribers
        Route::resource('subscribers', 'SubscriberController')->only('index', 'destroy');

        // Site settings
        Route::get('/settings', 'SettingsController@edit')->name('settings.edit');
        Route::patch('/settings', 'SettingsController@update')->name('settings.update');
    });
