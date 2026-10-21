@props([
    'label' => 'Statistik',
    'value' => '0',
    'subtext' => null,
    'icon' => 'orders',
    'color' => 'terracotta', // 'terracotta', 'amber', 'sky', 'emerald'
])

@php
    $colorMap = [
        'terracotta' => [
            'icon_bg' => 'bg-amber-100/70 text-[#C25E2E]',
            'accent' => 'text-[#C25E2E]',
        ],
        'amber' => [
            'icon_bg' => 'bg-amber-100/70 text-amber-700',
            'accent' => 'text-amber-700 font-medium',
        ],
        'sky' => [
            'icon_bg' => 'bg-sky-100/70 text-sky-700',
            'accent' => 'text-sky-700 font-medium',
        ],
        'emerald' => [
            'icon_bg' => 'bg-emerald-100/70 text-emerald-700',
            'accent' => 'text-emerald-700 font-medium',
        ],
    ];

    $currentColor = $colorMap[$color] ?? $colorMap['terracotta'];
@endphp

<div {{ $attributes->merge(['class' => 'group relative overflow-hidden bg-white/80 backdrop-blur-md rounded-3xl p-6 border border-stone-200/60 shadow-xs hover:shadow-md hover:border-stone-300/80 transition-all duration-300']) }}>
    <!-- Header: Label & Icon -->
    <div class="flex items-center justify-between gap-2 mb-3">
        <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">
            {{ $label }}
        </span>
        <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 {{ $currentColor['icon_bg'] }} transition-transform duration-300 group-hover:scale-105">
            @if ($icon === 'orders')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            @elseif ($icon === 'pending')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @elseif ($icon === 'progress')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            @elseif ($icon === 'completed')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            @endif
        </div>
    </div>

    <!-- Main Value -->
    <div class="text-3xl font-bold tracking-tight text-stone-900 mb-1">
        {{ $value }}
    </div>

    <!-- Subtext -->
    @if ($subtext)
        <p class="text-xs text-stone-500 {{ $currentColor['accent'] }}">
            {{ $subtext }}
        </p>
    @endif
</div>
