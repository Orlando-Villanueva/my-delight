<?php

namespace App\View\Components\Bible;

use App\Services\BookProgressService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class BookCompletionGrid extends Component
{
    public function __construct(
        private BookProgressService $bookProgressService
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        $user = Auth::user();

        $oldData = $this->bookProgressService->getTestamentProgress($user, 'Old');
        $newData = $this->bookProgressService->getTestamentProgress($user, 'New');

        // Always default to 'Old' as requested
        $testament = 'Old';

        // Convert Collections to arrays for Alpine.js
        $oldData['processed_books'] = $oldData['processed_books']->toArray();
        $newData['processed_books'] = $newData['processed_books']->toArray();

        return view('components.bible.book-completion-grid', [
            'oldData' => $oldData,
            'newData' => $newData,
            'testament' => $testament,
        ]);
    }
}
