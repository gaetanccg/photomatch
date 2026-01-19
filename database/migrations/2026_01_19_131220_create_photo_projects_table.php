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
        Schema::create('photo_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('project_type', ['event', 'product', 'real_estate', 'corporate', 'portrait', 'other'])->default('other');
            $table->date('event_date')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->string('location');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();
            $table->integer('estimated_duration')->nullable()->comment('durée en heures');
            $table->enum('status', ['draft', 'published', 'in_progress', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_projects');
    }
};
