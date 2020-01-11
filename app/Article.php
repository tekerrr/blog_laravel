<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use CanBeActivated;
    use HasImage;

    protected $fillable = ['title', 'abstract', 'body', 'is_active'];

    public static function boot()
    {
        parent::boot();

        static::updating(function (Article $article) {
            if (! empty($article->getDirty()['is_active'])) {
                event(new \App\Events\ArticlePublished($article));
            }
        });
    }

    public function getCreatedAtAttribute($value)
    {
        return (new Service\Formatter\RusDate())->format($value);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function previous()
    {
        return self::where('id', '<', $this->id)->orderByDesc('id')->first();
    }

    public function next()
    {
        return self::where('id', '>', $this->id)->first();
    }
}
