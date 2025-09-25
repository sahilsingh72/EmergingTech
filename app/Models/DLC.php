<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DLC extends Model
{
    protected $table = 'dlc_mst'; // table name from SQL file
    protected $primaryKey = 'dlc_id'; // primary key
    public $incrementing = true; // because dlc_id is auto-increment
    protected $keyType = 'int'; // if dlc_id is varchar, use int
    protected $fillable = [
        'dlc_id',
        'dlc_cnm',
        'dlc_dst',
        'dlc_dstID',
    ];

    public function schools()
    {
        return $this->hasMany(School::class, 'scm_distid', 'dlc_dstID');
    }


}
