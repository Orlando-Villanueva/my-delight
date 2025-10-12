{{-- Mobile Navbar Component --}}
{{-- Flowbite-based navbar that scrolls with content (not fixed) --}}

<nav class="lg:hidden bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center">
                    <img
                        src="{{ asset('images/logo-64.png') }}?v={{ config('app.asset_version') }}"
                        srcset="{{ asset('images/logo-64.png') }}?v={{ config('app.asset_version') }} 1x, {{ asset('images/logo-64-2x.png') }}?v={{ config('app.asset_version') }} 2x"
                        alt="{{ config('app.name') }} Logo"
                        class="w-full h-full object-contain" />
                </div>
                <h1 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">
                    {{ config('app.name') }}
                </h1>
            </div>

            <!-- Mobile User Menu -->
            <div class="flex items-center">
                <button type="button"
                    class="flex text-sm bg-primary-500 rounded-full focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-600"
                    aria-expanded="false"
                    data-dropdown-toggle="dropdown-user-mobile">
                    <span class="sr-only">Open user menu</span>
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-medium">
                        {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'U' }}
                    </div>
                </button>

                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                    id="dropdown-user-mobile">
                    <div class="px-4 py-3" role="none">
                        <p class="text-sm text-gray-900 dark:text-white" role="none">
                            {{ auth()->check() ? auth()->user()->name : 'User Name' }}
                        </p>
                        <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                            {{ auth()->check() ? auth()->user()->email : 'user@example.com' }}
                        </p>
                    </div>
                    <ul class="py-1" role="none">
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                    role="menuitem">
                                    Sign out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
