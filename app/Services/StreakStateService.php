<?php

namespace App\Services;

use Carbon\Carbon;

class StreakStateService
{
    /**
     * Message arrays for different streak ranges and states
     */
    private array $inactiveMessages = [
        'default' => [
            'Start your reading journey today!',
            'Begin building your streak!',
            'Take the first step in your reading habit!',
            'Your Bible reading adventure starts now!'
        ],
        'withHistory' => [
            'You\'ve done it before, you can do it again!',
            'Ready to rebuild your reading habit?',
            'Time to start a new streak!',
            'Your comeback story starts today!'
        ]
    ];

    private array $milestoneMessages = [
        7 => [
            'One full week of reading!',
            'You\'ve completed your first week!',
            'Seven days of dedication achieved!',
            'Your first weekly milestone reached!'
        ],
        14 => [
            'Two full weeks of reading!',
            'You\'ve reached the two-week milestone!',
            'Fourteen days of consistent reading!',
            'Your two-week achievement unlocked!'
        ],
        21 => [
            'Three full weeks of reading!',
            'You\'ve reached the three-week milestone!',
            'Twenty-one days of dedication achieved!',
            'Your third weekly milestone reached!'
        ],
        30 => [
            'One full month of reading!',
            'You\'ve reached your first month!',
            'Thirty days of dedication achieved!',
            'Your monthly milestone reached!'
        ],
        60 => [
            'Two full months of reading!',
            'You\'ve reached the two-month milestone!',
            'Sixty days of incredible commitment!',
            'Your second month achievement unlocked!'
        ],
        90 => [
            'Three full months of reading!',
            'You\'ve reached the three-month milestone!',
            'Ninety days of unwavering dedication!',
            'Your quarterly achievement unlocked!'
        ],
        120 => [
            'Four full months of reading!',
            'You\'ve reached the four-month milestone!',
            'Your fourth month achievement unlocked!',
            'One hundred twenty days of commitment!'
        ],
        150 => [
            'Five full months of reading!',
            'You\'ve reached the five-month milestone!',
            'Your fifth month achievement unlocked!',
            'One hundred fifty days of dedication!'
        ],
        180 => [
            'Six full months of reading!',
            'You\'ve reached the half-year milestone!',
            'Your six-month achievement unlocked!',
            'Half a year of incredible dedication!'
        ],
        210 => [
            'Seven full months of reading!',
            'You\'ve reached the seven-month milestone!',
            'Your seventh month achievement unlocked!',
            'Seven months of incredible dedication!'
        ],
        240 => [
            'Eight full months of reading!',
            'You\'ve reached the eight-month milestone!',
            'Your eighth month achievement unlocked!',
            'Eight months of incredible dedication!'
        ],
        270 => [
            'Nine full months of reading!',
            'You\'ve reached the nine-month milestone!',
            'Your three-quarter year achievement unlocked!',
            'Nine months of incredible dedication!'
        ],
        300 => [
            'Ten full months of reading!',
            'You\'ve reached the ten-month milestone!',
            'Your tenth month achievement unlocked!',
            'Ten months of incredible dedication!'
        ],
        330 => [
            'Eleven full months of reading!',
            'You\'ve reached the eleven-month milestone!',
            'Your eleventh month achievement unlocked!',
            'Eleven months of incredible dedication!'
        ],
        365 => [
            'One full year of reading achieved!',
            'You\'ve reached the legendary one-year milestone!',
            'Your yearly achievement unlocked!',
            'Three hundred sixty-five days of commitment!'
        ]
    ];

