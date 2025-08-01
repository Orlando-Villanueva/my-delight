@extends('emails.layouts.base')

@section('title', 'Reset Your Password - Delight')

@section('content')
    <h2 class="greeting">Reset Your Password</h2>
    
    <p class="message">
        Hello! We received a request to reset your password for your Delight account. 
        Click the button below to create a new password and get back to building your Bible reading habit.
    </p>

    <div class="button-container">
        <a href="{{ $resetUrl }}" class="button">Reset My Password</a>
    </div>

    <p class="message">
        This password reset link will expire in {{ config('auth.passwords.users.expire', 60) }} minutes for your security.
    </p>

    <div class="notice notice-security">
        <p class="notice-title">🔒 Security Notice</p>
        <p class="notice-text">
            If you didn't request this password reset, you can safely ignore this email. 
            Your password will remain unchanged.
        </p>
    </div>

    <hr class="divider">
    
    <p class="message text-small text-muted">
        If you're having trouble clicking the button, copy and paste the URL below into your web browser:
    </p>
    <p class="text-small">
        <a href="{{ $resetUrl }}" class="text-link" style="word-break: break-all;">{{ $resetUrl }}</a>
    </p>
@endsection