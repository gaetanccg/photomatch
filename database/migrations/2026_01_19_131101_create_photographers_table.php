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
        Schema::create('photographers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->integer('experience_years')->default(0);
            $table->string('portfolio_url')->nullable();
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->decimal('daily_rate', 10, 2)->default(0);
            $table->boolean('is_verified')->default(false);
            $table->decimal('rating', 3, 1)->nullable();
            $table->integer('total_missions')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photographers');
    }
};
