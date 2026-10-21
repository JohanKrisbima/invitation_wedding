@props([
    'status' => 'pending',
    'type' => 'order', // 'order' or 'payment' or 'general'
])

@php
    $statusKey = strtolower($status);

    $colorClasses = match ($statusKey) {
        // Payment Statuses
        'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
        'unpaid' => 'bg-rose-50 text-rose-700 border-rose-200/80',
        
        // Order Statuses
        'pending' => 'bg-amber-50 text-amber-800 border-amber-200/80',
        'in_progress' => 'bg-sky-50 text-sky-700 border-sky-200/80',
        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
        'cancelled' => 'bg-stone-100 text-stone-600 border-stone-200',
        
        // General
        'published' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
        'draft' => 'bg-stone-100 text-stone-600 border-stone-200',
        'archived' => 'bg-purple-50 text-purple-700 border-purple-200/80',
        
        default => 'bg-stone-50 text-stone-600 border-stone-200',
    };

    $dotClasses = match ($statusKey) {
        'paid', 'completed', 'published' => 'bg-emerald-500',
        'unpaid', 'cancelled' => 'bg-rose-500',
        'pending' => 'bg-amber-500',
        'in_progress' => 'bg-sky-500',
        default => 'bg-stone-400',
    };

    $label = match ($statusKey) {
        'paid' => 'PAID',
        'unpaid' => 'UNPAID',
        'pending' => 'PENDING',
        'in_progress' => 'IN PROGRESS',
        'completed' => 'COMPLETED',
        'cancelled' => 'CANCELLED',
        'published' => 'PUBLISHED',
        'draft' => 'DRAFT',
        default => strtoupper(str_replace('_', ' ', $status)),
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold tracking-wide border shadow-xs transition-colors duration-150 {$colorClasses}"]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $dotClasses }}"></span>
    <span>{{ $slot->isEmpty() ? $label : $slot }}</span>
</span>
