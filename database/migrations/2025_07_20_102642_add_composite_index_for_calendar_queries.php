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
            // Note: We're checking if the index exists first to avoid errors if it already exists
            if (!Schema::hasIndex('reading_logs', 'idx_user_date_read_calendar')) {
                $table->index(['user_id', 'date_read'], 'idx_user_date_read_calendar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reading_logs', function (Blueprint $table) {
            // Drop the index if it exists
            if (Schema::hasIndex('reading_logs', 'idx_user_date_read_calendar')) {
                $table->dropIndex('idx_user_date_read_calendar');
            }
        });
    }
};
