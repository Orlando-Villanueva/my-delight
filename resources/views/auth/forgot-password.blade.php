@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 bg-gray-50">
                <div class="w-full max-w-md mx-auto">
            <!-- Forgot Password Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    Reset your password
                </h2>
                <p class="text-gray-600">
                    Enter your email address and we'll send you a link to reset your password
                </p>
            </div>

            <!-- Forgot Password Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                
                <!-- Display Success Message -->
                @if (session('status'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-green-800 font-medium text-sm">{{ session('status') }}</span>
                        </div>
                    </div>
                @endif
                
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
                    <p class="mt-1 text-sm text-gray-500">We'll send password reset instructions to this email address</p>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-primary-500 text-white py-3 px-4 rounded-lg font-semibold hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors"
                >
                    Send Reset Link
                </button>
            </form>
            
            <!-- Back to Login Link -->
            <div class="text-center mt-8">
                <p class="text-gray-600">
                    Remember your password? 
                    <a href="{{ route('login') }}" 
                       class="font-semibold text-primary-600 hover:text-primary-500 transition-colors">
                        Back to sign in
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection 