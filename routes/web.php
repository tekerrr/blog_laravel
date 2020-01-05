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
    Route::patch('articles/{article}/set-active-status', 'Admin\ArticleController@setActiveStatus')->name('article.set-active-status');
});
