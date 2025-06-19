<?php

namespace Database\Factories;

use App\Models\BookProgress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookProgress>
 */
class BookProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bookId = $this->faker->numberBetween(1, 66);
        $totalChapters = $this->faker->numberBetween(10, 50);
        $chaptersRead = $this->faker->numberBetween(0, 5);
        $chaptersReadArray = range(1, $chaptersRead);
        $completionPercent = $totalChapters > 0 ? round(($chaptersRead / $totalChapters) * 100, 2) : 0;
        
        return [
            'user_id' => User::factory(),
            'book_id' => $bookId,
            'book_name' => 'Genesis', // Simplified for testing
            'total_chapters' => $totalChapters,
            'chapters_read' => $chaptersReadArray,
            'completion_percent' => $completionPercent,
            'is_completed' => $completionPercent >= 100,
            'last_updated' => now(),
        ];
    }
}
