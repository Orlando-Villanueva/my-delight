<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingLog extends Model
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
        'chapter',
        'passage_text',
        'date_read',
        'notes_text',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_read' => 'date',
            'book_id' => 'integer',
            'chapter' => 'integer',
        ];
    }

    /**
     * Get the user that owns the reading log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include readings for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include readings for a specific date range.
     */
    public function scopeDateRange($query, $startDate, $endDate = null)
    {
        $query->where('date_read', '>=', $startDate);
        
        if ($endDate) {
            $query->where('date_read', '<=', $endDate);
        }
        
        return $query;
    }

    /**
     * Scope a query to only include readings for a specific book.
     */
    public function scopeForBook($query, $bookId)
    {
        return $query->where('book_id', $bookId);
    }

    /**
     * Scope a query to order by date read (most recent first).
     */
    public function scopeRecentFirst($query)
    {
        return $query->orderBy('date_read', 'desc');
    }
} 