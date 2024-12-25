<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('attendances', function (Blueprint $table) {
      $table->id();
      $table->string('period');
      $table->integer('academic_year_id');
      $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('attendances');
  }
};
