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
            // Drop the old constraint that prevented multiple chapters per day from same book
            $table->dropUnique('unique_user_book_chapter_date');

            // Add new constraint that only prevents exact duplicate entries
            $table->unique(['user_id', 'book_id', 'chapter', 'date_read'], 'unique_user_exact_reading');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reading_logs', function (Blueprint $table) {
            // Restore the original constraint
            $table->dropUnique('unique_user_exact_reading');
            $table->unique(['user_id', 'book_id', 'chapter', 'date_read'], 'unique_user_book_chapter_date');
        });
    }
};
