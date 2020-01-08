<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'value'];

    /**
     * Overdrive config/content.php via SettingsTable
     * @param string $name
     * @param string $value
     */
    public static function setContent(string $name, string $value)
    {
        $name = \Str::start($name, 'content.');

        static::updateOrCreate(['name' => $name], ['value' => $value]);
    }
}
