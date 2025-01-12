<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    $connection = DB::connection()->getName();

    Schema::create('student_subject', function (Blueprint $table) use ($connection) {
      if($connection === 'registrar_sqlite') {
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      }

      $table->string('student_no');
      $table->foreign('student_no')->references('student_no')->on('students')->cascadeOnDelete();
      $table->foreignUuid('subject_id')->constrained('subjects')->cascadeOnDelete();
      $table->unsignedInteger('academic_year_id');
      $table->unsignedInteger('section_id');
      $table->enum('status', ['dropped', 'active', 'return'])->default('active');
      $table->date('last_attendance_date')->nullable();
      $table->date('dropped_at')->nullable();
      $table->date('returned_at')->nullable();
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('student_subject');
  }
};
