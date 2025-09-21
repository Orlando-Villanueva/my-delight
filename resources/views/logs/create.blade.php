@extends('layouts.authenticated')

@section('page-title', 'Log Reading')
@section('page-subtitle', 'Record your Bible reading progress')

@section('content')
    <div id="main-content" class="h-full">
        <div class="max-w-2xl mx-auto sm:px-20 lg:px-32">
            <x-bible.reading-log-form
                :books="$books"
                :allowYesterday="$allowYesterday ?? false"
                :hasReadYesterday="$hasReadYesterday ?? false"
                :hasReadToday="$hasReadToday ?? false"
                :currentStreak="$currentStreak ?? 0"
            />
        </div>
    </div>
@endsection 