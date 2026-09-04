<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Setting extends Model
{
    use LogsActivity;
    
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

    public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->useLogName('settings')
        ->logOnly([
            'key',
            'value',
            'type',
            'description',
        ])
        ->logOnlyDirty()
        ->dontLogEmptyChanges();
}
}
