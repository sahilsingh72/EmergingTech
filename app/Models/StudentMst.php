<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMst extends Model
{
    use HasFactory;

    protected $table = 'student_mst';
    protected $primaryKey = 'stu_id';

    protected $fillable = [
        'stu_name',
        'stu_roll_number',
        'stu_gender',
        'stu_dob',
        'stu_fathername',
        'stu_classid',
        'stu_class',
        'stu_sectionid',
        'stu_section',
        'stu_scm_id',
        'stu_scm_udise',
        'stu_schoolname',
        'stu_distid',
        'stu_dist',
        'stu_blockid',
        'stu_block',
    ];
}
