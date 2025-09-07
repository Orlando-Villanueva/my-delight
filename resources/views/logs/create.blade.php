@extends('layouts.authenticated')

@section('page-title', 'Log Bible Reading')
@section('page-subtitle', 'Record your Bible reading progress')

@section('content')
    <div id="main-content" class="h-full">
        <div class="max-w-2xl mx-auto">
            @include('partials.reading-log-form', compact('books', 'errors', 'allowYesterday', 'hasReadYesterday', 'currentStreak', 'hasReadToday'))
        </div>
    </div>
@endsection 