    private array $activeMessages = [
        1 => [
            'Great start! Keep it going!',
            'You\'re building momentum!',
            'One day down, many more to go!',
            'Perfect beginning to your journey!'
        ],
        '2-6' => [
            'You\'re building a great habit!',
            'Keep the momentum going!',
            'Your consistency is showing!',
            'Building something beautiful!'
        ],
        '7-13' => [
            'One week down, heading for two!',
            'Past one week, approaching two!',
            'Building toward your two-week milestone!',
            'One week achieved, keep the momentum!'
        ],
        '15-20' => [
            'Two weeks down, approaching three weeks!',
            'Past two weeks, heading for twenty-one days!',
            'Building toward your three-week milestone!',
            'Two weeks achieved, three weeks within reach!'
        ],
        '22-29' => [
            'Three weeks down, approaching your first month!',
            'Past three weeks, heading for thirty days!',
            'Building toward your monthly milestone!',
            'Three weeks achieved, one month within reach!'
        ],
        '31-59' => [
            'One month down, approaching two months!',
            'Past your first month, heading for sixty days!',
            'Building toward your two-month milestone!',
            'One month achieved, two months within reach!'
        ],
        '61-89' => [
            'Two months down, approaching three months!',
            'Past two months, heading for ninety days!',
            'Building toward your quarterly milestone!',
            'Two months achieved, three months within reach!'
        ],
        '91-119' => [
            'Three months down, approaching four months!',
            'Past your quarter year, heading for four months!',
            'Building toward your four-month milestone!',
            'Three months achieved, four months within reach!'
        ],
        '121-149' => [
            'Four months down, approaching five months!',
            'Past four months, heading for five months!',
            'Building toward your five-month milestone!',
            'Four months achieved, five months within reach!'
        ],
        '151-179' => [
            'Five months down, approaching six months!',
            'Past five months, heading for half a year!',
            'Building toward your six-month milestone!',
            'Five months achieved, six months within reach!'
        ],
        '181-209' => [
            'Six months down, approaching seven months!',
            'Past half a year, heading for seven months!',
            'Building toward your seven-month milestone!',
            'Six months achieved, seven months within reach!'
        ],
        '211-239' => [
            'Seven months down, approaching eight months!',
            'Past seven months, heading for eight months!',
            'Building toward your eight-month milestone!',
            'Seven months achieved, eight months within reach!'
        ],
        '241-269' => [
            'Eight months down, approaching nine months!',
            'Past eight months, heading for nine months!',
            'Building toward your nine-month milestone!',
            'Eight months achieved, nine months within reach!'
        ],
        '271-299' => [
            'Nine months down, approaching ten months!',
            'Past nine months, heading for ten months!',
            'Building toward your ten-month milestone!',
            'Nine months achieved, ten months within reach!'
        ],
        '301-329' => [
            'Ten months down, approaching eleven months!',
            'Past ten months, heading for eleven months!',
            'Building toward your eleven-month milestone!',
            'Ten months achieved, eleven months within reach!'
        ],
        '331-364' => [
            'Eleven months down, approaching one year!',
            'Past eleven months, heading for your first year!',
            'Building toward your legendary yearly milestone!',
            'Eleven months achieved, one year within reach!'
        ],
        '365+' => [
            'Building on a full year of reading!',
            'Your year-long habit is extraordinary!',
            'Keep your legendary streak alive!',
            'Over a year of incredible dedication!'
        ]
    ];

    private array $warningMessages = [
        'Don\'t break your {streak}-day streak! Read today!',
        'Your {streak}-day streak needs you!',
        'Keep your {streak}-day momentum going - read today!',
        'Don\'t let your {streak}-day progress slip away!',
        'Your {streak}-day streak is counting on you!'
    ];

    private array $acknowledgeMessages = [
        'Well done! You\'ve read today!',
        'Great job staying consistent!',
        'Your streak is safe for today!',
        'Another day of progress!'
    ];

    /**
     * Determine the visual state of the streak counter component
     * 
     * @param int $currentStreak
     * @param bool $hasReadToday
     * @param Carbon|null $currentTime
     * @return string
     */
    public function determineStreakState(int $currentStreak, bool $hasReadToday, ?Carbon $currentTime = null): string
    {
        $currentTime = $currentTime ?? now();

        // Inactive state: current streak is 0
        if ($currentStreak === 0) {
            return 'inactive';
        }

        // Warning state: has streak but hasn't read today and it's past warning time (6 PM)
        if ($currentStreak > 0 && !$hasReadToday && $currentTime->hour >= 18) {
            return 'warning';
        }

        // Active state: has streak and either read today or not past warning time
        return 'active';
    }

