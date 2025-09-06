@extends('layouts.authenticated')

@section('page-title', 'Reading History')
@section('page-subtitle', 'View your Bible reading journey')

@section('content')
    <div id="main-content" class="h-full">
        @include('partials.logs-content', compact('logs'))
    </div>
@endsection 