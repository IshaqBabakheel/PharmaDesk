<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Manufacturer extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'website',
        'address',
        'city',
        'country',
        'notes',
        'status',
        'sort_order',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('manufacturers')
            ->logOnly([
                'name',
                'phone',
                'email',
                'address',
                'description',
                'status',
                'sort_order',
            ])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Medicines belonging to this manufacturer.
     */
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }

    /**
     * Scope: Active records only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


}
