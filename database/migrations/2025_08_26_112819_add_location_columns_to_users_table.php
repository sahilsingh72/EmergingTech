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
        Schema::table('users', function (Blueprint $table) {
            $table->string('district_id')->nullable();   // For DLCs
            $table->unsignedBigInteger('dlc_id')->nullable(); // For Institutes/Trainers
            $table->unsignedBigInteger('block_id')->nullable(); 
            $table->unsignedBigInteger('institute_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['district_id', 'dlc_id', 'block_id', 'institute_id']);
        });
    }
};
