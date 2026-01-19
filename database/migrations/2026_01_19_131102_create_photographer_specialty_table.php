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
        Schema::create('photographer_specialty', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photographer_id')->constrained()->onDelete('cascade');
            $table->foreignId('specialty_id')->constrained()->onDelete('cascade');
            $table->enum('experience_level', ['beginner', 'intermediate', 'expert'])->default('intermediate');
            $table->timestamps();

            $table->unique(['photographer_id', 'specialty_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photographer_specialty');
    }
};
