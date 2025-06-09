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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['pemilik', 'penyewa']);
            $table->rememberToken();
            $table->timestamps();
            $table->string('username')->unique()->nullable();
            $table->string('profile_image_url')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('phone')->nullable();
            $table->string('ktp_image_url')->nullable();
            $table->string('sim_image_url')->nullable();
            $table->string('ktm_image_url')->nullable();
            $table->boolean('google_blocked')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
