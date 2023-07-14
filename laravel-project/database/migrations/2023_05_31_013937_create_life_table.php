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
            $table->bigIncrements("life_id");
            $table->string('life_name',50);
            $table->string('life_detail',100);
            $table->string('message',50);
            $table->foreignId('user_id')->unsigned()->constrained('user', 'user_id');
            $table->foreignId('img_id')->unsigned()->nullable()->constrained('img', 'img_id');
            $table->integer('good')->default(0);
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
