<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $table = 'trainers';
    protected $primaryKey = 'trainer_id'; // custom primary key
    public $incrementing = true; // trainer_id is auto-increment
    protected $keyType = 'int';

    protected $fillable = [
        'trainer_name',
        'email',
        'phone',
        'whatsapp_number',
        'dist_id',
        'scm_id',
        'district',
        'specialization',
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
        'specialization' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
