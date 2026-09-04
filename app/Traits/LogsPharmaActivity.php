<?php

namespace App\Traits;

use Spatie\Activitylog\Support\LogOptions;

trait LogsPharmaActivity
{
    protected function defaultActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}