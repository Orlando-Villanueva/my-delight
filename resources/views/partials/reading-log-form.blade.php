{{-- Unified Reading Log Form Component --}}
{{-- This partial contains just the form logic and can be included in different layouts --}}

<x-bible.mobile-reading-form
    :books="$books"
    :allowYesterday="$allowYesterday ?? false"
    :hasReadYesterday="$hasReadYesterday ?? false"
    :hasReadToday="$hasReadToday ?? false"
    :currentStreak="$currentStreak ?? 0"
/>