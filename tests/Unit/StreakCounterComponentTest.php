<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StreakCounterComponentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test singular/plural grammar for day/days text
     */
    public function test_proper_grammar_handling_for_singular_plural_days()
    {
        // Test cases: [streak, expected_text]
        $testCases = [
            [0, 'days'],   // 0 days (plural)
            [1, 'day'],    // 1 day (singular) 
            [2, 'days'],   // 2 days (plural)
            [5, 'days'],   // 5 days (plural)
            [10, 'days'],  // 10 days (plural)
            [30, 'days'],  // 30 days (plural)
        ];

        foreach ($testCases as [$streak, $expectedGrammar]) {
            $component = $this->component('ui.streak-counter', [
                'currentStreak' => $streak,
                'longestStreak' => 0,
                'stateClasses' => [
                    'background' => 'bg-white',
                    'icon' => 'text-gray-400',
                    'showIcon' => false,
                    'opacity' => 'opacity-70',
                    'border' => 'border-gray-300'
                ],
                'message' => '',
            ]);

            $component->assertSee("{$expectedGrammar} in a row");
        }
    }

    /**
     * Test longest streak grammar uses Laravel's Str::plural helper
     */
    public function test_longest_streak_grammar_uses_laravel_plural_helper()
    {
        // Test cases: [longestStreak, expected_text]
        $testCases = [
            [1, '1 day'],    // 1 day (singular)
            [2, '2 days'],   // 2 days (plural)
            [10, '10 days'], // 10 days (plural)
            [30, '30 days'], // 30 days (plural)
        ];

        foreach ($testCases as [$longestStreak, $expectedText]) {
            $component = $this->component('ui.streak-counter', [
                'currentStreak' => 5,
                'longestStreak' => $longestStreak,
                'stateClasses' => [
                    'background' => 'bg-white',
                    'icon' => 'text-gray-400',
                    'showIcon' => false,
                    'opacity' => 'opacity-70',
                    'border' => 'border-gray-300'
                ],
                'message' => '',
            ]);

            $component->assertSee($expectedText);
        }
    }

    /**
     * Test component displays message when provided
     */
    public function test_component_displays_message_when_provided()
    {
        $testMessage = 'Great start! Keep it going!';
        
        $component = $this->component('ui.streak-counter', [
            'currentStreak' => 1,
            'longestStreak' => 0,
            'stateClasses' => [
                'background' => 'bg-white',
                'icon' => 'text-gray-400',
                'showIcon' => false,
                'opacity' => 'opacity-70',
                'border' => 'border-gray-300'
            ],
            'message' => $testMessage,
        ]);

        $component->assertSee($testMessage);
    }

    /**
     * Test component hides message when not provided
     */
    public function test_component_hides_message_when_not_provided()
    {
        $component = $this->component('ui.streak-counter', [
            'currentStreak' => 1,
            'longestStreak' => 0,
            'stateClasses' => [
                'background' => 'bg-white',
                'icon' => 'text-gray-400',
                'showIcon' => false,
                'opacity' => 'opacity-70',
                'border' => 'border-gray-300'
            ],
            'message' => '',
        ]);

        // Should not contain the message wrapper div when no message
        $component->assertDontSee('<div class="text-center mt-2">', false);
    }

    /**
     * Test component applies state classes correctly
     */
    public function test_component_applies_state_classes_correctly()
    {
        $stateClasses = [
            'background' => 'bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-md',
            'icon' => 'text-orange-200',
            'showIcon' => true,
            'opacity' => 'opacity-90',
            'border' => 'border-white/20'
        ];

        $component = $this->component('ui.streak-counter', [
            'currentStreak' => 5,
            'longestStreak' => 10,
            'stateClasses' => $stateClasses,
            'message' => 'Warning message',
        ]);

        // Should contain the warning background classes
        $component->assertSee('from-orange-500');
        $component->assertSee('to-orange-600');
        
        // Should show the fire icon when showIcon is true
        $component->assertSee('<svg', false);
        
        // Should apply opacity class to secondary text
        $component->assertSee('opacity-90');
    }

    /**
     * Test component hides icon when showIcon is false
     */
    public function test_component_hides_icon_when_show_icon_is_false()
    {
        $stateClasses = [
            'background' => 'bg-white',
            'icon' => 'text-gray-400',
            'showIcon' => false,
            'opacity' => 'opacity-70',
            'border' => 'border-gray-300'
        ];

        $component = $this->component('ui.streak-counter', [
            'currentStreak' => 0,
            'longestStreak' => 0,
            'stateClasses' => $stateClasses,
            'message' => 'Start your reading journey today!',
        ]);

        // Should not contain the fire icon SVG
        $component->assertDontSee('<svg', false);
    }

    /**
     * Test component size variations work correctly
     */
    public function test_component_size_variations()
    {
        $sizes = ['small', 'default', 'large'];
        
        foreach ($sizes as $size) {
            $component = $this->component('ui.streak-counter', [
                'currentStreak' => 5,
                'longestStreak' => 10,
                'size' => $size,
                'stateClasses' => [
                    'background' => 'bg-white',
                    'icon' => 'text-gray-400',
                    'showIcon' => true,
                    'opacity' => 'opacity-70',
                    'border' => 'border-gray-300'
                ],
                'message' => 'Test message',
            ]);

            // Component should render without errors for each size
            $component->assertSee('Current Streak');
            $component->assertSee('5');
        }
    }
}