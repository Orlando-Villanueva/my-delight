@extends('layouts.app')

@section('title', 'Streak Card States Demo')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                Streak Card States Demo
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                This page demonstrates all possible visual states of the streak counter component, 
                including different streak values, time contexts, and messaging variations.
            </p>
            <div class="mt-4 flex justify-center">
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                    ← Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Demo Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($streakCards as $index => $card)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-3 border border-gray-200 dark:border-gray-700">
                    
                    <!-- Card Info Header -->
                    <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2">
                            {{ $card['title'] }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            {{ $card['description'] }}
                        </p>
                        
                        <!-- State Info -->
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="px-2 py-1 rounded-full 
                                @if($card['state'] === 'inactive') bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                @elseif($card['state'] === 'active') bg-blue-200 text-blue-800 dark:bg-blue-800 dark:text-blue-200
                                @else bg-orange-200 text-orange-800 dark:bg-orange-800 dark:text-orange-200
                                @endif">
                                {{ ucfirst($card['state']) }} State
                            </span>
                            
                            @if(str_contains($card['description'], 'MILESTONE DAY'))
                                <span class="px-2 py-1 bg-yellow-200 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200 rounded-full font-semibold">
                                    🎉 MILESTONE
                                </span>
                            @elseif(str_contains($card['description'], 'Non-milestone'))
                                <span class="px-2 py-1 bg-indigo-200 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-200 rounded-full">
                                    📈 Building
                                </span>
                            @endif
                            
                            @if($card['hasReadToday'])
                                <span class="px-2 py-1 bg-green-200 text-green-800 dark:bg-green-800 dark:text-green-200 rounded-full">
                                    Read Today
                                </span>
                            @endif
                            
                            <span class="px-2 py-1 bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-full">
                                {{ $card['timeContext'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Actual Streak Counter Component -->
                    <div class="mb-4">
                        <x-ui.streak-counter
                            :currentStreak="$card['currentStreak']"
                            :longestStreak="$card['longestStreak']"
                            :stateClasses="$card['stateClasses']"
                            :message="$card['message']"
                            size="small"
                            class="h-auto" />
                    </div>

                    <!-- Technical Details -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                        <h4 class="font-semibold text-sm text-gray-900 dark:text-white mb-2">Technical Details:</h4>
                        <div class="space-y-1 text-xs text-gray-600 dark:text-gray-400">
                            <div><strong>Current:</strong> {{ $card['currentStreak'] }} {{ $card['currentStreak'] === 1 ? 'day' : 'days' }}</div>
                            <div><strong>Longest:</strong> {{ $card['longestStreak'] }} {{ Str::plural('day', $card['longestStreak']) }}</div>
                            <div><strong>State:</strong> {{ $card['state'] }}</div>
                            <div><strong>Read Today:</strong> {{ $card['hasReadToday'] ? 'Yes' : 'No' }}</div>
                        </div>
                        
                        <!-- Message Preview -->
                        @if($card['message'])
                            <div class="mt-3">
                                <strong class="text-xs text-gray-900 dark:text-white">Message:</strong>
                                <p class="text-xs text-gray-600 dark:text-gray-400 italic mt-1">
                                    "{{ $card['message'] }}"
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Marketing Poster Section -->
        <div class="mt-16 mb-12 -mx-4 sm:mx-0">
            <div class="text-center mb-6 px-4 sm:px-0">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Marketing Showcase
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    The most distinctive and visually compelling streak states for marketing materials and feature demonstrations.
                </p>
            </div>

            <div class="bg-gradient-to-br from-blue-600 to-indigo-800 shadow-2xl p-4 sm:p-8 lg:p-12">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-6">
                    
                    <!-- Inactive State - New User -->
                    <div class="bg-white/95 backdrop-blur-sm rounded-xl p-3 sm:p-5 shadow-lg">
                        <div class="text-center mb-3">
                            <span class="inline-block px-2 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">
                                New User Journey
                            </span>
                        </div>
                        @php
                            $newUserCard = collect($streakCards)->first(fn($card) => $card['currentStreak'] === 0 && str_contains($card['description'], 'New user'));
                        @endphp
                        @if($newUserCard)
                            <x-ui.streak-counter
                                :currentStreak="$newUserCard['currentStreak']"
                                :longestStreak="$newUserCard['longestStreak']"
                                :stateClasses="$newUserCard['stateClasses']"
                                :message="$newUserCard['message']"
                                size="small"
                                class="!h-auto" />
                        @endif
                    </div>

                    <!-- Active State - Building Habit -->
                    <div class="bg-white/95 backdrop-blur-sm rounded-xl p-3 sm:p-5 shadow-lg">
                        <div class="text-center mb-3">
                            <span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                🎯 First Day
                            </span>
                        </div>
                        @php
                            $buildingCard = collect($streakCards)->first(fn($card) => $card['currentStreak'] === 1 && $card['state'] === 'active');
                        @endphp
                        @if($buildingCard)
                            <x-ui.streak-counter
                                :currentStreak="$buildingCard['currentStreak']"
                                :longestStreak="$buildingCard['longestStreak']"
                                :stateClasses="$buildingCard['stateClasses']"
message="Great start! Keep it going!"
                                size="small"
                                class="!h-auto" />
                        @endif
                    </div>

                    <!-- Active State - 7 Day Milestone -->
                    <div class="bg-white/95 backdrop-blur-sm rounded-xl p-3 sm:p-5 shadow-lg">
                        <div class="text-center mb-3">
                            <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                                🎉 First Milestone
                            </span>
                        </div>
                        @php
                            $milestoneCard = collect($streakCards)->first(fn($card) => $card['currentStreak'] === 7);
                        @endphp
                        @if($milestoneCard)
                            <x-ui.streak-counter
                                :currentStreak="$milestoneCard['currentStreak']"
                                :longestStreak="$milestoneCard['longestStreak']"
                                :stateClasses="$milestoneCard['stateClasses']"
message="One full week of reading!"
                                size="small"
                                class="!h-auto" />
                        @endif
                    </div>

                    <!-- Warning State - Risk of Losing Streak -->
                    <div class="bg-white/95 backdrop-blur-sm rounded-xl p-3 sm:p-5 shadow-lg">
                        <div class="text-center mb-3">
                            <span class="inline-block px-2 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">
                                ⚠️ Streak at Risk
                            </span>
                        </div>
                        @php
                            $warningCard = collect($streakCards)->first(fn($card) => $card['state'] === 'warning' && $card['currentStreak'] > 10);
                        @endphp
                        @if($warningCard)
                            <x-ui.streak-counter
                                :currentStreak="$warningCard['currentStreak']"
                                :longestStreak="$warningCard['longestStreak']"
                                :stateClasses="$warningCard['stateClasses']"
message="Don't break your streak! Read today!"
                                size="small"
                                class="!h-auto" />
                        @endif
                    </div>

                    <!-- Active State - Month Milestone -->
                    <div class="bg-white/95 backdrop-blur-sm rounded-xl p-3 sm:p-5 shadow-lg">
                        <div class="text-center mb-3">
                            <span class="inline-block px-2 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                                📅 Month Strong
                            </span>
                        </div>
                        @php
                            $monthCard = collect($streakCards)->first(fn($card) => $card['currentStreak'] === 30);
                        @endphp
                        @if($monthCard)
                            <x-ui.streak-counter
                                :currentStreak="$monthCard['currentStreak']"
                                :longestStreak="$monthCard['longestStreak']"
                                :stateClasses="$monthCard['stateClasses']"
message="One full month of reading!"
                                size="small"
                                class="!h-auto" />
                        @endif
                    </div>

                    <!-- Active State - Long Streak -->
                    <div class="bg-white/95 backdrop-blur-sm rounded-xl p-3 sm:p-5 shadow-lg">
                        <div class="text-center mb-3">
                            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                🔥 Power User
                            </span>
                        </div>
                        @php
                            $powerUserCard = collect($streakCards)->first(fn($card) => $card['currentStreak'] === 90 && $card['state'] === 'active');
                        @endphp
                        @if($powerUserCard)
                            <x-ui.streak-counter
                                :currentStreak="$powerUserCard['currentStreak']"
                                :longestStreak="$powerUserCard['longestStreak']"
                                :stateClasses="$powerUserCard['stateClasses']"
message="Building on three months of reading!"
                                size="small"
                                class="!h-auto" />
                        @endif
                    </div>
                </div>

                <!-- Feature Highlights -->
                <div class="mt-8 text-center">
                    <div class="flex flex-wrap justify-center gap-6 text-white/90">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-white/60 rounded-full"></div>
                            <span class="text-sm">Visual State Changes</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-white/60 rounded-full"></div>
                            <span class="text-sm">Motivational Messaging</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-white/60 rounded-full"></div>
                            <span class="text-sm">Milestone Celebrations</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-white/60 rounded-full"></div>
                            <span class="text-sm">Smart Reminders</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="mt-12 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">State Legend</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Inactive State -->
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center">
                        <span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span>
                        Inactive State
                    </h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>• Current streak = 0 days</li>
                        <li>• Neutral gray background</li>
                        <li>• No fire icon</li>
                        <li>• Encouraging start messages</li>
                        <li>• Different messages for users with/without history</li>
                    </ul>
                </div>

                <!-- Active State -->
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center">
                        <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                        Active State
                    </h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>• Current streak > 0 days</li>
                        <li>• Blue gradient background</li>
                        <li>• Fire icon displayed</li>
                        <li>• Contextual messages by range:</li>
                        <li class="ml-4">- 1 day: First day encouragement</li>
                        <li class="ml-4">- 2-6 days: Building habit</li>
                        <li class="ml-4">- 7-13 days: One week milestone</li>
                        <li class="ml-4">- 14-29 days: Two weeks milestone</li>
                        <li class="ml-4">- 30-59 days: One month milestone</li>
                        <li class="ml-4">- 60-89 days: Two months milestone</li>
                        <li class="ml-4">- 90-119 days: Three months milestone</li>
                        <li class="ml-4">- 120-149 days: Four months milestone</li>
                        <li class="ml-4">- 150-179 days: Five months milestone</li>
                        <li class="ml-4">- 180-364 days: Six months milestone</li>
                        <li class="ml-4">- 365+ days: One year milestone</li>
                    </ul>
                </div>

                <!-- Warning State -->
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center">
                        <span class="w-3 h-3 bg-orange-500 rounded-full mr-2"></span>
                        Warning State
                    </h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>• Current streak > 0 days</li>
                        <li>• Has NOT read today</li>
                        <li>• Time is past 6 PM</li>
                        <li>• Orange gradient background</li>
                        <li>• Orange-tinted fire icon</li>
                        <li>• Urgent but encouraging messages</li>
                        <li>• Messages include streak number</li>
                    </ul>
                </div>
            </div>

            <!-- Message Rotation Info -->
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Message Rotation System</h4>
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    Messages rotate daily based on a consistent hash to prevent user desensitization. 
                    The same message will appear for the same user on the same day, but different messages 
                    will appear on different days to maintain engagement.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                This demo page is only available in local development environment.
            </p>
        </div>
    </div>
</div>
@endsection