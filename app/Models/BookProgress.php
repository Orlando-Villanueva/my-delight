<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookProgress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'book_name',
        'total_chapters',
        'chapters_read',
        'completion_percent',
        'is_completed',
        'last_updated',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'chapters_read' => 'array',
            'completion_percent' => 'decimal:2',
            'is_completed' => 'boolean',
            'last_updated' => 'datetime',
            'book_id' => 'integer',
            'total_chapters' => 'integer',
        ];
    }

    /**
     * Get the user that owns the book progress.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include progress for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include completed books.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope a query to only include books in progress.
     */
    public function scopeInProgress($query)
    {
        return $query->where('is_completed', false)
                    ->where('completion_percent', '>', 0);
    }

    /**
     * Scope a query to only include books not started.
     */
    public function scopeNotStarted($query)
    {
        return $query->where('completion_percent', 0);
    }

    /**
     * Add a chapter to the chapters_read array.
     */
    public function addChapter(int $chapter): void
    {
        $chaptersRead = $this->chapters_read ?? [];
        
        if (!in_array($chapter, $chaptersRead)) {
            $chaptersRead[] = $chapter;
            sort($chaptersRead);
            
            $this->chapters_read = $chaptersRead;
            $this->updateCompletionStatus();
        }
    }

    /**
     * Update completion percentage and status based on chapters read.
     */
    public function updateCompletionStatus(): void
    {
        $chaptersReadCount = count($this->chapters_read ?? []);
        $this->completion_percent = $this->total_chapters > 0 
            ? round(($chaptersReadCount / $this->total_chapters) * 100, 2)
            : 0;
        
        $this->is_completed = $this->completion_percent >= 100;
        $this->last_updated = now();
    }

    /**
     * Get the number of chapters read.
     */
    public function getChaptersReadCountAttribute(): int
    {
        return count($this->chapters_read ?? []);
    }

    /**
     * Check if a specific chapter has been read.
     */
    public function hasReadChapter(int $chapter): bool
    {
        return in_array($chapter, $this->chapters_read ?? []);
    }
} 