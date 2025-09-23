<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingUpload extends Model
{
    protected $table = 'training_uploads';
    protected $primaryKey = 'upload_id';

    protected $fillable = [
        'zone_id',
        'dist_id',
        'school_id',
        'coordinator_id',
        'trainer_id',
        'filetype_id',
        'file_type',
        'file_name',
        'onedrive_path',
        'onedrive_url',
        'uploaded_by',
        'training_date',
        'description',
    ];

    // Cast JSON fields automatically
    protected $casts = [
        'file_name' => 'array',
        'onedrive_path' => 'array',
        'onedrive_url' => 'array',
        
        'training_date' => 'date',
    ];

    // Relations
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'scm_id');
    }

    public function coordinator()
    {
        return $this->belongsTo(Coordinator::class, 'coordinator_id');
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }
    
    public function uploadby()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id');
    }
}
