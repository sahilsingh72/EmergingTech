<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerTravelBill extends Model
{
    protected $table = 'trainer_travel_expenses';
    protected $fillable = [
        'trainer_id', 
        'district_id', 
        'specialization', 
        'main_from',
        'main_to',
        'main_date',
        'main_mode',
        'main_amount',
        'main_bill',
        'main_bill_url', 
        'has_return', 
        'return_from', 
        'return_to', 
        'return_mode',
        'return_amount', 
        'return_bill_file', 
        'return_bill_url', 
        'training_date', 
        'status',
        'remarks',
        'status_updated_at', 
        'status_updated_by'
    ];

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
