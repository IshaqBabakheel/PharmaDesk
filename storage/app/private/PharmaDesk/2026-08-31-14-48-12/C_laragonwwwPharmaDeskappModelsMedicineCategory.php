<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Medicine;

class MedicineCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',

        'slug',

        'description',

        'status',

        'sort_order',

        'created_by',

        'updated_by',

        'deleted_by'
    ];

    protected $casts = [

        'status'=>'boolean'

    ];

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class,'deleted_by');
    }

    /**
     * Scope: Active records only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
