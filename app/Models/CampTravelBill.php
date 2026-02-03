<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampTravelBill extends Model
{
    protected $table = 'camp_travel_bills';
    protected $fillable = [
        'school_id',
        'district_id',
        'training_date',

        'main_from',
        'main_to',
        'main_distance',
        'main_amount',
        'total_main_amount',
        'main_bill_path',
        'main_bill_url',

        'has_return',
        'return_from',
        'return_to',
        'return_distance',
        'return_amount',
        'total_return_amount',
        'return_bill_path',
        'return_bill_url',

        'status',
        'remarks',
        'uploaded_by',
        'status_updated_by',
        'status_updated_at',
    ];

    public function members()
    {
        return $this->hasMany(CampTravelBillMember::class, 'camp_travel_bill_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'scm_id');
    }
    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'DSM_DSCD');
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function statusUpdatedByUser()
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }
}
