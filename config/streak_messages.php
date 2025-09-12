<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Streak Message Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all the message arrays used by the StreakStateService
    | for displaying different types of streak encouragement messages to users.
    |
    */

    'inactive' => [
        'default' => [
            'Start your reading journey today!',
            'Begin building your streak!',
            'Take the first step in your reading habit!',
            'Your Bible reading adventure starts now!',
        ],
        'withHistory' => [
            'You\'ve done it before, you can do it again!',
            'Ready to rebuild your reading habit?',
            'Time to start a new streak!',
            'Your comeback story starts today!',
        ],
    ],

    'milestone' => [
        7 => [
            'One full week of reading!',
            'You\'ve completed your first week!',
            'Seven days of dedication achieved!',
            'Your first weekly milestone reached!',
        ],
        14 => [
            'Two full weeks of reading!',
            'You\'ve reached the two-week milestone!',
            'Fourteen days of consistent reading!',
            'Your two-week achievement unlocked!',
        ],
        21 => [
            'Three full weeks of reading!',
            'You\'ve reached the three-week milestone!',
            'Twenty-one days of dedication achieved!',
            'Your third weekly milestone reached!',
        ],
        30 => [
            'One full month of reading!',
            'You\'ve reached your first month!',
            'Thirty days of dedication achieved!',
            'Your monthly milestone reached!',
        ],
        60 => [
            'Two full months of reading!',
            'You\'ve reached the two-month milestone!',
            'Sixty days of incredible commitment!',
            'Your second month achievement unlocked!',
        ],
        90 => [
            'Three full months of reading!',
            'You\'ve reached the three-month milestone!',
            'Ninety days of unwavering dedication!',
            'Your quarterly achievement unlocked!',
        ],
        120 => [
            'Four full months of reading!',
            'You\'ve reached the four-month milestone!',
            'Your fourth month achievement unlocked!',
            'One hundred twenty days of commitment!',
        ],
        150 => [
            'Five full months of reading!',
            'You\'ve reached the five-month milestone!',
            'Your fifth month achievement unlocked!',
            'One hundred fifty days of dedication!',
        ],
        180 => [
            'Six full months of reading!',
            'You\'ve reached the half-year milestone!',
            'Your six-month achievement unlocked!',
            'Half a year of incredible dedication!',
        ],
        210 => [
            'Seven full months of reading!',
            'You\'ve reached the seven-month milestone!',
            'Your seventh month achievement unlocked!',
            'Seven months of incredible dedication!',
        ],
        240 => [
            'Eight full months of reading!',
            'You\'ve reached the eight-month milestone!',
            'Your eighth month achievement unlocked!',
            'Eight months of incredible dedication!',
        ],
        270 => [
            'Nine full months of reading!',
            'You\'ve reached the nine-month milestone!',
            'Your three-quarter year achievement unlocked!',
            'Nine months of incredible dedication!',
        ],
        300 => [
            'Ten full months of reading!',
            'You\'ve reached the ten-month milestone!',
            'Your tenth month achievement unlocked!',
            'Ten months of incredible dedication!',
        ],
        330 => [
            'Eleven full months of reading!',
            'You\'ve reached the eleven-month milestone!',
            'Your eleventh month achievement unlocked!',
            'Eleven months of incredible dedication!',
        ],
        365 => [
            'One full year of reading achieved!',
            'You\'ve reached the legendary one-year milestone!',
            'Your yearly achievement unlocked!',
            'Three hundred sixty-five days of commitment!',
        ],
    ],

    'active' => [
        1 => [
            'Great start! Keep it going!',
            'You\'re building momentum!',
            'One day down, many more to go!',
            'Perfect beginning to your journey!',
        ],
        '2-6' => [
            'You\'re building a great habit!',
            'Keep the momentum going!',
            'Your consistency is showing!',
            'Building something beautiful!',
        ],
        '7-13' => [
            'One week down, heading for two!',
            'Past one week, approaching two!',
            'Building toward your two-week milestone!',
            'One week achieved, keep the momentum!',
        ],
        '15-20' => [
            'Two weeks down, approaching three weeks!',
            'Past two weeks, heading for twenty-one days!',
            'Building toward your three-week milestone!',
            'Two weeks achieved, three weeks within reach!',
        ],
        '22-29' => [
            'Three weeks down, approaching your first month!',
            'Past three weeks, heading for thirty days!',
            'Building toward your monthly milestone!',
            'Three weeks achieved, one month within reach!',
        ],
        '31-59' => [
            'One month down, approaching two months!',
            'Past your first month, heading for sixty days!',
            'Building toward your two-month milestone!',
            'One month achieved, two months within reach!',
        ],
        '61-89' => [
            'Two months down, approaching three months!',
            'Past two months, heading for ninety days!',
            'Building toward your quarterly milestone!',
            'Two months achieved, three months within reach!',
        ],
        '91-119' => [
            'Three months down, approaching four months!',
            'Past your quarter year, heading for four months!',
            'Building toward your four-month milestone!',
            'Three months achieved, four months within reach!',
        ],
        '121-149' => [
            'Four months down, approaching five months!',
            'Past four months, heading for five months!',
            'Building toward your five-month milestone!',
            'Four months achieved, five months within reach!',
        ],
        '151-179' => [
            'Five months down, approaching six months!',
            'Past five months, heading for half a year!',
            'Building toward your six-month milestone!',
            'Five months achieved, six months within reach!',
        ],
        '181-209' => [
            'Six months down, approaching seven months!',
            'Past half a year, heading for seven months!',
            'Building toward your seven-month milestone!',
            'Six months achieved, seven months within reach!',
        ],
        '211-239' => [
            'Seven months down, approaching eight months!',
            'Past seven months, heading for eight months!',
            'Building toward your eight-month milestone!',
            'Seven months achieved, eight months within reach!',
        ],
        '241-269' => [
            'Eight months down, approaching nine months!',
            'Past eight months, heading for nine months!',
            'Building toward your nine-month milestone!',
            'Eight months achieved, nine months within reach!',
        ],
        '271-299' => [
            'Nine months down, approaching ten months!',
            'Past nine months, heading for ten months!',
            'Building toward your ten-month milestone!',
            'Nine months achieved, ten months within reach!',
        ],
        '301-329' => [
            'Ten months down, approaching eleven months!',
            'Past ten months, heading for eleven months!',
            'Building toward your eleven-month milestone!',
            'Ten months achieved, eleven months within reach!',
        ],
        '331-364' => [
            'Eleven months down, approaching one year!',
            'Past eleven months, heading for your first year!',
            'Building toward your legendary yearly milestone!',
            'Eleven months achieved, one year within reach!',
        ],
        '365+' => [
            'Building on a full year of reading!',
            'Your year-long habit is extraordinary!',
            'Keep your legendary streak alive!',
            'Over a year of incredible dedication!',
        ],
    ],

    'warning' => [
        'Don\'t break your {streak}-day streak! Read today!',
        'Your {streak}-day streak needs you!',
        'Keep your {streak}-day momentum going - read today!',
        'Don\'t let your {streak}-day progress slip away!',
        'Your {streak}-day streak is counting on you!',
    ],

    'acknowledge' => [
        'Well done! You\'ve read today!',
        'Great job staying consistent!',
        'Your streak is safe for today!',
        'Another day of progress!',
    ],

];
