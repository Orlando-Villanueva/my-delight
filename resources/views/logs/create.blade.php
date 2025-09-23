@extends('layouts.authenticated')

@section('page-title', 'Log Reading')
@section('page-subtitle', 'Record your Bible reading progress')

@section('content')
    <div id="main-content" class="h-full">
        <x-bible.reading-log-form
            :books="$books"
            :allowYesterday="$allowYesterday ?? false"
            :hasReadYesterday="$hasReadYesterday ?? false"
            :hasReadToday="$hasReadToday ?? false"
            :currentStreak="$currentStreak ?? 0"
        />
    </div>
@endsection 