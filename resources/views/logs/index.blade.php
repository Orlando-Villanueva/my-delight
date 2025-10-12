@extends('layouts.authenticated')

@section('page-title', 'Reading History')
@section('page-subtitle', 'View your Bible reading journey')

@section('content')
<!-- Full-width Content when no sidebar is defined -->
<div class="flex-1 p-4 lg:p-6 pb-20 lg:pb-6 container">
    <div id="main-content" class="h-full">
        @include('partials.logs-content', compact('logs'))
    </div>
</div>
@endsection 