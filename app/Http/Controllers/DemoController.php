<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DemoController extends Controller
{
    /**
     * Show the HTMX and Alpine.js demo page.
     */
    public function index()
    {
        return view('demo');
    }

    /**
     * Get a random Bible verse (HTMX endpoint).
     */
    public function getRandomVerse()
    {
        // Simulate a short delay to demonstrate loading state
        sleep(1);
        
        // Sample verses for demo purposes
        $verses = [
            [
                'reference' => 'John 3:16',
                'text' => 'For God so loved the world that he gave his one and only Son, that whoever believes in him shall not perish but have eternal life.'
            ],
            [
                'reference' => 'Philippians 4:13',
                'text' => 'I can do all things through Christ who strengthens me.'
            ],
            [
                'reference' => 'Psalm 23:1',
                'text' => 'The Lord is my shepherd; I shall not want.'
            ],
            [
                'reference' => 'Proverbs 3:5-6',
                'text' => 'Trust in the LORD with all your heart and lean not on your own understanding; in all your ways submit to him, and he will make your paths straight.'
            ],
            [
                'reference' => 'Romans 8:28',
                'text' => 'And we know that in all things God works for the good of those who love him, who have been called according to his purpose.'
            ]
        ];
        
        // Select a random verse
        $verse = $verses[array_rand($verses)];
        
        // Return a partial view with just the verse content
        return view('partials.verse', ['verse' => $verse]);
    }

    /**
     * Handle comment submission (HTMX endpoint).
     */
    public function logReading(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'book' => 'required|string', // Using 'book' as the name field
            'notes' => 'nullable|string|max:500',
        ]);
        
        // In a real app, we would save this to the database
        // For demo purposes, we'll just return a partial view
        
        return view('partials.reading-log', [
            'comment' => [
                'name' => $validated['book'],
                'message' => $validated['notes'] ?? '',
                'date' => now()->format('M d, Y h:i A'),
            ]
        ]);
    }
}
