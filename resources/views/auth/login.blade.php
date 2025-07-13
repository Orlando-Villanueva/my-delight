@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#F5F7FA] to-white dark:from-gray-900 dark:to-gray-800 flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
        <!-- Logo Section -->
        <div class="text-center">
            <img 
                src="{{ asset('images/logo-64.png') }}?v={{ time() }}"
                srcset="{{ asset('images/logo-64.png') }}?v={{ time() }} 1x, {{ asset('images/logo-64-2x.png') }}?v={{ time() }} 2x"
                alt="Bible Habit Builder Logo" 
                class="w-20 h-20 object-contain mx-auto mb-6"
                style="filter: drop-shadow(0 0 15px rgba(51, 102, 204, 0.2)) drop-shadow(0 0 30px rgba(51, 102, 204, 0.08));"
            />
            <h1 class="text-2xl font-bold text-[#4A5568] dark:text-gray-200 mb-2">Bible Habit Builder</h1>
            <p class="text-[#4A5568] dark:text-gray-300 opacity-80">Build Your Bible Reading Habit</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-[#D1D7E0] dark:border-gray-700 p-8 sm:p-10">
            <div class="space-y-6">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-[#4A5568] dark:text-gray-200 mb-2">
                        Welcome back
                    </h2>
                    <p class="text-[#4A5568] dark:text-gray-300 opacity-75">
                        Login to your Bible Habit Builder account
                    </p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
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
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                        <input 
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 {{ $errors->has('email') ? 'border-red-300 focus:ring-red-500' : '' }}"
                            placeholder=""
                        />
                        @if($errors->has('email'))
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                    
                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Password</label>
                            <a href="{{ route('password.request') }}" 
                               class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300 transition-colors">
                                Forgot your password?
                            </a>
                        </div>
                        <input 
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 {{ $errors->has('password') ? 'border-red-300 focus:ring-red-500' : '' }}"
                            placeholder=""
                        />
                        @if($errors->has('password'))
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('password') }}</p>
                        @endif
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-primary-500 text-white py-3 px-4 rounded-lg font-semibold hover:bg-primary-600 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200"
                    >
                        Sign In
                    </button>
                </form>
                
                <!-- Register Link -->
                <div class="text-center mt-8">
                    <p class="text-[#4A5568] dark:text-gray-300 opacity-75">
                        Don't have an account? 
                        <a href="{{ route('register') }}" 
                           class="font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300 transition-colors">
                            Sign up
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 