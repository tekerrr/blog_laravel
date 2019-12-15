<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use CanBeActivated;

    protected $fillable = ['body', 'user_id', 'is_active'];

    public function getCreatedAtAttribute($value)
    {
        return (new Service\Formatter\RusDate())->format($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
