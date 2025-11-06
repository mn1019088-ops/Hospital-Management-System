<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_id')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('specialization');
            $table->text('qualification');
            $table->integer('experience_years')->default(0);
            $table->text('address')->nullable();
            $table->decimal('consultation_fee', 8, 2)->default(0);
            $table->string('avatar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->time('available_from')->nullable();
            $table->time('available_to')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctors');
    }
};