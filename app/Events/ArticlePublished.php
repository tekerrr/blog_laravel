<?php

namespace App\Events;

use App\Article;

class ArticlePublished
{
    public $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }
}
