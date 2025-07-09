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
        Schema::create('motorcycle_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motorcycle_id')->constrained('motorcycles')->cascadeOnDelete();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('rating')->unsigned()->comment('Rating 1-5 bintang');
            $table->text('comment')->nullable()->comment('Komentar review dari penyewa');
            $table->softDeletes();
            $table->timestamps();

            // Index untuk optimasi query
            $table->index(['motorcycle_id', 'rating']);
            $table->index(['user_id']);
            $table->unique(['transaction_id'], 'unique_transaction_review');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorcycle_reviews');
    }
};
