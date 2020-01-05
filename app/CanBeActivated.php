<?php


namespace App;


trait CanBeActivated
{
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function activate()
    {
        $this->is_active = true;
        $this->save();

        return $this;
    }

    public function deactivate()
    {
        $this->is_active = false;
        $this->save();

        return $this;
    }

    public function previousActive()
    {
        return self::active()->where('id', '<', $this->id)->orderByDesc('id')->first();
    }

    public function nextActive()
    {
        return self::active()->where('id', '>', $this->id)->first();
    }
}
