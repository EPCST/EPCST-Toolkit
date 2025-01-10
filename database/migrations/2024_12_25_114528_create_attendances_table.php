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

    Schema::create('attendances', function (Blueprint $table) use ($connection) {
      if($connection === 'registrar_sqlite') {
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      }

      $table->uuid('id')->primary();
      $table->string('period');
      $table->integer('academic_year_id');
      $table->date('date');
      $table->float('hours', 1)->comment('session length');
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
