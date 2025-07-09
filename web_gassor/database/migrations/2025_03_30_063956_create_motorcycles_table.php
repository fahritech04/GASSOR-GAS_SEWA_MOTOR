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
        Schema::create('motorcycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users');
            $table->foreignId('motorbike_rental_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('vehicle_number_plate');
            $table->boolean('stnk')->default(false);
            $table->json('stnk_images')->nullable();
            $table->integer('price_per_day');
            $table->integer('stock')->default(1);
            $table->integer('available_stock')->default(1);
            $table->boolean('has_gps')->default(false);
            $table->time('start_rent_hour')->default('08:00');
            $table->time('end_rent_hour')->default('20:00');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorcycles');
    }
};
