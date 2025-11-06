<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->string('room_type');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->integer('floor');
            $table->integer('capacity')->default(1);
            $table->integer('occupied')->default(0);
            $table->decimal('price_per_day', 8, 2)->default(0);
            $table->text('facilities')->nullable();
            $table->enum('status', ['available', 'occupied', 'maintenance', 'cleaning'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};