<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('training_uploads', function (Blueprint $table) {
            $table->id('upload_id');
            
            // Relations
            $table->unsignedBigInteger('zone_id')->nullable();;   // from district table
            $table->unsignedBigInteger('dist_id')->nullable();;   // from district table
            $table->unsignedBigInteger('school_id')->nullable();;     // from schools table
            $table->unsignedBigInteger('coordinator_id')->nullable(); // uploader coordinator
            $table->unsignedBigInteger('trainer_id')->nullable();     // uploader trainer
            
            // File details
            $table->unsignedBigInteger('filetype_id')->nullable();;
            $table->string('file_type');       // attendance_sheet, training_photo, video, certificate, feedback, bill etc
            $table->json('file_name');       // original file name
            $table->json('onedrive_path');   // OneDrive path
            $table->json('onedrive_url')->nullable(); // Sharable link (optional)

            $table->unsignedBigInteger('uploaded_by'); // user_id of uploader (login)
            $table->date('training_date')->nullable();
            $table->text('description')->nullable(); // <-- New column

             // Add timestamps
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_uploads');
    }
};
