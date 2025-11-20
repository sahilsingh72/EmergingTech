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
        'scm_id' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'dist_id', 'DSM_DSCD'); // dist_id in trainers, DSM_DSCD in districts
    }
    public function schools()
    {
        return $this->belongsToMany(School::class, 'trainer_scm_allocation', 'trainer_id', 'scm_id');
    }

    public function getSchoolIdsAttribute()
    {
        return $this->schools->pluck('scm_id')->toArray();
    }
}
