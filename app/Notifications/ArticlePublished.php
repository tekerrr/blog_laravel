<?php

namespace App\Notifications;

use App\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArticlePublished extends Notification implements ShouldQueue
{
    use Queueable;

    public $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Добавлена новая статья')
            ->markdown('mail.article.published', ['article' => $this->article]);
    }
}
