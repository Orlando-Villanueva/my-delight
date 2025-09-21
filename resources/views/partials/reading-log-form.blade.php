<x-bible.reading-log-form
    :books="$books"
    :allowYesterday="$allowYesterday ?? false"
    :hasReadYesterday="$hasReadYesterday ?? false"
    :hasReadToday="$hasReadToday ?? false"
    :currentStreak="$currentStreak ?? 0"
/>