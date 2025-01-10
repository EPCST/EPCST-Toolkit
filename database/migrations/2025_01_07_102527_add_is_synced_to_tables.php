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
      $tables = ['activities', 'activity_student', 'attendance_student_subject', 'attendances', 'student_subject', 'students', 'users', 'subjects'];

      foreach($tables as $table) {
        Schema::table($table, function (Blueprint $table) {
            $table->boolean('is_synced')->default(false);
        });
      }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
