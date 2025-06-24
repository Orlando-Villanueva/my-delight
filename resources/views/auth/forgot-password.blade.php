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
                Reset your password
            </h2>
            <p class="text-neutral-500 dark:text-gray-400">
                Enter your email address and we'll send you a link to reset your password
            </p>
        </div>

        <!-- Forgot Password Form Card -->
        <x-ui.card elevated="true" class="mt-8">
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                
                <!-- Display Success Message -->
                @if (session('status'))
                    <div class="bg-secondary/10 border border-secondary/20 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-secondary mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-secondary font-medium">{{ session('status') }}</span>
                        </div>
                    </div>
                @endif
                
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
                
                <!-- Email Field -->
                <x-ui.input 
                    type="email"
                    name="email"
                    label="Email Address"
                    placeholder="Enter your email address"
                    required="true"
                    :value="old('email')"
                    :error="$errors->first('email')"
                    help="We'll send password reset instructions to this email address"
                />
                
                <!-- Submit Button -->
                <x-ui.button 
                    type="submit" 
                    variant="primary" 
                    size="lg" 
                    class="w-full"
                >
                    Send reset link
                </x-ui.button>
            </form>
        </x-ui.card>
        
        <!-- Back to Login Link -->
        <div class="text-center">
            <p class="text-neutral-500 dark:text-gray-400">
                Remember your password?
                <a href="{{ route('login') }}" 
                   class="font-medium text-primary hover:text-primary/80 transition-colors">
                    Back to sign in
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