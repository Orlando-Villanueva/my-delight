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
                Set new password
            </h2>
            <p class="text-neutral-500 dark:text-gray-400">
                Enter your new password to complete the reset process
            </p>
        </div>

        <!-- Reset Password Form Card -->
        <x-ui.card elevated="true" class="mt-8">
            <form hx-post="/reset-password" 
                  hx-target="#auth-response" 
                  hx-swap="innerHTML"
                  hx-indicator="#loading-indicator"
                  class="space-y-6">
                @csrf
                
                <!-- Hidden Token Field -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                
                <!-- Response Container -->
                <div id="auth-response" class="hidden"></div>
                
                <!-- Email Field (readonly, pre-filled) -->
                <x-ui.input 
                    type="email"
                    name="email"
                    label="Email Address"
                    :value="$request->email"
                    placeholder="Enter your email address"
                    required="true"
                    :error="$errors->first('email')"
                    readonly="true"
                />
                
                <!-- New Password Field with Strength Indicator -->
                <div x-data="{ 
                    showPassword: false, 
                    password: '',
                    strength: 0,
                    getStrength() {
                        let score = 0;
                        if (this.password.length >= 8) score++;
                        if (/[a-z]/.test(this.password)) score++;
                        if (/[A-Z]/.test(this.password)) score++;
                        if (/[0-9]/.test(this.password)) score++;
                        if (/[^A-Za-z0-9]/.test(this.password)) score++;
                        this.strength = score;
                        return score;
                    },
                    getStrengthText() {
                        const score = this.getStrength();
                        if (score === 0) return '';
                        if (score <= 2) return 'Weak';
                        if (score <= 3) return 'Fair';
                        if (score <= 4) return 'Good';
                        return 'Strong';
                    },
                    getStrengthColor() {
                        const score = this.getStrength();
                        if (score <= 2) return 'bg-error';
                        if (score <= 3) return 'bg-warning';
                        if (score <= 4) return 'bg-secondary';
                        return 'bg-success';
                    }
                }" class="space-y-1">
                    <label for="password" class="block text-sm font-medium text-neutral-700 mb-2 after:content-['*'] after:ml-0.5 after:text-error">
                        New Password
                    </label>
                    <div class="relative">
                        <input 
                            :type="showPassword ? 'text' : 'password'"
                            id="password"
                            name="password"
                            placeholder="Create a strong password"
                            required
                            x-model="password"
                            @input="getStrength()"
                            class="block w-full px-3 py-2 pr-10 border border-neutral-300 rounded-lg shadow-sm placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-neutral-600"
                        />
                        <button 
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-neutral-400 hover:text-neutral-600"
                        >
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878a3 3 0 00-.007 4.243m4.242-4.242L15.536 8.464M14.122 14.121a3 3 0 01-4.243-4.243m4.243 4.243l1.414 1.414"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div x-show="password.length > 0" class="mt-2">
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="text-neutral-500">Password strength</span>
                            <span x-text="getStrengthText()" 
                                  :class="strength <= 2 ? 'text-error' : strength <= 3 ? 'text-warning' : strength <= 4 ? 'text-secondary' : 'text-success'">
                            </span>
                        </div>
                        <div class="w-full bg-neutral-200 rounded-full h-2">
                            <div :class="getStrengthColor()" 
                                 class="h-2 rounded-full transition-all duration-300"
                                 :style="`width: ${(strength / 5) * 100}%`">
                            </div>
                        </div>
                        <div class="mt-1 text-xs text-neutral-500">
                            Use 8+ characters with a mix of letters, numbers & symbols
                        </div>
                    </div>
                    
                    @error('password')
                        <p class="text-sm text-error mt-1" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password Confirmation Field -->
                <div x-data="{ showPasswordConfirmation: false }" class="space-y-1">
                    <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 mb-2 after:content-['*'] after:ml-0.5 after:text-error">
                        Confirm New Password
                    </label>
                    <div class="relative">
                        <input 
                            :type="showPasswordConfirmation ? 'text' : 'password'"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Confirm your new password"
                            required
                            class="block w-full px-3 py-2 pr-10 border border-neutral-300 rounded-lg shadow-sm placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-neutral-600"
                        />
                        <button 
                            type="button"
                            @click="showPasswordConfirmation = !showPasswordConfirmation"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-neutral-400 hover:text-neutral-600"
                        >
                            <svg x-show="!showPasswordConfirmation" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="showPasswordConfirmation" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878a3 3 0 00-.007 4.243m4.242-4.242L15.536 8.464M14.122 14.121a3 3 0 01-4.243-4.243m4.243 4.243l1.414 1.414"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="text-sm text-error mt-1" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Submit Button -->
                <x-ui.button 
                    type="submit" 
                    variant="primary" 
                    size="lg" 
                    class="w-full"
                >
                    <span class="htmx-indicator" id="loading-indicator">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Resetting password...
                    </span>
                    <span class="htmx-indicator:not(.htmx-request)">
                        Reset password
                    </span>
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

<!-- HTMX Configuration -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configure HTMX for Fortify authentication
        document.body.addEventListener('htmx:configRequest', function(evt) {
            evt.detail.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        });
        
        // Handle successful password reset
        document.body.addEventListener('htmx:afterRequest', function(evt) {
            if (evt.detail.xhr.status === 302 && evt.detail.pathInfo.requestPath === '/reset-password') {
                // Redirect to login with success message
                window.location.href = '/login?reset=success';
            }
        });
        
        // Handle validation errors
        document.body.addEventListener('htmx:responseError', function(evt) {
            if (evt.detail.xhr.status === 422) {
                // Display validation errors
                const response = JSON.parse(evt.detail.xhr.responseText);
                if (response.errors) {
                    let errorHtml = '<div class="bg-error/10 border border-error/20 rounded-lg p-4 mb-4">';
                    errorHtml += '<div class="flex items-center"><svg class="w-5 h-5 text-error mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
                    errorHtml += '<span class="text-error font-medium">Please correct the following errors:</span></div>';
                    errorHtml += '<ul class="mt-2 text-sm text-error">';
                    Object.values(response.errors).forEach(function(errors) {
                        errors.forEach(function(error) {
                            errorHtml += '<li>• ' + error + '</li>';
                        });
                    });
                    errorHtml += '</ul></div>';
                    document.getElementById('auth-response').innerHTML = errorHtml;
                    document.getElementById('auth-response').classList.remove('hidden');
                }
            }
        });
    });
</script>
@endsection 