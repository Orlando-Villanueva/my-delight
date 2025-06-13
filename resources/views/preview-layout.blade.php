@extends('layouts.authenticated')

@section('page-title', 'Layout Preview')
@section('page-subtitle', 'Testing the responsive layout with sample content')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-neutral-mid dark:border-gray-700">
            <h1 class="text-2xl font-bold text-neutral-dark dark:text-gray-100 mb-2">
                🎨 Responsive Layout Preview
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                This preview shows the authenticated layout with sample content. Test the responsiveness by resizing your browser window.
            </p>
        </div>

        <!-- Streak Card (Sample) -->
        <div class="bg-gradient-to-r from-primary to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold mb-1">Current Streak</h2>
                    <div class="flex items-center space-x-3">
                        <span class="text-4xl font-bold">🔥</span>
                        <span class="text-3xl font-bold">7 days</span>
                    </div>
                    <p class="text-blue-100 text-sm mt-1">Longest streak: 14 days</p>
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
                📅 Reading Calendar (Sample)
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
                
                <!-- Sample calendar days -->
                @for($i = 1; $i <= 35; $i++)
                    @php
                        $hasReading = $i <= 20 && ($i % 3 !== 0); // Sample pattern
                        $dayNumber = $i <= 20 ? $i : '';
                    @endphp
                    <div class="aspect-square p-1 text-xs rounded {{ $hasReading ? 'bg-secondary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-400' }}">
                        {{ $dayNumber }}
                    </div>
                @endfor
            </div>
        </div>

        <!-- Sample Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-neutral-mid dark:border-gray-700">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Chapters</h4>
                <p class="text-2xl font-bold text-neutral-dark dark:text-gray-100 mt-1">127</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-neutral-mid dark:border-gray-700">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Books Started</h4>
                <p class="text-2xl font-bold text-neutral-dark dark:text-gray-100 mt-1">12</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-neutral-mid dark:border-gray-700">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Books Completed</h4>
                <p class="text-2xl font-bold text-neutral-dark dark:text-gray-100 mt-1">3</p>
            </div>
        </div>

        <!-- Navigation Test Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-neutral-mid dark:border-gray-700">
            <h3 class="text-lg font-semibold text-neutral-dark dark:text-gray-100 mb-4">
                🧭 Navigation Test
            </h3>
            <div class="space-y-3">
                <p class="text-gray-600 dark:text-gray-400">
                    <strong>Mobile:</strong> Check the bottom navigation (3 tabs) and FAB button
                </p>
                <p class="text-gray-600 dark:text-gray-400">
                    <strong>Desktop:</strong> Check the left sidebar navigation and 70%/30% layout split
                </p>
                <p class="text-gray-600 dark:text-gray-400">
                    <strong>Responsive:</strong> Resize window to see layout changes at 1024px breakpoint
                </p>
            </div>
        </div>

        <!-- Sample Book Grid -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-neutral-mid dark:border-gray-700">
            <h3 class="text-lg font-semibold text-neutral-dark dark:text-gray-100 mb-4">
                📚 Bible Books Progress (Sample)
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2 text-xs">
                @php
                    $sampleBooks = ['Genesis', 'Exodus', 'Leviticus', 'Numbers', 'Deuteronomy', 'Joshua', 'Judges', 'Ruth', 'Matthew', 'Mark', 'Luke', 'John'];
                    $statuses = ['completed', 'in-progress', 'not-started'];
                @endphp
                @foreach($sampleBooks as $index => $book)
                    @php
                        $status = $statuses[$index % 3];
                        $bgColor = match($status) {
                            'completed' => 'bg-secondary text-white',
                            'in-progress' => 'bg-primary text-white',
                            'not-started' => 'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300'
                        };
                    @endphp
                    <div class="p-2 rounded text-center {{ $bgColor }}">
                        {{ $book }}
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
                    <span class="font-medium">5/7 days</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">This Month:</span>
                    <span class="font-medium">18/30 days</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Reading Time:</span>
                    <span class="font-medium">~15 min</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-neutral-light dark:bg-gray-700 rounded-lg p-4">
            <h4 class="font-semibold text-neutral-dark dark:text-gray-100 mb-3">Recent Readings</h4>
            <div class="space-y-3 text-sm">
                <div class="border-l-2 border-primary pl-3">
                    <p class="font-medium">John 3</p>
                    <p class="text-xs text-gray-500">Today, 8:30 AM</p>
                </div>
                <div class="border-l-2 border-secondary pl-3">
                    <p class="font-medium">John 2</p>
                    <p class="text-xs text-gray-500">Yesterday, 7:45 AM</p>
                </div>
                <div class="border-l-2 border-secondary pl-3">
                    <p class="font-medium">John 1</p>
                    <p class="text-xs text-gray-500">2 days ago, 9:15 AM</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Layout Info -->
        <div class="bg-accent/10 rounded-lg p-4 border border-accent/20">
            <h4 class="font-semibold text-accent mb-2">Desktop Sidebar (30%)</h4>
            <p class="text-xs text-gray-600 dark:text-gray-400">
                This sidebar is only visible on desktop (lg+ breakpoint). On mobile, this content would be integrated into the main content area.
            </p>
        </div>
    </div>
@endsection 