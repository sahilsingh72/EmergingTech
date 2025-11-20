<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoGallery extends Model
{
    use HasFactory;

    protected $table = 'photo_gallery';

    protected $fillable = [
        'uploaded_by',
        'school_id',
        'file_name',
        'onedrive_url',
        'onedrive_path',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'scm_id');
    }
}

