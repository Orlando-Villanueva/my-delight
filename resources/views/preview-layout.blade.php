@extends('layouts.authenticated')

@section('page-title', 'Bible Habit Builder Demo')
@section('page-subtitle', 'Experience your personalized Bible reading journey')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-neutral-mid dark:border-gray-700">
            <h1 class="text-2xl font-bold text-neutral-dark dark:text-gray-100 mb-2">
                🎨 Bible Habit Builder Demo
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Welcome to your personal Bible reading journey! This demo shows how your daily reading habits create lasting spiritual growth.
            </p>
        </div>

        <!-- Streak Card (Sample) -->
        <div class="bg-gradient-to-r from-primary to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold mb-1">Current Streak</h2>
                    <div class="flex items-center space-x-3">
                        <span class="text-4xl font-bold">🔥</span>
                        <span class="text-3xl font-bold">12 days</span>
                    </div>
                    <p class="text-blue-100 text-sm mt-1">Longest streak: 28 days</p>
                </div>
                <div class="text-right">
                    <p class="text-blue-100 text-sm">Keep it up!</p>
                    <p class="text-xs text-blue-200">Last reading: Today</p>
                </div>
            </div>
        </div>

        <!-- Sample Calendar Grid -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-neutral-mid dark:border-gray-700">
            <h3 class="text-lg font-semibold text-neutral-dark dark:text-gray-100 mb-4">
                📅 June 2025 Reading Calendar
            </h3>
            <div class="grid grid-cols-7 gap-1 text-center text-xs">
                <!-- Day headers -->
                <div class="p-2 font-medium text-gray-500">S</div>
                <div class="p-2 font-medium text-gray-500">M</div>
                <div class="p-2 font-medium text-gray-500">T</div>
                <div class="p-2 font-medium text-gray-500">W</div>
                <div class="p-2 font-medium text-gray-500">T</div>
                <div class="p-2 font-medium text-gray-500">F</div>
                <div class="p-2 font-medium text-gray-500">S</div>
                
                <!-- June 2025 calendar (June 1st is a Sunday) -->
                @php
                    $completedDays = [1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 13, 14, 15, 16, 17, 18]; // Orlando's reading pattern
                @endphp
                @for($i = 1; $i <= 30; $i++)
                    @php
                        $hasReading = in_array($i, $completedDays);
                        $isToday = $i === 18;
                        $isFuture = $i > 18;
                    @endphp
                    <div class="aspect-square p-1 text-xs rounded flex items-center justify-center
                        {{ $isToday ? 'bg-primary text-white ring-2 ring-primary ring-offset-1' : 
                           ($hasReading ? 'bg-secondary text-white' : 
                           ($isFuture ? 'bg-gray-50 dark:bg-gray-800 text-gray-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-400')) }}">
                        {{ $i }}
                    </div>
                @endfor
                
                <!-- Fill remaining grid cells for layout -->
                @for($i = 31; $i <= 35; $i++)
                    <div class="aspect-square p-1 text-xs rounded bg-gray-50 dark:bg-gray-800"></div>
                @endfor
            </div>
        </div>

        <!-- Sample Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-neutral-mid dark:border-gray-700">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Chapters</h4>
                <p class="text-2xl font-bold text-neutral-dark dark:text-gray-100 mt-1">89</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-neutral-mid dark:border-gray-700">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Books Started</h4>
                <p class="text-2xl font-bold text-neutral-dark dark:text-gray-100 mt-1">8</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-neutral-mid dark:border-gray-700">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Books Completed</h4>
                <p class="text-2xl font-bold text-neutral-dark dark:text-gray-100 mt-1">5</p>
            </div>
        </div>

        <!-- Navigation Test Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-neutral-mid dark:border-gray-700">
            <h3 class="text-lg font-semibold text-neutral-dark dark:text-gray-100 mb-4">
                🧭 Layout Features
            </h3>
            <div class="space-y-3">
                <p class="text-gray-600 dark:text-gray-400">
                    <strong>Mobile View:</strong> Optimized bottom navigation with quick access to Dashboard, History, and Profile
                </p>
                <p class="text-gray-600 dark:text-gray-400">
                    <strong>Desktop View:</strong> Elegant sidebar layout with main content (70%) and activity panel (30%)
                </p>
                <p class="text-gray-600 dark:text-gray-400">
                    <strong>Responsive Design:</strong> Seamlessly adapts to any screen size for consistent user experience
                </p>
            </div>
        </div>

        <!-- Sample Book Grid -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-neutral-mid dark:border-gray-700">
            <h3 class="text-lg font-semibold text-neutral-dark dark:text-gray-100 mb-4">
                📚 Current Reading Progress
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2 text-xs">
                @php
                    $readingProgress = [
                        ['book' => 'Genesis', 'status' => 'completed'],
                        ['book' => 'Exodus', 'status' => 'completed'],
                        ['book' => 'Matthew', 'status' => 'completed'],
                        ['book' => 'Mark', 'status' => 'completed'],
                        ['book' => 'Luke', 'status' => 'completed'],
                        ['book' => 'John', 'status' => 'in-progress'],
                        ['book' => 'Acts', 'status' => 'in-progress'],
                        ['book' => 'Romans', 'status' => 'in-progress'],
                        ['book' => 'Psalms', 'status' => 'not-started'],
                        ['book' => 'Proverbs', 'status' => 'not-started'],
                        ['book' => 'James', 'status' => 'not-started'],
                        ['book' => 'Revelation', 'status' => 'not-started']
                    ];
                @endphp
                @foreach($readingProgress as $book)
                    @php
                        $bgColor = match($book['status']) {
                            'completed' => 'bg-secondary text-white',
                            'in-progress' => 'bg-primary text-white',
                            'not-started' => 'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300'
                        };
                    @endphp
                    <div class="p-2 rounded text-center {{ $bgColor }}">
                        {{ $book['book'] }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('sidebar')
    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="bg-neutral-light dark:bg-gray-700 rounded-lg p-4">
            <h4 class="font-semibold text-neutral-dark dark:text-gray-100 mb-3">Quick Stats</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">This Week:</span>
                    <span class="font-medium">6/7 days</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">This Month:</span>
                    <span class="font-medium">16/18 days</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Avg. Reading Time:</span>
                    <span class="font-medium">22 min</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-neutral-light dark:bg-gray-700 rounded-lg p-4">
            <h4 class="font-semibold text-neutral-dark dark:text-gray-100 mb-3">Recent Readings</h4>
            <div class="space-y-3 text-sm">
                <div class="border-l-2 border-primary pl-3">
                    <p class="font-medium">John 14:1-31</p>
                    <p class="text-xs text-gray-500">Today, 7:15 AM</p>
                </div>
                <div class="border-l-2 border-secondary pl-3">
                    <p class="font-medium">John 13:1-38</p>
                    <p class="text-xs text-gray-500">Yesterday, 7:30 AM</p>
                </div>
                <div class="border-l-2 border-secondary pl-3">
                    <p class="font-medium">John 12:12-50</p>
                    <p class="text-xs text-gray-500">June 16, 6:45 AM</p>
                </div>
            </div>
        </div>

        <!-- Motivational Quote -->
        <div class="bg-accent/10 rounded-lg p-4 border border-accent/20">
            <h4 class="font-semibold text-accent mb-2">Daily Encouragement</h4>
            <blockquote class="text-xs text-gray-600 dark:text-gray-400 italic">
                "Your word is a lamp for my feet, a light on my path."
            </blockquote>
            <p class="text-xs text-accent mt-1">— Psalm 119:105</p>
        </div>
    </div>
@endsection 