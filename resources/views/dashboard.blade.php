@extends('layouts.authenticated')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name)

@section('content')
    <div id="main-content" class="h-full">
        @include('partials.dashboard-content')
    </div>
@endsection

@section('sidebar')
    @include('partials.dashboard-sidebar')
@endsection 