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

    Schema::create('activity_student', function (Blueprint $table) use ($connection) {
      if($connection === 'registrar_sqlite') {
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      }

      $table->foreignUuid('activity_id')->constrained('activities')->cascadeOnDelete();
      $table->string('student_no');
      $table->foreign('student_no')->references('student_no')->on('students')->cascadeOnDelete();
      $table->primary(['activity_id', 'student_no']);
      $table->float('score', 1)->nullable();
      $table->string('remarks')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('activity_student');
  }
};
