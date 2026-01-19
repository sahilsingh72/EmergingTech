<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFeedback extends Model
{
    use HasFactory;

    protected $table = 'student_feedbacks';
    protected $primaryKey = 'id';

    protected $fillable = [
        'stu_id',
        'school_id', // will store scm_id from school_mst

        // Pre Feedback
        'pre_attended_training',
        'pre_if_any',
        'pre_heard_technologies',
        'pre_heard_ai',
        'pre_heard_iot',
        'pre_heard_cybersecurity',
        'pre_know_tech',
        'pre_confidence',
        'pre_career',
        'pre_interest',
        'pre_usefulness',
        'pre_expectations',
        'pre_aware',
        'pre_ai_known',
        'pre_et_use',

        // Post Feedback
        'post_interested_course',
        'post_knowledge_improve',
        'post_confidence_now',
        'post_engagement',
        'post_understanding',
        'post_usefulness',
        'post_demo_helpfulness',
        'post_topic_coverage',
        'post_hands_on_usefulness',
        'post_real_life_use',
        'post_cyber_use',
        'post_ai_use',
        'post_iot_use',
        'post_trainer_rating',
        'post_overall_satisfaction',
        'post_interest_increase',
        'post_innovation',
        'post_motivation_future',
        'post_comments',
        'post_most_useful',
    ];

    // Relationships
    public function student()
    {
        // Link to StudentMst model using stu_id
        return $this->belongsTo(StudentMst::class, 'stu_id', 'stu_id');
    }

    public function school()
    {
        // Link to School model using scm_id (your school primary key)
        return $this->belongsTo(School::class, 'school_id', 'scm_id');
    }
}
