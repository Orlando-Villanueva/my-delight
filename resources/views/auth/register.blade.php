@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo and Header -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-primary mb-2">
                Bible Habit Builder
            </h1>
            <h2 class="text-xl font-semibold text-neutral-dark dark:text-gray-100 mb-2">
                Start your journey
            </h2>
            <p class="text-neutral-500 dark:text-gray-400">
                Create your account to begin tracking your Bible reading habit
            </p>
        </div>

        <!-- Registration Form Card -->
        <x-ui.card elevated="true" class="mt-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                
                <!-- Display Validation Errors -->
                @if ($errors->any())
                    <div class="bg-error/10 border border-error/20 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-error mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-error font-medium">Please correct the following errors:</span>
                        </div>
                        <ul class="text-sm text-error space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Name Field -->
                <x-ui.input 
                    type="text"
                    name="name"
                    label="Full Name"
                    placeholder="Enter your full name"
                    required="true"
                    :value="old('name')"
                    :error="$errors->first('name')"
                />
                
                <!-- Email Field -->
                <x-ui.input 
                    type="email"
                    name="email"
                    label="Email Address"
                    placeholder="Enter your email"
                    required="true"
                    :value="old('email')"
                    :error="$errors->first('email')"
                />
                
                <!-- Password Field -->
                <x-ui.input 
                    type="password"
                    name="password"
                    label="Password"
                    placeholder="Create a strong password"
                    required="true"
                    :error="$errors->first('password')"
                    help="Use 8+ characters with a mix of letters, numbers & symbols"
                />
                
                <!-- Password Confirmation Field -->
                <x-ui.input 
                    type="password"
                    name="password_confirmation"
                    label="Confirm Password"
                    placeholder="Confirm your password"
                    required="true"
                    :error="$errors->first('password_confirmation')"
                />
                
                <!-- Terms of Service Checkbox -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input 
                            id="terms"
                            name="terms"
                            type="checkbox"
                            required
                            class="h-4 w-4 text-primary focus:ring-primary border-neutral-300 rounded"
                        />
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="text-neutral-600">
                            I agree to the 
                            <a href="#" class="font-medium text-primary hover:text-primary/80 transition-colors">
                                Terms of Service
                            </a>
                            and 
                            <a href="#" class="font-medium text-primary hover:text-primary/80 transition-colors">
                                Privacy Policy
                            </a>
                        </label>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <x-ui.button 
                    type="submit" 
                    variant="primary" 
                    size="lg" 
                    class="w-full"
                >
                    Create account
                </x-ui.button>
            </form>
        </x-ui.card>
        
        <!-- Login Link -->
        <div class="text-center">
            <p class="text-neutral-500 dark:text-gray-400">
                Already have an account?
                <a href="{{ route('login') }}" 
                   class="font-medium text-primary hover:text-primary/80 transition-colors">
                    Sign in here
                </a>
            </p>
        </div>
        
        <!-- Language Toggle -->
        <div class="text-center">
            <div class="inline-flex rounded-lg border border-neutral-300 p-1">
                <button class="px-3 py-1 text-sm font-medium text-primary bg-primary/10 rounded-md">
                    EN
                </button>
                <button class="px-3 py-1 text-sm font-medium text-neutral-500 hover:text-neutral-700 rounded-md">
                    FR
                </button>
            </div>
        </div>
    </div>
</div>
@endsection 