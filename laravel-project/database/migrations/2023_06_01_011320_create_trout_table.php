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
        Schema::create('trout', function (Blueprint $table) {
            $table->increments('trout_id');
            $table->string('trout_detail',100)->nullable();
            $table->foreignId('life_id')->constrained('life');
            $table->integer('seqno');
            $table->integer('point')->default(0);
            $table->string('color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trout');
    }
};
