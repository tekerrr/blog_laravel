<?php

Route::redirect('/', '/articles');

Auth::routes(['reset' => false, 'confirm' => false, 'verify' => false]);

Route::resource('articles', 'ArticleController')->only('index', 'show');
