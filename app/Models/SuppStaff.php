<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuppStaff extends Model
{
    protected $table = 'support_staff_mst';
    protected $primaryKey = 'ss_id'; // custom primary key
    public $incrementing = true; // ss_id is auto-increment
    protected $keyType = 'int';

    protected $fillable = [
        'ss_name',
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
        'photo',
        'aadhar_card',
    ];

    protected $casts = [
        'education_certificates' => 'array',
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'dist_id', 'DSM_DSCD'); // dist_id in trainers, DSM_DSCD in districts
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function schools()
    {
        return $this->belongsToMany(School::class, 'staff_scm_allocation', 'staff_id', 'scm_id');
    }
    public function getSchoolIdsAttribute()
    {
        return $this->schools->pluck('scm_id')->toArray();
    }
}
