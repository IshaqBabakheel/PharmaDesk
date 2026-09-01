<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    // use SoftDeletes;
    protected $fillable = [
        'key',
        'value',
        'type',
        'description'
    ];

    /**
    * Get Settings by key
    */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public function set($key, $value, $type = 'string')
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type
            ]
        );

    }
}
