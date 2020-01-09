<?php

use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Article::class, 30)->create()->map(function ($article) {
            factory(\App\Comment::class, 5)->create(['article_id' => $article->id]);
        });

        factory(\App\Page::class)->create();

        factory(\App\Subscriber::class, 5)->create();
        factory(\App\User::class, 5)->create()->map(function (\App\User $user) {
            $user->subscription()->create();
        });
    }
}
