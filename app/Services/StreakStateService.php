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
        '14-29' => [
            'Two weeks of consistent reading!',
            'Two weeks strong!',
            'You\'ve reached the two-week milestone!',
            'Half a month of commitment achieved!'
        ],
        '30-59' => [
            'Building on your month of reading!',
            'Your monthly habit is growing strong!',
            'Keep your month-long streak going!',
            'Over a month of consistent reading!'
        ],
        '60-89' => [
            'Building on two months of reading!',
            'Your two-month habit is solid!',
            'Keep your multi-month streak alive!',
            'Over two months of dedication!'
        ],
        '90-119' => [
            'Building on three months of reading!',
            'Your quarterly habit is unbreakable!',
            'Keep your three-month streak strong!',
            'Over three months of commitment!'
        ],
        '120-149' => [
            'Building on four months of reading!',
            'Your four-month habit is incredible!',
            'Keep your long streak alive!',
            'Over four months of dedication!'
        ],
        '150-179' => [
            'Building on five months of reading!',
            'Your five-month habit is amazing!',
            'Keep your extended streak going!',
            'Over five months of commitment!'
        ],
        '180-364' => [
            'Building on six months of reading!',
            'Your half-year habit is legendary!',
            'Keep your incredible streak alive!',
            'Over six months of dedication!'
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
        } elseif ($streak >= 14 && $streak <= 29) {
            return '14-29';
        } elseif ($streak >= 30 && $streak <= 59) {
            return '30-59';
        } elseif ($streak >= 60 && $streak <= 89) {
            return '60-89';
        } elseif ($streak >= 90 && $streak <= 119) {
            return '90-119';
        } elseif ($streak >= 120 && $streak <= 149) {
            return '120-149';
        } elseif ($streak >= 150 && $streak <= 179) {
            return '150-179';
        } elseif ($streak >= 180 && $streak <= 364) {
            return '180-364';
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
