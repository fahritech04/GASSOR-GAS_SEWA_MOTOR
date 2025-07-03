<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->time('start_rent_hour')->default('08:00');
            $table->time('end_rent_hour')->default('20:00');
        });
    }

    public function down(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->dropColumn(['start_rent_hour', 'end_rent_hour']);
        });
    }
};
