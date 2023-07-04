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
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements("user_id");
            $table->foreignId('img_id')->unsigned()->constrained('img', 'img_id');
            $table->string('user_mail',100);
            $table->string('user_name',50);
            $table->string('password');
            $table->integer('life_id')->nullable();
            $table->date('birth')->nullable();
            $table->string('blood_type',10)->nullable();
            $table->integer('height')->nullable();
            $table->string('hobby',100)->nullable();
            $table->string('episode1',100)->nullable();
            $table->string('episode2',100)->nullable();
            $table->string('episode3',100)->nullable();
            $table->string('episode4',100)->nullable();
            $table->string('episode5',100)->nullable();
            $table->rememberToken();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
