<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camp_expense_bills', function (Blueprint $table) {
            $table->id();

            $table->integer('school_id');

            $table->unsignedBigInteger('uploaded_by');

            $table->string('bill_type');
            $table->date('training_date');
            $table->decimal('amount', 10, 2);

            $table->string('bill_path')->nullable();
            $table->string('bill_url')->nullable();

            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->text('remarks')->nullable();
            $table->datetime('status_updated_at')->nullable();
            $table->unsignedBigInteger('status_updated_by')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('school_id')
                ->references('scm_id')
                ->on('school_mst')
                ->onDelete('cascade');

            $table->foreign('uploaded_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('status_updated_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camp_expense_bills');
    }
};
