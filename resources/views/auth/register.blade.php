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
                Start Your Bible Journey
            </h1>
            <p class="text-xl text-white text-opacity-90 leading-relaxed max-w-md">
                Join thousands of believers building consistent Bible reading habits. Track your progress and grow in your faith daily.
            </p>
        </div>
    </div>

    <!-- Right Side - Register Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 bg-gray-50">
        <div class="w-full max-w-md mx-auto">
            <!-- Register Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        Create account
                    </h2>
                    <p class="text-gray-600">
                        Start your Bible reading journey today
                    </p>
                </div>

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
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
                
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                        <input 
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all bg-white {{ $errors->has('name') ? 'border-red-300 focus:ring-red-500' : '' }}"
                            placeholder=""
                        />
                        @if($errors->has('name'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('name') }}</p>
                        @endif
                    </div>
                    
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
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
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
                    
                    <!-- Password Confirmation Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                        <input 
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all bg-white {{ $errors->has('password_confirmation') ? 'border-red-300 focus:ring-red-500' : '' }}"
                            placeholder=""
                        />
                        @if($errors->has('password_confirmation'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('password_confirmation') }}</p>
                        @endif
                    </div>
                    
                    <!-- Terms of Service Checkbox -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input 
                                id="terms"
                                name="terms"
                                type="checkbox"
                                required
                                class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                            />
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="text-gray-600">
                                I agree to the 
                                <a href="#" class="font-semibold text-primary-600 hover:text-primary-500 transition-colors">
                                    Terms of Service
                                </a>
                                and 
                                <a href="#" class="font-semibold text-primary-600 hover:text-primary-500 transition-colors">
                                    Privacy Policy
                                </a>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-primary-500 text-white py-3 px-4 rounded-lg font-semibold hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors"
                    >
                        Create Account
                    </button>
                </form>
                
                <!-- Login Link -->
                <div class="text-center mt-8">
                    <p class="text-gray-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" 
                           class="font-semibold text-primary-600 hover:text-primary-500 transition-colors">
                            Sign in
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 