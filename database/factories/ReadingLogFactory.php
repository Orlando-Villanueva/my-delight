<?php

namespace Database\Factories;

use App\Models\ReadingLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReadingLog>
 */
class ReadingLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bookId = $this->faker->numberBetween(1, 66);
        $chapter = $this->faker->numberBetween(1, 50);
        
        return [
            'user_id' => User::factory(),
            'book_id' => $bookId,
            'chapter' => $chapter,
            'passage_text' => "Genesis {$chapter}", // Simplified for testing
            'date_read' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'notes_text' => $this->faker->optional()->sentence(),
        ];
    }
}
