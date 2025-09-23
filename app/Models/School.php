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
   ];

    public function district()
        {
            return $this->belongsTo(District::class, 'scm_dist_id', 'DSM_DSCD');
        }

   

    
    
}
