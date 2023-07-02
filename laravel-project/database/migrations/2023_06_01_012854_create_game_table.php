<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game', function (Blueprint $table) {
            $table->bigIncrements("game_id");
            $table->string('game_status');
            $table->foreignId('user_id')->unsigned()->constrained('user', 'user_id');
            $table->integer('user_2')->nullable();  // NULLを許容する場合
            $table->integer('user_3')->nullable();  // NULLを許容する場合
            $table->integer('user_4')->nullable();  // NULLを許容する場合
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game');
    }
};
