@extends('emails.layouts.base')

@section('title', 'Welcome to Delight!')

@section('content')
<h2 class="greeting">Welcome to Delight, {{ $user->name }}!</h2>

<p class="message">
    We're thrilled to have you join our community of Bible readers who are building consistent reading habits.
    Delight is here to help you track your progress, maintain reading streaks, and find joy in daily Scripture reading.
</p>

<div class="notice notice-success">
    <p class="notice-title">🎉 You're all set!</p>
    <p class="notice-text">
        Your account is ready to go. Start logging your Bible reading today and watch your streak grow!
    </p>
</div>

<div class="button-container">
    <a href="{{ url('/dashboard') }}" class="button">Start Reading & Tracking</a>
</div>

<div class="content-card">
    <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600; color: #111827;">Getting Started Tips</h3>

    <div style="margin-bottom: 16px;">
        <strong style="color: #374151;">📖 Log Your Reading:</strong>
        <span style="color: #6b7280;">Use the simple book and chapter selector to track what you've read each day.</span>
    </div>

    <div style="margin-bottom: 16px;">
        <strong style="color: #374151;">🔥 Build Your Streak:</strong>
        <span style="color: #6b7280;">Read consistently to build and maintain your reading streak (with a 1-day grace period).</span>
    </div>

    <div style="margin-bottom: 16px;">
        <strong style="color: #374151;">📊 Track Progress:</strong>
        <span style="color: #6b7280;">Watch your book completion grid fill up as you progress through the Bible.</span>
    </div>

    <div>
        <strong style="color: #374151;">📅 View History:</strong>
        <span style="color: #6b7280;">Check your calendar view to see your reading activity over time.</span>
    </div>
</div>

<p class="message">
    Remember, consistency is key! Even reading just one chapter a day can help you build a lasting Bible reading habit.
    We're here to support you on this journey.
</p>

<div class="notice notice-info">
    <p class="notice-title">💡 Pro Tip</p>
    <p class="notice-text">
        Set a daily reminder and choose a consistent time for reading. Many users find success reading first thing in the morning or before bed.
    </p>
</div>

<hr class="divider">

<p class="message text-small text-muted">
    If you have any questions or need help getting started, don't hesitate to reach out.
    We're excited to see your reading journey unfold!
</p>
@endsection

@section('footer-extra')
<p class="footer-text">
    <a href="{{ url('/dashboard') }}" class="footer-link">Go to Dashboard</a> |
    <a href="{{ url('/reading-log') }}" class="footer-link">Log Reading</a> |
    <a href="{{ url('/progress') }}" class="footer-link">View Progress</a>
</p>
@endsection