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
        Schema::create('camp_travel_bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('district_id');

            $table->date('training_date')->nullable();

            // Main travel
            $table->string('main_from');
            $table->string('main_to');
            $table->decimal('main_amount', 10, 2)->default(0);
            $table->decimal('total_main_amount', 10, 2)->default(0);
            $table->string('main_bill_path')->nullable();
            $table->text('main_bill_url')->nullable();

            // Return travel
            $table->boolean('has_return')->default(false);
            $table->string('return_from')->nullable();
            $table->string('return_to')->nullable();
            $table->decimal('return_amount', 10, 2)->nullable();
            $table->decimal('total_return_amount', 10, 2)->nullable();
            $table->string('return_bill_path')->nullable();
            $table->text('return_bill_url')->nullable();

            // Status
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->text('remarks')->nullable();

            $table->unsignedBigInteger('uploaded_by');
            $table->unsignedBigInteger('status_updated_by')->nullable();
            $table->timestamp('status_updated_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camp_travel_bills');
    }
};
