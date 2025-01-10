<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    $connection = DB::connection()->getName();

    Schema::create('activities', function (Blueprint $table) use ($connection) {
      if($connection === 'registrar_sqlite') {
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      }

      $table->uuid('id')->primary();
      $table->foreignUuid('subject_id')->constrained('subjects')->cascadeOnDelete();
      $table->string('period');
      $table->integer('academic_year_id');
      $table->enum('type', ['activity', 'quiz', 'prelim', 'midterm', 'final']);
      $table->string('title');
      $table->text('description')->nullable();
      $table->float('points', 1);
      $table->dateTime('due_date');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('activities');
  }
};
