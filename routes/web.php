<?php

// Base redirects
Route::redirect('/', '/articles');

// User paths
Auth::routes(['reset' => false, 'confirm' => false, 'verify' => false]);
Route::get('/account', 'AccountController@edit')->name('account.edit');
Route::patch('/account', 'AccountController@update')->name('account.update');

Route::get('/password', 'Auth\PasswordController@edit')->name('password.edit');
Route::patch('/password', 'Auth\PasswordController@update')->name('password.update');

Route::patch('/avatar', 'AvatarController@update')->name('avatar.update');
Route::delete('/avatar', 'AvatarController@destroy')->name('avatar.destroy');

// Subscribe paths
Route::post('/subscribe', 'SubscriberController@subscribe')->name('subscribe');
Route::post('/unsubscribe', 'SubscriberController@unsubscribe')->name('unsubscribe');
Route::get('/unsubscribe/{subscriber}', 'SubscriberController@unsubscribeByLink')->name('unsubscribe.link');

// Content paths
Route::resource('articles', 'ArticleController')->only('index', 'show');
Route::post('/articles/{article}/comments', 'CommentController@store')->name('comments.store');
Route::resource('pages', 'PageController')->only('show');

// Input Elements
Route::patch('/per-page', 'CustomPaginatorController@perPage')->name('custom-controller.per-page');

// Admin (stuff) paths
Route::prefix('/admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('articles', 'Admin\ArticleController');
    Route::patch('articles/{article}/set-active-status', 'Admin\ArticleController@setActiveStatus')->name('articles.set-active-status');

    Route::resource('comments', 'Admin\CommentController')->only('index', 'destroy');
    Route::patch('comments/{comment}/set-active-status', 'Admin\CommentController@setActiveStatus')->name('comments.set-active-status');

    Route::resource('pages', 'Admin\PageController');
    Route::patch('pages/{page}/set-active-status', 'Admin\PageController@setActiveStatus')->name('pages.set-active-status');

    Route::resource('users', 'Admin\UserController')->except('create', 'store', 'show');
    Route::patch('users/{user}/set-active-status', 'Admin\UserController@setActiveStatus')->name('users.set-active-status');

    Route::patch('users/{user}/avatar', 'Admin\AvatarController@update')->name('avatar.update');
    Route::delete('users/{user}/avatar', 'Admin\AvatarController@destroy')->name('avatar.destroy');

    Route::patch('users/{user}/roles/{role}/add', 'Admin\RoleController@add')->name('users.roles.add');
    Route::patch('users/{user}/roles/{role}/remove', 'Admin\RoleController@remove')->name('users.roles.remove');

    Route::resource('subscribers', 'Admin\SubscriberController')->only('index', 'destroy');

    Route::get('settings', 'Admin\SettingsController@edit')->name('settings.edit');
    Route::patch('settings', 'Admin\SettingsController@update')->name('settings.update');
});
