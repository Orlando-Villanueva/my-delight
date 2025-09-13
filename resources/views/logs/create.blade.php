@extends('layouts.authenticated')

@section('page-title', 'Log Reading')
@section('page-subtitle', 'Record your Bible reading progress')

@section('content')
    <div id="main-content" class="h-full">
        <div class="max-w-2xl mx-auto sm:px-20 lg:px-32">
            @include('partials.reading-log-form', compact('books', 'errors', 'allowYesterday', 'hasReadYesterday', 'currentStreak', 'hasReadToday'))
        </div>
    </div>
@endsection 