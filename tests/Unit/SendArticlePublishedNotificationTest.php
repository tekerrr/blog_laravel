<?php

namespace Tests\Unit;

use App\Article;
use App\Events\ArticlePublished;
use App\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SendArticlePublishedNotificationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function listener_send_emails_to_all_subscribers_when_article_published()
    {
        // Arrange
        \Notification::fake();
        $subscribers = factory(Subscriber::class, 2)->create();
        $article = factory(Article::class)->create(['is_active' => false]);

        // Act
        $article->activate();

        // Assert
        \Notification::assertSentTo($subscribers->first(), \App\Notifications\ArticlePublished::class);
        \Notification::assertSentTo($subscribers->last(), \App\Notifications\ArticlePublished::class);
    }
}
