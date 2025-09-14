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
        Schema::create('book_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('book_id')->unsigned();
            $table->string('book_name', 50);
            $table->tinyInteger('total_chapters')->unsigned();
            $table->json('chapters_read')->default('[]');
            $table->decimal('completion_percent', 5, 2)->default(0.00);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();

            // Indexes for performance
            $table->unique(['user_id', 'book_id'], 'unique_user_book');
            $table->index(['user_id', 'completion_percent'], 'idx_user_completion');
            $table->index(['user_id', 'is_completed'], 'idx_user_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_progress');
    }
};
