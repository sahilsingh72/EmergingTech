<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialMedia extends Model
{
    use HasFactory;

    protected $table = 'social_media';

    protected $fillable = [
        'user_id',
        'district_name',
        'school_name',
        'training_date',
        'media_type',
        'title',
        'description',
        'media_path',
        'media_url',
        'media_link',
    ];
}
