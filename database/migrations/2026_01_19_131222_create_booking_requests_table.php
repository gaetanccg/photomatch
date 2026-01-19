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
        Schema::create('booking_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('photo_projects')->onDelete('cascade');
            $table->foreignId('photographer_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'declined', 'cancelled'])->default('pending');
            $table->text('client_message')->nullable();
            $table->text('photographer_response')->nullable();
            $table->decimal('proposed_price', 10, 2)->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_requests');
    }
};
