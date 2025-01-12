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
    $connection = DB::connection()->getName();

    Schema::create('attendance_student_subject', function (Blueprint $table) use ($connection) {
      if($connection === 'registrar_sqlite') {
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      }

      $table->uuid('id')->primary();
      $table->foreignUuid('attendance_id')->constrained()->cascadeOnDelete();
      $table->string('student_no');
      $table->foreign('student_no')->references('student_no')->on('students')->cascadeOnDelete();
      $table->foreignUuid('subject_id')->constrained()->cascadeOnDelete();
      $table->unique(['attendance_id', 'student_no', 'subject_id'], 'attendance_student_subject_unique');
      $table->float('hours', 1);
      $table->enum('status', ['present', 'late', 'absent', 'excused']);
      $table->string('remarks')->nullable();
      $table->boolean('return_to_class')->default(false);
      $table->boolean('is_dropped')->default(false);
      $table->timestamp('deleted_at')->nullable();
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
