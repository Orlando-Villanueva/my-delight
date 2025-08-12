@extends('emails.layouts.base')

@section('title', 'New Weekly Target Feature - Delight')

@section('content')
<h2 class="greeting">📊 Introducing Weekly Targets</h2>

<p class="message">
    We're excited to announce a powerful new addition to your Delight dashboard: <strong>Weekly Targets</strong>. This feature helps you build a sustainable Bible reading habit with a research-backed goal of reading 4 days per week.
</p>

<div class="notice notice-success">
    <h4 class="notice-title">Your New Weekly Progress Tracker</h4>
    <p class="notice-text">
        See your current week's reading progress displayed as "3/4 days this week" with a visual progress bar that fills as you read throughout the week.
    </p>
</div>

<div class="content-card">
    <h3 style="color: #111827; margin: 0 0 16px 0; font-size: 18px; font-weight: 600;">What's New:</h3>
    
    <div style="margin-bottom: 16px;">
        <strong style="color: #059669;">✅ Weekly Progress Tracking</strong><br>
        <span style="color: #6b7280; font-size: 14px;">See your current week's reading progress at a glance with "X/4 days this week" display</span>
    </div>

    <div style="margin-bottom: 16px;">
        <strong style="color: #059669;">📈 Visual Progress Bar</strong><br>
        <span style="color: #6b7280; font-size: 14px;">Watch your progress fill up throughout the week with an encouraging progress indicator</span>
    </div>

    <div style="margin-bottom: 16px;">
        <strong style="color: #059669;">🎯 Research-Backed Target</strong><br>
        <span style="color: #6b7280; font-size: 14px;">The 4-days-per-week goal is based on habit formation research for sustainable growth</span>
    </div>

    <div style="margin-bottom: 0;">
        <strong style="color: #059669;">🔄 Automatic Weekly Reset</strong><br>
        <span style="color: #6b7280; font-size: 14px;">Fresh start every Sunday - no manual tracking needed</span>
    </div>
</div>

<p class="message">
    <strong>Why Weekly Targets?</strong><br>
    While daily streaks are great for momentum, weekly targets provide a more balanced approach that accounts for life's realities. Missing one day doesn't break your progress - you can still achieve your weekly goal and maintain the habit.
</p>

<div class="button-container">
    <a href="{{ config('app.url') }}/dashboard" class="button">Check Your Weekly Progress</a>
</div>

<div class="notice notice-info">
    <h4 class="notice-title">Seamless Integration</h4>
    <p class="notice-text">
        The weekly target widget now appears prominently on your dashboard alongside your existing streak counter. Both metrics work together to give you a complete picture of your reading consistency.
    </p>
</div>

<p class="message">
    This feature automatically tracks your reading logs - no additional setup required. Just keep logging your daily Bible reading and watch your weekly progress grow!
</p>

<hr class="divider">

<p class="message mb-0">
    <strong>Happy reading!</strong><br>
    <em style="color: #6b7280;">The Delight Team</em><br>
    <span class="text-small text-muted">Helping you delight in God's Word, one day at a time</span>
</p>
@endsection