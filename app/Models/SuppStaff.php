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


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
