<?php

Route::redirect('/', '/articles');

Auth::routes(['reset' => false, 'confirm' => false, 'verify' => false]);

Route::resource('pages', 'PageController')->only('show');

Route::resource('articles', 'ArticleController')->only('index', 'show');
Route::post('/articles/{article}/comments', 'CommentController@store')
    ->middleware('auth')
    ->name('comments.store')
;