    /**
     * Get the CSS classes for a given streak state
     * 
     * @param string $state
     * @return array
     */
    public function getStateClasses(string $state): array
    {
        $stateClasses = [
            'inactive' => 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-[#D1D7E0] dark:border-gray-700 shadow-lg',
            'active' => 'bg-gradient-to-br from-[#3366CC] to-[#2952A3] text-white shadow-lg',
            'warning' => 'bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-lg'
        ];

        $iconColors = [
            'inactive' => 'text-gray-400 dark:text-gray-500',
            'active' => 'text-accent-500',
            'warning' => 'text-orange-200'
        ];

        return [
            'background' => $stateClasses[$state] ?? $stateClasses['active'],
            'icon' => $iconColors[$state] ?? $iconColors['active'],
            'showIcon' => $state !== 'inactive',
            'opacity' => $state === 'inactive' ? 'opacity-70' : 'opacity-90',
            'border' => $state === 'inactive' ? 'border-[#D1D7E0] dark:border-gray-600' : 'border-white/20'
        ];
    }

    /**
     * Select appropriate message based on current streak, state, and longest streak
     * 
     * @param int $currentStreak
     * @param string $state
     * @param int $longestStreak
     * @param bool $hasReadToday
     * @return string
     */
    public function selectMessage(int $currentStreak, string $state, int $longestStreak = 0, bool $hasReadToday = false): string
    {
        switch ($state) {
            case 'inactive':
                return $this->selectInactiveMessage($longestStreak);
            
            case 'warning':
                return $this->selectWarningMessage($currentStreak);
            
            case 'active':
                // If user has read today and has a streak > 2, show acknowledgment message occasionally
                // Avoid acknowledgment for streaks 1-2 since they're building from 0, not maintaining an existing streak
                if ($hasReadToday && $currentStreak > 2 && $this->shouldShowAcknowledgment()) {
                    return $this->selectAcknowledgmentMessage();
                }
                return $this->selectActiveMessage($currentStreak);
            
            default:
                return $this->selectActiveMessage($currentStreak);
        }
    }

    /**
     * Select message for inactive state (0 streak)
     * 
     * @param int $longestStreak
     * @return string
     */
    private function selectInactiveMessage(int $longestStreak): string
    {
        $messageType = $longestStreak > 0 ? 'withHistory' : 'default';
        $messages = $this->inactiveMessages[$messageType];
        
        return $this->rotateMessage($messages, 'inactive_' . $messageType);
    }

    /**
     * Select message for active state (1+ streak)
     * 
     * @param int $currentStreak
     * @return string
     */
    private function selectActiveMessage(int $currentStreak): string
    {
        // Check if this is a milestone day first
        if (isset($this->milestoneMessages[$currentStreak])) {
            $messages = $this->milestoneMessages[$currentStreak];
            return $this->rotateMessage($messages, 'milestone_' . $currentStreak);
        }
        
        // Otherwise use regular range-based messages
        $range = $this->getStreakRange($currentStreak);
        $messages = $this->activeMessages[$range];
        
        return $this->rotateMessage($messages, 'active_' . $range);
    }

    /**
     * Select message for warning state
     * 
     * @param int $currentStreak
     * @return string
     */
    private function selectWarningMessage(int $currentStreak): string
    {
        $message = $this->rotateMessage($this->warningMessages, 'warning');
        
        // Replace {streak} placeholder with actual streak value
        return str_replace('{streak}', $currentStreak, $message);
    }

    /**
     * Select acknowledgment message for when user has read today
     * 
     * @return string
     */
    private function selectAcknowledgmentMessage(): string
    {
        return $this->rotateMessage($this->acknowledgeMessages, 'acknowledge');
    }

    /**
     * Determine the streak range for message selection
     * 
     * @param int $streak
     * @return string|int
     */
    private function getStreakRange(int $streak): string|int
    {
        if ($streak === 1) {
            return 1;
        } elseif ($streak >= 2 && $streak <= 6) {
            return '2-6';
        } elseif ($streak >= 7 && $streak <= 13) {
            return '7-13';
        } elseif ($streak >= 15 && $streak <= 20) {
            return '15-20';
        } elseif ($streak >= 22 && $streak <= 29) {
            return '22-29';
        } elseif ($streak >= 31 && $streak <= 59) {
            return '31-59';
        } elseif ($streak >= 61 && $streak <= 89) {
            return '61-89';
        } elseif ($streak >= 91 && $streak <= 119) {
            return '91-119';
        } elseif ($streak >= 121 && $streak <= 149) {
            return '121-149';
        } elseif ($streak >= 151 && $streak <= 179) {
            return '151-179';
        } elseif ($streak >= 181 && $streak <= 209) {
            return '181-209';
        } elseif ($streak >= 211 && $streak <= 239) {
            return '211-239';
        } elseif ($streak >= 241 && $streak <= 269) {
            return '241-269';
        } elseif ($streak >= 271 && $streak <= 299) {
            return '271-299';
        } elseif ($streak >= 301 && $streak <= 329) {
            return '301-329';
        } elseif ($streak >= 331 && $streak <= 364) {
            return '331-364';
        } else {
            return '365+';
        }
    }

    /**
     * Rotate between messages to prevent user desensitization
     * Uses a simple hash-based rotation to ensure consistency per user/day
     * 
     * @param array $messages
     * @param string $context
     * @return string
     */
    private function rotateMessage(array $messages, string $context): string
    {
        // Create a seed based on current date and context to ensure same message per day
        // but different messages across days and contexts
        $dateString = now()->format('Y-m-d');
        $seed = hash('crc32b', $dateString . '_' . $context);
        $index = hexdec(substr($seed, 0, 8)) % count($messages);
        
        return $messages[$index];
    }

    /**
     * Determine if acknowledgment message should be shown (25% chance)
     * 
     * @return bool
     */
    private function shouldShowAcknowledgment(): bool
    {
        // Show acknowledgment message 25% of the time when user has read today
        $dateString = now()->format('Y-m-d');
        $seed = hash('crc32b', $dateString . '_acknowledge_chance');
        return (hexdec(substr($seed, 0, 8)) % 4) === 0;
    }
}
