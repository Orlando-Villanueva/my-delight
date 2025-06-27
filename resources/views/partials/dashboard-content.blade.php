{{-- Dashboard Content Partial --}}
{{-- This partial is loaded via HTMX for seamless content loading --}}

<div class="space-y-6">
    <!-- Welcome Section -->
    <x-ui.card>
        <h1 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600 mb-4">You're successfully logged in to your Bible reading journey.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div class="p-3 bg-gray-50 rounded border">
                <div class="font-medium text-gray-700">Account Status</div>
                <div class="text-gray-600">✅ Active</div>
            </div>
            <div class="p-3 bg-gray-50 rounded border">
                <div class="font-medium text-gray-700">Email</div>
                <div class="text-gray-600">{{ auth()->user()->email }}</div>
            </div>
            <div class="p-3 bg-gray-50 rounded border">
                <div class="font-medium text-gray-700">Member Since</div>
                <div class="text-gray-600">{{ auth()->user()->created_at->format('M j, Y') }}</div>
            </div>
        </div>
    </x-ui.card>

    <!-- MVP Status Notice -->
    <x-ui.card class="bg-yellow-50 border-yellow-200">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">MVP Development Status</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>You're seeing the authenticated dashboard! 🎉 The core Bible reading features will be implemented in the coming weeks according to our development roadmap.</p>
                </div>
            </div>
        </div>
    </x-ui.card>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-ui.card>
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 ml-3">Log Reading</h3>
            </div>
            <p class="text-gray-600 text-sm mb-4">Record your daily Bible reading progress</p>
            <button hx-get="{{ route('logs.create') }}" 
                    hx-target="#main-content" 
                    hx-swap="innerHTML"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                📖 Log Reading
            </button>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 ml-3">View History</h3>
            </div>
            <p class="text-gray-600 text-sm mb-4">See your reading calendar and streaks (Coming in Week 5)</p>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                In Development
            </span>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 ml-3">View Statistics</h3>
            </div>
            <p class="text-gray-600 text-sm mb-4">Track your progress and achievements (Coming in Week 6)</p>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                In Development
            </span>
        </x-ui.card>
    </div>


</div> 