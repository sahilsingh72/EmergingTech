<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampExpenseBill extends Model
{
    protected $table = 'camp_expense_bills';
    protected $primaryKey = 'id'; // custom primary key
    public $incrementing = true; // id is auto-increment
    protected $fillable = [
        'school_id',
        'uploaded_by',
        'bill_type',
        'training_date',
        'amount',
        'bill_path',
        'bill_url',
    ];
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'scm_id');
    }
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id');
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function statusUpdatedByUser()
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }
}