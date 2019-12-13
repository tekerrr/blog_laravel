<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'abstract', 'body', 'is_active'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getCreatedAtAttribute($value)
    {
        return (new Service\Formatter\RusDate())->format($value);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function activate()
    {
        $this->update(['is_active' => true]);

        return $this;
    }

    public function deactivate()
    {
        $this->update(['is_active' => false]);

        return $this;
    }

    public function previous()
    {
        return self::active()->where('id', '<', $this->id)->orderByDesc('id')->first();
    }

    public function next()
    {
        return self::active()->where('id', '>', $this->id)->first();
    }
}
