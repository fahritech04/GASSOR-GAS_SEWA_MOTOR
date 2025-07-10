<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('motorcycle_physical_checks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('motorcycle_id');
            $table->unsignedBigInteger('motorbike_rental_id');
            $table->json('checklist');
            $table->string('video_path');
            $table->timestamps();

            $table->foreign('motorcycle_id')->references('id')->on('motorcycles')->onDelete('cascade');
            $table->foreign('motorbike_rental_id')->references('id')->on('motorbike_rentals')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motorcycle_physical_checks');
    }
};
