<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampTravelBillMember extends Model
{
    protected $fillable = [
        'camp_travel_bill_id',
        'role',
        'member_id',
    ];

    public function bill()
    {
        return $this->belongsTo(CampTravelBill::class, 'camp_travel_bill_id');
    }
     public function trainer()
    {
        return $this->belongsTo(
            Trainer::class,
            'member_id',
            'trainer_id'
        );
    }

    public function coordinator()
    {
        return $this->belongsTo(
            Coordinator::class,
            'member_id',
            'coordinator_id'
        );
    }

    public function staff()
    {
        return $this->belongsTo(
            SuppStaff::class,
            'member_id',
            'ss_id'
        );
    }

    public function getPersonAttribute()
    {
        return match ($this->role) {
            'trainer'     => $this->trainer,
            'coordinator' => $this->coordinator,
            'staff'       => $this->staff,
            default       => null,
        };
    }
}
