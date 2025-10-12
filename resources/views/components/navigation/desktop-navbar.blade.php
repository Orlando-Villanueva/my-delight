{{-- Desktop Navbar Component --}}
{{-- Flowbite-based navbar with logo and profile dropdown --}}

<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <a href="{{ route('dashboard') }}"
                    hx-get="{{ route('dashboard') }}"
                    hx-target="#page-container"
                    hx-push-url="true"
                    class="flex ms-2 md:me-24">
                    <img src="{{ asset('images/logo-64.png') }}?v={{ config('app.asset_version') }}"
                        class="h-8 me-3"
                        alt="{{ config('app.name') }} Logo" />
                    <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">{{ config('app.name') }}</span>
                </a>
            </div>
            <div class="flex items-center">
                <div class="flex items-center ms-3">
                    <div>
                        <button type="button"
                            class="flex text-sm bg-primary-500 rounded-full focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-600"
                            aria-expanded="false"
                            data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'U' }}
                            </div>
                        </button>
                    </div>
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                        id="dropdown-user">
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
    </div>
</nav>
