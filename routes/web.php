<?php

Route::redirect('/', '/articles');

Auth::routes(['reset' => false, 'confirm' => false, 'verify' => false]);

Route::resource('pages', 'PageController')->only('show');

Route::get('/account', 'AccountController@edit')->name('account.edit');
Route::patch('/account', 'AccountController@update')->name('account.update');

Route::post('/subscriber', 'SubscriberController@store')->name('subscriber.store');
Route::delete('/subscriber', 'SubscriberController@destroy')->name('subscriber.destroy');

Route::get('/password', 'Auth\PasswordController@edit')->name('password.edit');
Route::patch('/password', 'Auth\PasswordController@update')->name('password.update');

Route::patch('/avatar', 'AvatarController@update')->name('avatar.update');
Route::delete('/avatar', 'AvatarController@destroy')->name('avatar.destroy');

Route::resource('articles', 'ArticleController')->only('index', 'show');
Route::post('/articles/{article}/comments', 'CommentController@store')->name('comments.store');
