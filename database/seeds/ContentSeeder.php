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
            $article->image()->save(factory(\App\Image::class)->state('withoutImageable')->make());
        });
    }
}
