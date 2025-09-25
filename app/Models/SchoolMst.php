<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolMst extends Model
{
    protected $table = 'school_mst'; 
    protected $primaryKey = 'scm_id';
    public $timestamps = false;

    protected $fillable = [
        'scm_udisecode',
        'scm_name',
        'scm_distid',
        'scm_dist',
        'scm_blockid',
        'scm_block',
    ];
}
