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
        'scm_udisecode',
        'scm_name',
        'scm_distid',
        'scm_dist',
        'scm_blockid',
        'scm_block',
    ];

     public static function districts()
    {
        return self::select('scm_distid', 'scm_dist')->distinct()->get();
    }

    // Blocks inside a district
    public static function blocks($districtId)
    {
        return self::where('scm_distid', $districtId)
            ->select('scm_blockid', 'scm_block')
            ->distinct()
            ->get();
    }

    // Schools inside a block
    public static function institutes($blockId)
    {
        return self::where('scm_blockid', $blockId)
            ->select('id','scm_name')
            ->get();
    }
    
    // Relationship with DLC (by district name)
    public function dlc()
    {
        return $this->belongsTo(DLC::class, 'scm_distid', 'dlc_dstID');
    }
}
