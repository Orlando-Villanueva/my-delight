<div class="border-b border-gray-200 dark:border-gray-700 py-2 last:border-b-0">
    <div class="flex justify-between items-start">
        <div>
            <p class="text-sm font-medium">{{ $comment['name'] }}</p>
            @if(!empty($comment['message']))
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $comment['message'] }}</p>
            @endif
        </div>
        <span class="text-xs text-gray-500 dark:text-gray-500">{{ $comment['date'] }}</span>
    </div>
</div>
