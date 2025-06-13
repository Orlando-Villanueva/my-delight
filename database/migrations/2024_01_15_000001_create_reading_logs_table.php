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
        Schema::create('reading_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('book_id')->unsigned();
            $table->smallInteger('chapter')->unsigned();
            $table->string('passage_text', 100);
            $table->date('date_read');
            $table->text('notes_text')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'date_read'], 'idx_user_date');
            $table->index(['user_id', 'book_id', 'chapter'], 'idx_user_book_chapter');
            
            // Prevent duplicate readings on same date
            $table->unique(['user_id', 'book_id', 'chapter', 'date_read'], 'unique_user_book_chapter_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_logs');
    }
}; 