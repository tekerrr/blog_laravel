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
        $this->update(['is_active' => true]);

        return $this;
    }

    public function deactivate()
    {
        $this->update(['is_active' => false]);

        return $this;
    }
}
