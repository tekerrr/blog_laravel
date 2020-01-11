<?php

use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run()
    {
        // Articles with comments (+users)
        factory(\App\Article::class, 30)->create()->map(function ($article) {
            factory(\App\Comment::class, 5)->create(['article_id' => $article->id]);
        });

        // Static pages
        factory(\App\Page::class)->create();

        // Subscribers
        factory(\App\Subscriber::class, 5)->create();

        // Users with subscription
        factory(\App\User::class, 5)->create()->map(function (\App\User $user) {
            $user->subscription()->create();
        });
    }
}
