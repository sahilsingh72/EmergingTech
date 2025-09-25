<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'district_id',
        'dlc_id',
        'block_id',
        'institute_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
    public function district()
    {
        return $this->belongsTo(School::class, 'district_id', 'scm_distid');
    }

    public function block()
    {
        return $this->belongsTo(School::class, 'block_id', 'scm_blockid');
    }

    public function institute()
    {
        return $this->belongsTo(School::class, 'institute_id', 'id'); // if school_mst has pk id
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
     public function dlc()
    {
        return $this->belongsTo(DLC::class, 'dlc_id', 'dlc_id');
    }
}
 