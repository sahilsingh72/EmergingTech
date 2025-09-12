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
        Schema::table('student_mst', function (Blueprint $table) {
            $table->renameColumn('stu_scmUDISE', 'stu_scm_udise');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('student_mst', function (Blueprint $table) {
            $table->renameColumn('stu_scm_udise', 'stu_scmUDISE');
        });
    }
};
