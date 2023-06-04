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
            $table->increments('user_id');
            $table->foreignId('img_id')->nullable()->constrained('img');
            $table->string('user_mail',100)->primary();
            $table->string('user_name',50);
            $table->string('password');
            $table->foreignId('life_id')->nullable()->constrained('life');
            $table->date('birth')->nullable();
            $table->string('blood_type',10)->nullable();
            $table->string('hobby',100)->nullable();
            $table->string('episode1',100)->nullable();
            $table->string('episode2',100)->nullable();
            $table->string('episode3',100)->nullable();
            $table->string('episode4',100)->nullable();
            $table->string('episode5',100)->nullable();
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at')->nullable();
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
