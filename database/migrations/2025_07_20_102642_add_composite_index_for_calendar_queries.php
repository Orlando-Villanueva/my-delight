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
        Schema::table('reading_logs', function (Blueprint $table) {
            // Add composite index for calendar queries (user_id, date_read)
            $table->index(['user_id', 'date_read'], 'idx_user_date_read_calendar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reading_logs', function (Blueprint $table) {
            // Drop the composite index
            $table->dropIndex('idx_user_date_read_calendar');
        });
    }
};
