<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    protected $fillable = [

        'name',

        'email',

        'password',

        'phone',

        'gender',

        'date_of_birth',

        'address',

        'city',

        'country',

        'profile_photo',

        'status',

        'created_by',

        'updated_by',

        'deleted_by',

    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $guard_name = 'web';


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
}




    

    
