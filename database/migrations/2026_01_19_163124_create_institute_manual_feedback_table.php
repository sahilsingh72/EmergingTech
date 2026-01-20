<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('institute_feedback_entry', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->date('training_date');

            // Ratings (1–5)
            $table->tinyInteger('planning_coordination');
            $table->tinyInteger('timeliness_discipline');
            $table->tinyInteger('trainer_quality');
            $table->tinyInteger('student_engagement');
            $table->tinyInteger('clarity_explanation');
            $table->tinyInteger('iot_robotics_usefulness');
            $table->tinyInteger('ai_relevance');
            $table->tinyInteger('cyber_awareness_need');
            $table->tinyInteger('equipment_quality');
            $table->tinyInteger('overall_impact');

            // Outcome & Impact
            $table->boolean('awareness_increased'); // Yes / No
            $table->enum('student_enthusiasm', ['High', 'Moderate', 'Low']);
            $table->enum('program_beneficial', ['Strongly Agree', 'Agree', 'Neutral', 'Disagree']);
            $table->json('future_program_interest'); // AI, IoT, Cyber, All

            $table->unsignedBigInteger('submitted_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institute_feedback_entry');
    }
};
