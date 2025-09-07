@extends('layouts.authenticated')

@section('page-title', 'Log Bible Reading')
@section('page-subtitle', 'Record your Bible reading progress')

@section('content')
    <div id="main-content" class="h-full">
        @include('partials.reading-log-page-content', compact('books', 'errors', 'allowYesterday'))
    </div>
@endsection 