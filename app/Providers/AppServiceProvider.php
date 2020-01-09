<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \View::composer('layout.navbar.public', function ($view) {
            $view->with('articles', \App\Article::active()->latest()->take(config('content.navbar.articles'))->get());
            $view->with('pages', \App\Page::active()->get());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Config::loadConfig();
    }
}
