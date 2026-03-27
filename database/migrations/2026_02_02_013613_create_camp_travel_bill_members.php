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
        Schema::create('camp_travel_bill_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('camp_travel_bill_id');
            $table->enum('role', ['trainer', 'coordinator', 'staff']);
            $table->unsignedBigInteger('member_id');

            $table->timestamps();

            $table->foreign('camp_travel_bill_id')
                ->references('id')
                ->on('camp_travel_bills')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camp_travel_bill_members');
    }
};
