{{-- Shared Profile Dropdown Component --}}
{{-- Used in both desktop and mobile navigation --}}

@props([
    'dropdownId' => 'dropdown-user',
    'size' => 'default', // 'default' (w-9 h-9) or 'small' (w-8 h-8)
])

<div class="flex items-center">
    <button type="button"
        class="flex text-sm bg-primary-500 rounded-full focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-600"
        aria-expanded="false"
        data-dropdown-toggle="{{ $dropdownId }}">
        <span class="sr-only">Open user menu</span>
        <div @class([
            'rounded-full flex items-center justify-center text-white font-medium',
            'w-9 h-9 text-base' => $size === 'default',
            'w-8 h-8 text-sm' => $size === 'small',
        ])>
            {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'U' }}
        </div>
    </button>

    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
        id="{{ $dropdownId }}">
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