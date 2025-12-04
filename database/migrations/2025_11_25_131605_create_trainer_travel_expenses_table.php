<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainer_travel_expenses', function (Blueprint $table) {
            $table->id();

            // Trainer Info
            $table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('district_id');
            $table->string('specialization');

            // Main Travel
            $table->string('main_from');
            $table->string('main_to')->default('OKCL, Bhubaneswar');
            $table->date('main_date')->nullable();
            $table->string('main_mode')->nullable();
            $table->decimal('main_amount', 10, 2)->nullable();
            $table->string('main_bill')->nullable();

            // Return Travel
            $table->boolean('has_return')->default(false);
            $table->string('return_from')->nullable();
            $table->string('return_to')->nullable();
            $table->string('return_mode')->nullable();
            $table->decimal('return_amount', 10, 2)->nullable();
            $table->string('return_bill_file')->nullable();

            // Admin Approval
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->text('remarks')->nullable();
            $table->datetime('status_updated_at')->nullable();
            $table->unsignedBigInteger('status_updated_by')->nullable();

            $table->timestamps();

            $table->foreign('trainer_id')->references('trainer_id')->on('trainers')->cascadeOnDelete();
            $table->foreign('status_updated_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainer_travel_expenses');
    }
};
