<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstituteFeedback extends Model
{
    protected $table = 'institute_feedback_entry';

    protected $fillable = [
        'school_id',
        'training_date',
        'planning_coordination',
        'timeliness_discipline',
        'trainer_quality',
        'student_engagement',
        'clarity_explanation',
        'iot_robotics_usefulness',
        'ai_relevance',
        'cyber_awareness_need',
        'equipment_quality',
        'overall_impact',
        'awareness_increased',
        'student_enthusiasm',
        'program_beneficial',
        'future_program_interest',
        'submitted_by',
    ];

    public function school()
    {
        return $this->belongsTo(\App\Models\School::class, 'school_id', 'scm_id');
    }
}
