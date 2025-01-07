<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('student_subject', function (Blueprint $table) {
      $table->string('student_no');
      $table->foreign('student_no')->references('student_no')->on('students')->cascadeOnDelete();
      $table->unsignedBigInteger('subject_id');
      $table->unsignedInteger('academic_year_id');
      $table->unsignedInteger('section_id');
      $table->enum('status', ['dropped', 'active', 'return'])->default('active');
      $table->date('last_attendance_date')->nullable();
      $table->date('dropped_at')->nullable();
      $table->date('returned_at')->nullable();
      $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnDelete();
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
