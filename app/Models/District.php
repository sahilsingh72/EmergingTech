<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'dst_mst01'; // table name from SQL file
    protected $primaryKey = 'DSM_DSCD'; // primary key
    public $incrementing = false; // because DSM_DSCD is not auto-increment
    protected $keyType = 'string'; // if DSM_DSCD is varchar, use string, else int
    protected $fillable = [
        'DSM_DSCD',
        'DSM_STCD',
        'DSM_DSNM',
        'DSM_ZONEID',
    ];

    public function schools()
    {
        return $this->hasMany(School::class, 'scm_dist_id', 'DSM_DSCD');
    }
}
