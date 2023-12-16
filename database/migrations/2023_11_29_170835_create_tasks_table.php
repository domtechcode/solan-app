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
    Schema::create('tasks', function (Blueprint $table) {
      $table->increments('id');
      $table->string('text');
      $table->integer('duration');
      $table->float('progress');
      $table->dateTime('start_date');
      $table->integer('parent');
      $table->integer('sortorder')->default(0);
      $table->integer('type')->default(1);
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tasks');
  }
};
