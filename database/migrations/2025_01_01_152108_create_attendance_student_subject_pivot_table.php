<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('attendance_student_subject', function (Blueprint $table) {
      $table->id();
      $table->foreignId('attendance_id')->constrained()->cascadeOnDelete();
      $table->foreignId('student_id')->constrained()->cascadeOnDelete();
      $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
      $table->float('hours', 1);
      $table->enum('status', ['present', 'late', 'absent', 'excused']);
      $table->string('remarks')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('attendance_student_subject_pivot');
  }
};
