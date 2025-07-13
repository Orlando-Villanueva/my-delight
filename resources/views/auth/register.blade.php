@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#F5F7FA] to-white dark:from-gray-900 dark:to-gray-800 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-8">
    <div class="w-full max-w-md space-y-6">
        <!-- Logo Section -->
        <div class="text-center">
            <img 
                src="{{ asset('images/logo-64.png') }}?v={{ time() }}"
                srcset="{{ asset('images/logo-64.png') }}?v={{ time() }} 1x, {{ asset('images/logo-64-2x.png') }}?v={{ time() }} 2x"
                alt="Bible Habit Builder Logo" 
                class="w-20 h-20 object-contain mx-auto mb-4"
                style="filter: drop-shadow(0 0 15px rgba(51, 102, 204, 0.2)) drop-shadow(0 0 30px rgba(51, 102, 204, 0.08));"
            />
            <h1 class="text-2xl font-bold text-[#4A5568] dark:text-gray-200 mb-1">Bible Habit Builder</h1>
            <p class="text-[#4A5568] dark:text-gray-300 opacity-80">Start Your Bible Journey</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-[#D1D7E0] dark:border-gray-700 p-6 sm:p-8">
            <div class="space-y-5">
                <!-- Header -->
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-[#4A5568] dark:text-gray-200 mb-1">
                        Create account
                    </h2>
                    <p class="text-[#4A5568] dark:text-gray-300 opacity-75">
                        Start your Bible reading journey today
                    </p>
                </div>

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                
                <!-- Display Validation Errors -->
                @if ($errors->any())
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-red-800 dark:text-red-400 font-medium text-sm">Please correct the following errors:</span>
                        </div>
                        <ul class="text-sm text-red-700 dark:text-red-400 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Name</label>
                        <input 
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 {{ $errors->has('name') ? 'border-red-300 focus:ring-red-500' : '' }}"
                            placeholder=""
                        />
                        @if($errors->has('name'))
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('name') }}</p>
                        @endif
                    </div>
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input 
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 {{ $errors->has('email') ? 'border-red-300 focus:ring-red-500' : '' }}"
                            placeholder=""
                        />
                        @if($errors->has('email'))
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                    
                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Password</label>
                        <div class="relative">
                            <input 
                                type="password"
                                id="password"
                                name="password"
                                required
                                class="w-full px-4 py-2.5 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 {{ $errors->has('password') ? 'border-red-300 focus:ring-red-500' : '' }}"
                                placeholder="Minimum 8 characters"
                            />
                            <button 
                                type="button" 
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none"
                                aria-label="Toggle password visibility"
                            >
                                <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.465 8.465m1.413 1.413L11.5 11.5m4.242 4.242L17.5 17.5m-1.758-1.758L13.12 13.12"/>
                                </svg>
                            </button>
                        </div>
                        @if($errors->has('password'))
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('password') }}</p>
                        @endif
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-primary-500 text-white py-2.5 px-4 rounded-lg font-semibold hover:bg-primary-600 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200 mt-6"
                    >
                        Create Account
                    </button>
                </form>
                
                <!-- Login Link -->
                <div class="text-center mt-6">
                    <p class="text-[#4A5568] dark:text-gray-300 opacity-75">
                        Already have an account? 
                        <a href="{{ route('login') }}" 
                           class="font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300 transition-colors">
                            Sign in
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        passwordInput.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}
</script> 