<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trainers', function (Blueprint $table) {
            $table->id('trainer_id'); // Primary key

            // Basic Info
            $table->string('trainer_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->unsignedBigInteger('dist_id')->nullable();
            $table->string('district')->nullable(); 
            $table->string('pincode', 10)->nullable();
            $table->json('specialization')->nullable();
            $table->text('address')->nullable();

            // File Uploads (store file paths)
            $table->string('cv')->nullable(); // CV/Resume file path
            $table->json('education_certificates')->nullable(); // Multiple files as JSON
            $table->string('experience_certificate')->nullable();
            $table->string('photo')->nullable();
            $table->string('aadhar_card')->nullable();

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
