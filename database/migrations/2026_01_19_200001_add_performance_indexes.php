<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Photographers table indexes
        Schema::table('photographers', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('rating');
            $table->index('is_verified');
            $table->index(['rating', 'is_verified']);
        });

        // Photo projects table indexes
        Schema::table('photo_projects', function (Blueprint $table) {
            $table->index('client_id');
            $table->index('status');
            $table->index('project_type');
            $table->index(['client_id', 'status']);
        });

        // Booking requests table indexes
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->index('project_id');
            $table->index('photographer_id');
            $table->index('status');
            $table->index(['project_id', 'status']);
            $table->index(['photographer_id', 'status']);
        });

        // Availabilities table indexes
        Schema::table('availabilities', function (Blueprint $table) {
            $table->index('photographer_id');
            $table->index('date');
            $table->index(['photographer_id', 'date']);
            $table->index(['photographer_id', 'is_available']);
        });
    }

    public function down(): void
    {
        Schema::table('photographers', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['rating']);
            $table->dropIndex(['is_verified']);
            $table->dropIndex(['rating', 'is_verified']);
        });

        Schema::table('photo_projects', function (Blueprint $table) {
            $table->dropIndex(['client_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['project_type']);
            $table->dropIndex(['client_id', 'status']);
        });

        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropIndex(['project_id']);
            $table->dropIndex(['photographer_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['project_id', 'status']);
            $table->dropIndex(['photographer_id', 'status']);
        });

        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropIndex(['photographer_id']);
            $table->dropIndex(['date']);
            $table->dropIndex(['photographer_id', 'date']);
            $table->dropIndex(['photographer_id', 'is_available']);
        });
    }
};
