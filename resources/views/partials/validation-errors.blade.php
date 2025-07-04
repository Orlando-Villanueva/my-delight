{{-- Validation Errors Display --}}
<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">
                Please fix the following errors:
            </h3>
            <div class="mt-2 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors as $field => $fieldErrors)
                        @foreach($fieldErrors as $error)
                            <li><strong>{{ ucfirst(str_replace('_', ' ', $field)) }}:</strong> {{ $error }}</li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div> 