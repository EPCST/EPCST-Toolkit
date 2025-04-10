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

    Schema::create('subjects', function (Blueprint $table) use ($connection) {
      if($connection === 'registrar_sqlite') {
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      }

      $table->uuid('id')->primary();
      $table->unsignedInteger('subject_id');
      $table->unsignedInteger('academic_year_id');
      $table->unsignedInteger('section_id');
      $table->unique(['academic_year_id', 'section_id', 'subject_id']);
      $table->string('code')->nullable();
      $table->string('title');
      $table->string('section');
      $table->integer('units_lab')->default(0);
      $table->integer('units_lec')->default(0);
      $table->integer('attendance_threshold')->default(5);
      $table->integer('dropout_threshold')->default(10);
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('subjects');
  }
};
