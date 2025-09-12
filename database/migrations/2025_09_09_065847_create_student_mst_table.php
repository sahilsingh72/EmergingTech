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
        Schema::create('student_mst', function (Blueprint $table) {
            $table->id('stu_id'); // Primary key
            $table->string('stu_name');
            $table->string('stu_roll_number')->nullable();
            $table->enum('stu_gender', ['Male', 'Female', 'Other'])->nullable();
            $table->date('stu_dob')->nullable();
            $table->string('stu_fathername')->nullable();

            $table->unsignedBigInteger('stu_classid')->nullable();
            $table->string('stu_class')->nullable();

            $table->unsignedBigInteger('stu_sectionid')->nullable();
            $table->string('stu_section')->nullable();

            $table->unsignedBigInteger('stu_scm_id')->nullable();
            $table->unsignedBigInteger('stu_scm_udise')->nullable();
            $table->string('stu_schoolname')->nullable();

            $table->unsignedBigInteger('stu_distid')->nullable();
            $table->string('stu_dist')->nullable();

            $table->unsignedBigInteger('stu_blockid')->nullable();
            $table->string('stu_block')->nullable();

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_mst');
    }
};
