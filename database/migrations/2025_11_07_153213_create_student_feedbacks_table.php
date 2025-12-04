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
        Schema::create('student_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stu_id')->index();
            $table->unsignedInteger('school_id')->nullable();

            // =========================
            // Pre Feedback
            // =========================
            $table->boolean('pre_attended_training')->nullable();
            $table->string('pre_if_any')->nullable();
            $table->boolean('pre_heard_technologies')->nullable();
            $table->boolean('pre_heard_ai')->default(false);
            $table->boolean('pre_heard_iot')->default(false);
            $table->boolean('pre_heard_cybersecurity')->default(false);

            $table->tinyInteger('pre_know_tech')->nullable();
            $table->tinyInteger('pre_confidence')->nullable();
            $table->tinyInteger('pre_interest')->nullable();
            $table->tinyInteger('pre_usefulness')->nullable();
            $table->text('pre_expectations')->nullable();

            // =========================
            // Post Feedback
            // =========================
            $table->string('post_interested_course')->nullable();
            $table->tinyInteger('post_knowledge_improve')->nullable();
            $table->tinyInteger('post_confidence_now')->nullable();
            $table->tinyInteger('post_engagement')->nullable();
            $table->tinyInteger('post_usefulness')->nullable();
            $table->tinyInteger('post_demo_helpfulness')->nullable();
            $table->tinyInteger('post_topic_coverage')->nullable();
            $table->tinyInteger('post_hands_on_usefulness')->nullable();
            $table->tinyInteger('post_real_life_use')->nullable();
            $table->tinyInteger('post_trainer_rating')->nullable();
            $table->tinyInteger('post_overall_satisfaction')->nullable();
            $table->tinyInteger('post_interest_increase')->nullable();
            $table->tinyInteger('post_motivation_future')->nullable();

            $table->text('post_comments')->nullable();
            $table->text('post_most_useful')->nullable();

            $table->timestamps();

           });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_feedbacks');
    }
};
