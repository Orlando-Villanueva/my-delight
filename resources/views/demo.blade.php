@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4 text-center">HTMX + Alpine.js Demo</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- HTMX Demo Section -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">HTMX Demo</h2>
            <p class="mb-2 text-sm">Click the button to fetch a random quote:</p>
            
            <button 
                class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded text-sm"
                hx-get="{{ route('demo.verse') }}"
                hx-target="#quote-container"
                hx-indicator="#htmx-indicator"
            >
                Get Random Quote
            </button>
            
            <div class="mt-2">
                <div id="htmx-indicator" class="htmx-indicator text-center py-1">
                    <span class="text-xs">Loading...</span>
                </div>
                <div id="quote-container" class="border rounded p-2 min-h-[60px] text-sm">
                    <p class="text-gray-500 dark:text-gray-400 italic">Quote will appear here</p>
                </div>
            </div>
        </div>
        
        <!-- Alpine.js Demo Section -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">Alpine.js Demo</h2>
            <p class="mb-2 text-sm">Simple counter with state management:</p>
            
            <div x-data="{ count: 0 }" class="text-center p-2 border rounded">
                <div class="text-2xl font-bold mb-2" x-text="count"></div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Current Count</p>
                
                <div class="flex justify-center space-x-2">
                    <button 
                        @click="count++"
                        class="bg-green-500 hover:bg-green-600 text-white py-1 px-2 rounded text-xs"
                    >
                        Increment
                    </button>
                    <button 
                        @click="count--"
                        class="bg-red-500 hover:bg-red-600 text-white py-1 px-2 rounded text-xs"
                    >
                        Decrement
                    </button>
                    <button 
                        @click="count = 0"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-1 px-2 rounded text-xs"
                    >
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Combined Demo Section -->
    <div class="mt-4 bg-white dark:bg-gray-800 p-4 rounded shadow">
        <h2 class="text-lg font-semibold mb-2">Combined HTMX + Alpine.js Demo</h2>
        <p class="mb-2 text-sm">This example shows both technologies working together:</p>
        
        <div x-data="{ showForm: false, message: '' }">
            <button 
                @click="showForm = !showForm"
                class="bg-purple-500 hover:bg-purple-600 text-white py-1 px-2 rounded text-xs mb-2"
                x-text="showForm ? 'Cancel' : 'Add Comment'"
            ></button>
            
            <div x-show="showForm" x-transition class="border rounded p-2 mb-2">
                <form hx-post="{{ route('demo.log') }}" hx-target="#comments-list" hx-swap="beforeend" hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}' hx-indicator="#form-indicator">
                    @csrf
                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="name">Name</label>
                        <input type="text" name="book" class="w-full border rounded p-1 text-sm" value="Anonymous">
                    </div>
                    
                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="message">Message</label>
                        <textarea 
                            x-model="message"
                            name="notes"
                            class="w-full border rounded p-1 text-sm"
                            rows="2"
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            <span x-text="message.length"></span>/100 characters
                        </p>
                    </div>
                    
                    <div class="flex justify-end">
                        <div id="form-indicator" class="htmx-indicator mr-2">
                            <span class="text-xs">Sending...</span>
                        </div>
                        <button 
                            type="submit"
                            class="bg-purple-500 hover:bg-purple-600 text-white py-1 px-2 rounded text-xs"
                        >
                            Submit
                        </button>
                    </div>
                </form>
            </div>
            
            <h3 class="text-sm font-medium mb-1">Comments</h3>
            <div id="comments-list" class="border rounded p-2 min-h-[60px]">
                <p class="text-gray-500 dark:text-gray-400 italic text-xs initial-message">Comments will appear here</p>
            </div>
        </div>
    </div>
</div>

<style>
    .htmx-indicator { display: none; }
    .htmx-request .htmx-indicator { display: block; }
    .htmx-request.htmx-indicator { display: block; }
</style>

<script>
    document.addEventListener('htmx:afterSwap', function(event) {
        // Remove the initial message when content is added
        if (event.target.id === 'comments-list') {
            const initialMessage = event.target.querySelector('.initial-message');
            if (initialMessage) {
                initialMessage.remove();
            }
        }
    });
</script>
@endsection
