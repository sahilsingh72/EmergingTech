<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coordinator extends Model
{
    protected $table = 'coordinator_mst';
    protected $primaryKey = 'coordinator_id'; // custom primary key
    public $incrementing = true; // coordinator_id is auto-increment
    protected $keyType = 'int';

    protected $fillable = [
        'coordinator_name',
        'email',
        'phone',
        'whatsapp_number',
        'dist_id',
        'scm_id',
        'district',
        'address',
        'pincode',
        'user_id',
        'highest_qual',
        'cv',
        'education_certificates',
        'experience_certificate',
        'photo',
        'aadhar_card',
    ];

    protected $casts = [
        'education_certificates' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
