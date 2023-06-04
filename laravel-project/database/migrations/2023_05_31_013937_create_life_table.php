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
        Schema::create('life', function (Blueprint $table) {
            $table->increments('life_id');
            $table->string('life_name',50);
            $table->string('life_detail',100);
            $table->string('message',50);
            $table->foreignId('user_id')->constrained('user');
            $table->integer('good');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('life');
    }
};
