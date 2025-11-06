<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin@system.in',
            'password' => Hash::make('Admin@hms123'),
            'phone' => '9876543210',
            'is_active' => true,
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('admins');
    }
};