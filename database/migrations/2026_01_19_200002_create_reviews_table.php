<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('photographer_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->text('photographer_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            // Un client ne peut laisser qu'un seul avis par booking
            $table->unique('booking_request_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
