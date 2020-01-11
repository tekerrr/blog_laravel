<?php

namespace App\Listeners;

use App\Events\ArticlePublished;
use App\Subscriber;

class SendArticlePublishedNotification
{
    public function handle(ArticlePublished $event)
    {
        Subscriber::all()->map->notify(new \App\Notifications\ArticlePublished($event->article));
    }
}
