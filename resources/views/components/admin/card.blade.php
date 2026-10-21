@props([
    'title' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white/80 backdrop-blur-md rounded-3xl border border-stone-200/60 shadow-xs p-6 transition-all duration-200']) }}>
    @if ($title || $subtitle || isset($headerActions))
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                @if ($title)
                    <h2 class="text-lg font-bold text-stone-900 tracking-tight">
                        {{ $title }}
                    </h2>
                @endif
                @if ($subtitle)
                    <p class="text-xs text-stone-500 mt-0.5">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>

            @if (isset($headerActions))
                <div class="flex items-center gap-3">
                    {{ $headerActions }}
                </div>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
