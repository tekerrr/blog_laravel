<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';
    public $timestamps = false;
    protected $fillable = ['key', 'value'];

    /**
     *  Load Config
     */
    public static function loadConfig()
    {
        if (\Schema::hasTable('config')) {
            foreach (static::all() as $row) {
                config([$row->key => $row->value]);
            }
        }
    }

    /**
     * Overdrive config/content.php via SettingsTable
     * @param string $key
     * @param string $value
     */
    public static function setContent(string $key, string $value)
    {
        $key = \Str::start($key, 'content.');

        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
