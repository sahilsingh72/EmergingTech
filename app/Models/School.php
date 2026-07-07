<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'school_mst';
    protected $primaryKey = 'scm_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'scm_id',
        'batch_id',
        'scm_name',
        'scm_udise_code',
        'scm_dist_id',
        'scm_dist',
        'scm_zone_id',
        'scm_hq_id',
        'scm_hq_type',
        'scm_subdivision_id',
        'scm_subdivision_name',
        'scm_address',
        'scm_pin_code',

        'scm_hm_name',
        'scm_hm_phone',
        'scm_hm_wp',
        'scm_hm_email',

        'scm_spoc_name',
        'scm_spoc_phone',
        'scm_spoc_wp',
        'scm_spoc_email',

        'scm_avail_3_class',
        'scm_smartclass',
        'scm_powerbackup',
        'scm_powerbackup_type',
        'scm_internet',
        'scm_internet_type',

        'training_date',
        'training_completed',
   ];

    public function district()
        {
            return $this->belongsTo(District::class, 'scm_dist_id', 'DSM_DSCD');
        }

    public function trainers()
    {
        return $this->belongsToMany(Trainer::class, 'trainer_scm_allocation', 'scm_id', 'trainer_id');
    }

    public function coordinators()
    {
        return $this->belongsToMany(Coordinator::class, 'coordinator_scm_allocation', 'scm_id', 'coordinator_id');
    }
    public function staffs()
    {
        return $this->belongsToMany(SuppStaff::class, 'staff_scm_allocation', 'scm_id', 'staff_id');
    }
    public function students()
    {
        return $this->hasMany(StudentMst::class, 'stu_scm_id', 'scm_id');
    }
    public function trainingUploads()
    {
        return $this->hasMany(
            TrainingUpload::class,
            'school_id',   // FK in training_uploads table
            'scm_id'       // PK in school_mst
        );
    }
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function scopeForBatch($query, ?int $batchId = null)
    {
        $batchId ??= (int) session('active_batch_id');

        return $query->where('batch_id', $batchId);
    }

}
