@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Side - Brand/Info -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#3366CC] to-[#1e40af] relative overflow-hidden">
        <div class="flex flex-col justify-center items-center text-center text-white p-12 w-full h-full">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mb-8 p-3">
                <img 
                    src="{{ asset('images/logo-64.png') }}"
                    srcset="{{ asset('images/logo-64.png') }} 1x, {{ asset('images/logo-64-2x.png') }} 2x"
                    alt="Bible Habit Builder Logo" 
                    class="w-full h-full object-contain"
                />
            </div>
            <h1 class="text-4xl font-bold mb-4">
                Build Your Bible Reading Habit
            </h1>
            <p class="text-xl text-white text-opacity-90 leading-relaxed max-w-md">
                Track your daily Bible reading progress, discover insights, and strengthen your faith journey with our comprehensive reading companion.
            </p>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 bg-gray-50">
        <div class="w-full max-w-md mx-auto">
            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        Welcome back
                    </h2>
                    <p class="text-gray-600">
                        Login to your Bible Habit Builder account
                    </p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-red-800 font-medium text-sm">Please correct the following errors:</span>
                            </div>
                            <ul class="text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input 
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all bg-white {{ $errors->has('email') ? 'border-red-300 focus:ring-red-500' : '' }}"
                            placeholder=""
                        />
                        @if($errors->has('email'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                    
                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                            <a href="{{ route('password.request') }}" 
                               class="text-sm text-primary-600 hover:text-primary-500 transition-colors">
                                Forgot your password?
                            </a>
                        </div>
                        <input 
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all bg-white {{ $errors->has('password') ? 'border-red-300 focus:ring-red-500' : '' }}"
                            placeholder=""
                        />
                        @if($errors->has('password'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('password') }}</p>
                        @endif
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-primary-500 text-white py-3 px-4 rounded-lg font-semibold hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors"
                    >
                        Sign In
                    </button>
                </form>
                
                <!-- Register Link -->
                <div class="text-center mt-8">
                    <p class="text-gray-600">
                        Don't have an account? 
                        <a href="{{ route('register') }}" 
                           class="font-semibold text-primary-600 hover:text-primary-500 transition-colors">
                            Sign up
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 