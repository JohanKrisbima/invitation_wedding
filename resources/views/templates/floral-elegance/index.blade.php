<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Page Title & Meta Information -->
    <title>The Wedding of {{ $couple?->partner_one_name ? ($couple->partner_one_name . ' & ' . $couple->partner_two_name) : 'Rian & Aulia' }}</title>
    <meta name="description" content="Pernikahan {{ $couple?->partner_one_name }} & {{ $couple?->partner_two_name }}. Merupakan kehormatan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.">
    <meta property="og:title" content="The Wedding of {{ $couple?->partner_one_name }} & {{ $couple?->partner_two_name }}">
    <meta property="og:description" content="Undangan Pernikahan Digital — {{ $couple?->partner_one_name }} & {{ $couple?->partner_two_name }}">

    <!-- Vite Bundles (Local Only — Zero CDN) -->
    @vite([
        'resources/css/app.css',
        'resources/css/templates/floral-elegance.css',
        'resources/js/templates/floral-elegance.js'
    ])
</head>
<body class="floral-theme-root overflow-x-hidden relative selection:bg-[#6e1c27]/20 selection:text-[#6e1c27]">

    @php
        $firstEvent = $events->first();
        $weddingDate = $firstEvent ? $firstEvent->start_at : null;
        $partnerOne = $couple?->partner_one_name ?? 'Rian Hidayat, S.T.';
        $partnerTwo = $couple?->partner_two_name ?? 'Aulia Rahma, S.Farm.';
        $shortOne = explode(',', $partnerOne)[0];
        $shortTwo = explode(',', $partnerTwo)[0];
        $initialOne = mb_substr($shortOne, 0, 1);
        $initialTwo = mb_substr($shortTwo, 0, 1);
    @endphp

    <!-- ================= FULLSCREEN FALLING PETALS CANVAS ================= -->
    <canvas id="fe-petals-canvas"></canvas>

    <!-- ================= FLOATING AUDIO TOGGLE (TOP-RIGHT) ================= -->
    <div class="fixed top-4 right-4 sm:top-6 sm:right-6 z-40">
        <button 
            type="button" 
            id="btn-audio-toggle" 
            title="Jeda Musik"
            class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#6e1c27] border-2 border-[#b98b52] shadow-xl flex items-center justify-center text-white hover:scale-105 active:scale-95 transition duration-200 group"
        >
            <svg id="audio-play-icon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <svg id="audio-pause-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
            </svg>
        </button>

        <audio id="bg-music-player" loop preload="auto">
            <source src="{{ asset('storage/music/pepujaning-atiku.mp3') }}" type="audio/mpeg">
        </audio>
    </div>

    <!-- ================= COVER SCREEN: TWO SPLIT DOORS ("PINTU TERBELAH") ================= -->
    <div id="invitation-cover-doors" class="fixed inset-0 z-50 overflow-hidden flex transition-opacity duration-500">
        <!-- Left Door -->
        <div id="cover-door-left" class="fe-door-left w-1/2 h-full bg-[#fdf0e6] bg-kawung relative border-r border-[#b98b52]/40 flex items-center justify-end">
            <div class="absolute inset-4 sm:inset-8 border-2 border-[#6e1c27]/25 rounded-l-3xl pointer-events-none"></div>
            <div class="w-48 h-80 border-r-2 border-[#b98b52]/20 rounded-r-full opacity-40"></div>
        </div>

        <!-- Right Door -->
        <div id="cover-door-right" class="fe-door-right w-1/2 h-full bg-[#fdf0e6] bg-kawung relative border-l border-[#b98b52]/40 flex items-center justify-start">
            <div class="absolute inset-4 sm:inset-8 border-2 border-[#6e1c27]/25 rounded-r-3xl pointer-events-none"></div>
            <div class="w-48 h-80 border-l-2 border-[#b98b52]/20 rounded-l-full opacity-40"></div>
        </div>

        <!-- Center Card (On top of doors, centered) -->
        <div id="cover-center-content" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-11/12 max-w-sm p-6 sm:p-8 rounded-[32px] bg-white/95 border-2 border-[#6e1c27] shadow-2xl text-center z-10 transition-all duration-700 overflow-hidden">
            <!-- Botanical Vine in Cover Card Corner -->
            <x-floral-corner-vine position="tl" />
            <x-floral-corner-vine position="br" />

            <!-- Script Monogram -->
            <div class="font-pinyon text-5xl sm:text-6xl text-[#6e1c27] mb-2 leading-none">
                R &amp; A
            </div>

            <span class="text-[10px] sm:text-[11px] font-bold tracking-[0.25em] text-[#6e1c27] uppercase block mb-1">
                WALIMATUL 'URS
            </span>
            <p class="text-xs text-stone-500 tracking-wider font-cormorant italic mb-4">
                The Wedding Celebration of
            </p>

            <h1 class="text-2xl sm:text-3xl font-bold font-playfair text-[#6e1c27] tracking-tight leading-snug mb-1">
                {{ $shortOne }}
            </h1>
            <div class="text-[#b98b52] font-pinyon text-2xl my-0.5">&amp;</div>
            <h1 class="text-2xl sm:text-3xl font-bold font-playfair text-[#6e1c27] tracking-tight leading-snug mb-5">
                {{ $shortTwo }}
            </h1>

            <div class="w-16 h-px bg-gradient-to-r from-transparent via-[#b98b52] to-transparent mx-auto mb-4"></div>

            <!-- Recipient Box -->
            <div class="p-3.5 rounded-2xl bg-[#fdf0e6]/70 border border-[#6e1c27]/20 mb-5 shadow-xs">
                <p class="text-[10px] text-stone-500 mb-0.5">Kepada Yth. Bapak/Ibu/Saudara/i:</p>
                <p class="text-sm sm:text-base font-bold text-stone-900 tracking-tight">
                    {{ $guestName }}
                </p>
                @if ($guest && $guest->category)
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[9px] font-semibold bg-[#6f8260]/15 text-[#6f8260]">
                        Tamu {{ ucfirst($guest->category) }}
                    </span>
                @endif
            </div>

            <!-- Pulsing Open Invitation Button -->
            <button 
                type="button" 
                id="btn-open-invitation"
                class="w-full py-3 px-6 rounded-full bg-[#6e1c27] hover:bg-[#52121b] text-white text-xs font-bold tracking-wider uppercase shadow-xl shadow-[#6e1c27]/25 transition duration-300 flex items-center justify-center gap-2 group animate-pulse hover:animate-none"
            >
                <svg class="w-3.5 h-3.5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5m0 0l-2.25 1.5-2.25-1.5m4.5 0L12 9l-4.5 5.5" />
                </svg>
                <span>Buka Undangan</span>
            </button>
        </div>
    </div>

    <!-- ================= MAIN LAYOUT (SPLIT-SCREEN ON DESKTOP, FLOW ON MOBILE) ================= -->
    <div class="fe-split-layout">

        <!-- ================= LEFT COLUMN: HERO FULL-BLEED (DESKTOP ONLY — HIDDEN ON MOBILE) ================= -->
        <div id="left-hero-container">
            <!-- Hero Photo Background Layer with Slow Zoom -->
            <div 
                id="left-hero-bg" 
                class="fe-hero-zoom"
                style="background-image: url('{{ $galleryPhotos->first() ? asset('storage/' . $galleryPhotos->first()->photo_path) : 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1600&q=80' }}');"
            ></div>

            <!-- Deep Wine & Soft Dark Vignette Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#36090f]/90 via-[#6e1c27]/60 to-[#52121b]/40"></div>

            <!-- Subtle Batik Kawung Overlay Texture -->
            <div class="absolute inset-0 bg-kawung opacity-15 pointer-events-none"></div>

            <!-- Centered Text Content -->
            <div id="left-hero-content" class="relative z-10 text-white max-w-lg transition-transform duration-200 ease-out">
                <!-- Botanical Floral Crest SVG -->
                <div class="w-14 h-14 mx-auto mb-3 text-[#e8d5be] opacity-90">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 4c-1.1 0-2 .9-2 2 0 1.9 2 4.5 2 4.5s2-2.6 2-4.5c0-1.1-.9-2-2-2zm0 16c1.1 0 2-.9 2-2 0-1.9-2-4.5-2-4.5s-2 2.6-2 4.5c0 1.1.9 2 2 2zM4 12c0 1.1.9 2 2 2 1.9 0 4.5-2 4.5-2S7.9 10 6 10c-1.1 0-2 .9-2 2zm16 0c0-1.1-.9-2-2-2-1.9 0-4.5 2-4.5 2s2.6 2 4.5 2c1.1 0 2-.9 2-2z"/>
                    </svg>
                </div>

                <span class="text-xs font-semibold tracking-[0.3em] text-[#e8d5be] uppercase block mb-2 font-cormorant">
                    THE WEDDING OF
                </span>

                <h1 class="text-4xl sm:text-5xl font-bold font-playfair tracking-tight mb-2 fe-reveal-text">
                    {{ $shortOne }} &amp; {{ $shortTwo }}
                </h1>

                <p class="text-xs sm:text-sm font-cormorant tracking-[0.25em] text-[#f4d6cd] uppercase mt-3">
                    {{ $weddingDate ? $weddingDate->format('d  .  m  .  Y') : '16 . 11 . 2026' }}
                </p>
            </div>
        </div>

        <!-- ================= RIGHT COLUMN: SCROLLING PANEL ================= -->
        <div 
            id="right-scroll-panel" 
            class="fe-panel-scroll bg-kawung p-3 sm:p-5 lg:p-6 space-y-6"
        >

            <!-- SECTION 1: SAMPUL LENGKUNG (FLORAL HERITAGE ARCH - MATCHING USER REFERENCE) -->
            <section id="section-cover" class="p-6 sm:p-8 text-center flex flex-col justify-between">
                <div class="fe-arch-inner-wrapper flex flex-col justify-between items-center h-full w-full max-w-[290px] sm:max-w-xs mx-auto z-10">
                    <!-- Top Monogram & Eyebrow -->
                    <div class="pt-8 sm:pt-12 space-y-2">
                        <!-- Intertwined Monogram -->
                        <div class="fe-monogram-intertwined mb-1">
                            <span class="fe-mono-first">{{ $initialOne }}</span>
                            <span class="fe-mono-amp">&amp;</span>
                            <span class="fe-mono-second">{{ $initialTwo }}</span>
                        </div>
                        <span class="text-[10px] sm:text-[11px] font-bold tracking-[0.28em] text-[#7d222e] uppercase block font-cormorant">
                            THE WEDDING OF
                        </span>
                    </div>

                    <!-- Couple Heading & Date (Classical Elegant Serif matching Reference Image) -->
                    <div class="my-auto py-2 space-y-2">
                        <h2 class="text-2xl sm:text-3xl font-bold font-playfair text-[#5a141d] tracking-[0.14em] uppercase leading-snug">
                            {{ $shortOne }} &amp; {{ $shortTwo }}
                        </h2>
                        <p class="text-xs sm:text-sm font-semibold tracking-[0.25em] text-[#6e1c27] font-cormorant">
                            {{ $weddingDate ? $weddingDate->format('d  .  m  .  Y') : '18  .  11  .  2026' }}
                        </p>
                    </div>

                    <!-- Scroll Down Indicator (Positioned above Borobudur stupas) -->
                    <div class="pb-10 sm:pb-12 flex flex-col items-center">
                        <div class="w-[18px] h-[32px] rounded-full border-[1.5px] border-[#5a141d]/75 flex items-start justify-center p-1 bg-white/25 backdrop-blur-xs shadow-xs">
                            <div class="w-1 h-2.5 rounded-full bg-[#5a141d] fe-scroll-dot-anim"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 2: AYAT SUCI (MATCHING IMAGE 2) -->
            <section id="section-quote" class="fe-page-card p-6 sm:p-8 text-center space-y-3">
                <x-floral-corner-vine position="tl" />
                <x-floral-corner-vine position="br" />

                <!-- Heart Icon Pill -->
                <div>
                    <div class="fe-pill-badge w-fit mx-auto px-4 py-1.5">
                        <svg class="w-4 h-4 text-[#6e1c27]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </div>
                </div>

                <!-- Title Pill: WITH LOVE -->
                <div>
                    <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                        <span class="text-xs font-semibold tracking-[0.25em] text-[#6e1c27] uppercase font-cormorant">
                            WITH LOVE
                        </span>
                    </div>
                </div>

                <!-- Inner White Card: Ar-Rum 21 Translation -->
                <div class="bg-white/95 rounded-2xl p-5 border border-[#6e1c27]/15 shadow-xs max-w-sm mx-auto">
                    <p class="font-cormorant italic text-xs sm:text-sm text-stone-700 leading-relaxed">
                        "Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang."
                    </p>
                </div>

                <!-- Reference Pill: QS. Ar-Rum : 21 -->
                <div>
                    <div class="fe-pill-badge w-fit mx-auto px-5 py-1.5">
                        <span class="text-[11px] font-bold tracking-wider text-[#6e1c27] uppercase font-cormorant">
                            QS. Ar-Rum : 21
                        </span>
                    </div>
                </div>
            </section>

            <!-- SECTION 3: BRIDE & GROOM PROFILE (MATCHING IMAGE 3) -->
            <section id="section-couple" class="fe-page-card p-6 sm:p-8 text-center space-y-4">
                <x-floral-corner-vine position="tl" />
                <x-floral-corner-vine position="br" />

                <!-- Top Pill Badge: BRIDE & GROOM -->
                <div>
                    <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                        <span class="text-xs font-semibold tracking-[0.25em] text-[#6e1c27] uppercase font-cormorant">
                            BRIDE &amp; GROOM
                        </span>
                    </div>
                </div>

                <!-- Intro Invitation Box -->
                <div class="bg-white/95 rounded-2xl p-4 border border-[#6e1c27]/15 shadow-xs max-w-sm mx-auto">
                    <p class="font-cormorant text-xs text-stone-700 leading-relaxed">
                        Tanpa mengurangi rasa hormat, kami mengundang Bapak/Ibu/Saudara/i serta kerabat sekalian untuk menghadiri acara pernikahan kami.
                    </p>
                </div>

                <!-- Groom Container -->
                <div class="bg-white/95 rounded-2xl p-5 border border-[#6e1c27]/15 shadow-xs max-w-sm mx-auto space-y-3">
                    <!-- Vertical Oval Frame with soft rose blush gradient -->
                    <div class="fe-oval-tilt w-32 h-44 sm:w-36 sm:h-48 mx-auto rounded-full p-1 border-2 border-[#d9a5a0] bg-gradient-to-b from-[#fdf0e6] to-[#f4d6cd] flex items-center justify-center overflow-hidden shadow-sm">
                        @if ($couple?->partner_one_photo)
                            <img src="{{ asset('storage/' . $couple->partner_one_photo) }}" alt="{{ $partnerOne }}" class="w-full h-full object-cover rounded-full">
                        @else
                            <span class="font-pinyon text-6xl text-[#6e1c27] select-none">
                                {{ substr($shortOne, 0, 1) }}
                            </span>
                        @endif
                    </div>

                    <!-- Script Name -->
                    <h3 class="font-pinyon text-4xl text-[#6e1c27] leading-none pt-1">
                        {{ $shortOne }}
                    </h3>

                    <!-- Full Name -->
                    <p class="font-playfair font-bold text-sm sm:text-base text-stone-900">
                        {{ $partnerOne }}
                    </p>

                    <!-- Parents Info -->
                    <p class="text-[11px] text-stone-600 font-cormorant leading-relaxed">
                        Putra dari pasangan <br>
                        <span class="font-bold text-stone-900">Bpk. {{ $couple?->partner_one_father ?? 'H. Suryadi Hidayat' }}</span> &amp; <br>
                        <span class="font-bold text-stone-900">Ibu {{ $couple?->partner_one_mother ?? 'Hj. Ratna Sari' }}</span>
                    </p>
                </div>

                <!-- Bride Container -->
                <div class="bg-white/95 rounded-2xl p-5 border border-[#6e1c27]/15 shadow-xs max-w-sm mx-auto space-y-3 mt-4">
                    <!-- Vertical Oval Frame -->
                    <div class="fe-oval-tilt w-32 h-44 sm:w-36 sm:h-48 mx-auto rounded-full p-1 border-2 border-[#d9a5a0] bg-gradient-to-b from-[#fdf0e6] to-[#f4d6cd] flex items-center justify-center overflow-hidden shadow-sm">
                        @if ($couple?->partner_two_photo)
                            <img src="{{ asset('storage/' . $couple->partner_two_photo) }}" alt="{{ $partnerTwo }}" class="w-full h-full object-cover rounded-full">
                        @else
                            <span class="font-pinyon text-6xl text-[#6e1c27] select-none">
                                {{ substr($shortTwo, 0, 1) }}
                            </span>
                        @endif
                    </div>

                    <!-- Script Name -->
                    <h3 class="font-pinyon text-4xl text-[#6e1c27] leading-none pt-1">
                        {{ $shortTwo }}
                    </h3>

                    <!-- Full Name -->
                    <p class="font-playfair font-bold text-sm sm:text-base text-stone-900">
                        {{ $partnerTwo }}
                    </p>

                    <!-- Parents Info -->
                    <p class="text-[11px] text-stone-600 font-cormorant leading-relaxed">
                        Putri dari pasangan <br>
                        <span class="font-bold text-stone-900">Bpk. {{ $couple?->partner_two_father ?? 'Ir. Bambang Wijaya' }}</span> &amp; <br>
                        <span class="font-bold text-stone-900">Ibu {{ $couple?->partner_two_mother ?? 'Siti Nurhaliza' }}</span>
                    </p>
                </div>
            </section>

            <!-- SECTION 4: RANGKAIAN ACARA (MATCHING IMAGE 4) -->
            <section id="section-events" class="fe-page-card p-4 sm:p-7 space-y-4">
                <x-floral-corner-vine position="tl" />
                <x-floral-corner-vine position="br" />

                <!-- Top Pills -->
                <div class="text-center space-y-2">
                    <div>
                        <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                            <span class="text-xs font-semibold tracking-[0.25em] text-[#6e1c27] uppercase font-cormorant">
                                RANGKAIAN ACARA
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                            <span class="text-xs font-semibold tracking-[0.2em] text-[#6e1c27] uppercase font-cormorant">
                                JADWAL PERNIKAHAN
                            </span>
                        </div>
                    </div>
                </div>

                @forelse ($events as $event)
                    <!-- Event Card -->
                    <div class="fe-event-card bg-white/95 rounded-2xl p-3.5 sm:p-5 border border-[#6e1c27]/20 shadow-xs space-y-2.5 w-full overflow-hidden">
                        <div class="flex items-center justify-between gap-1.5 sm:gap-2 min-w-0">
                            <span class="fe-event-badge">
                                {{ $event->event_type }}
                            </span>
                            @if ($event->start_at)
                                <div class="fe-event-date">
                                    <span class="fe-event-date-day">
                                        {{ $event->start_at->locale('id')->translatedFormat('l') }},
                                    </span>
                                    <span class="fe-event-date-full">
                                        {{ $event->start_at->locale('id')->translatedFormat('d F Y') }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Time -->
                        <div class="flex items-center gap-2 text-xs font-bold text-stone-900 pt-0.5 min-w-0">
                            <svg class="w-3.5 h-3.5 text-[#6e1c27] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="truncate">
                                {{ $event->start_at ? $event->start_at->format('H:i') : '--:--' }} &ndash; 
                                {{ $event->end_at ? $event->end_at->format('H:i') . ' WIB' : 'Selesai' }}
                            </span>
                        </div>

                        <!-- Venue -->
                        <div class="flex items-start gap-2 text-xs text-stone-600 min-w-0">
                            <svg class="w-3.5 h-3.5 text-[#6e1c27] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div class="min-w-0 flex-1">
                                <span class="font-bold text-[#6e1c27] block text-xs sm:text-sm font-playfair break-words">{{ $event->venue_name }}</span>
                                <span class="text-[11px] text-stone-500 font-cormorant block break-words leading-relaxed">{{ $event->address }}</span>
                            </div>
                        </div>

                        <!-- Dashed Separator -->
                        <div class="border-t border-dashed border-[#6e1c27]/20 pt-2.5"></div>

                        <!-- Pill Action Buttons -->
                        <div class="flex gap-2">
                            @if ($event->maps_url)
                                <a 
                                    href="{{ $event->maps_url }}" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="flex-1 py-1.5 sm:py-2 px-2 sm:px-3 rounded-full bg-[#6e1c27] hover:bg-[#52121b] text-white text-[10px] sm:text-[11px] font-bold tracking-wider uppercase flex items-center justify-center transition shadow-xs text-center font-cormorant min-w-0"
                                >
                                    BUKA MAPS
                                </a>
                            @endif

                            @if ($event->start_at)
                                @php
                                    $calStart = $event->start_at->format('Ymd\THis');
                                    $calEnd = $event->end_at ? $event->end_at->format('Ymd\THis') : $event->start_at->copy()->addHours(2)->format('Ymd\THis');
                                    $calTitle = urlencode($event->event_type . ' — ' . $shortOne . ' & ' . $shortTwo);
                                    $calLocation = urlencode($event->venue_name . ', ' . $event->address);
                                    $googleCal = "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$calTitle}&dates={$calStart}/{$calEnd}&location={$calLocation}";
                                @endphp
                                <a 
                                    href="{{ $googleCal }}" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="flex-1 py-1.5 sm:py-2 px-2 sm:px-3 rounded-full border border-[#6e1c27] text-[#6e1c27] hover:bg-[#6e1c27]/10 text-[10px] sm:text-[11px] font-bold tracking-wider uppercase flex items-center justify-center transition text-center font-cormorant min-w-0"
                                >
                                    SIMPAN KALENDER
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-xs text-stone-500 py-3">Jadwal acara pernikahan belum diatur.</p>
                @endforelse
            </section>

            <!-- SECTION 5: HITUNG MUNDUR LIVE COUNTDOWN (MATCHING IMAGE 5) -->
            @if ($weddingDate)
                <section id="section-countdown" class="fe-page-card p-6 sm:p-8 text-center space-y-4">
                    <x-floral-corner-vine position="tl" />
                    <x-floral-corner-vine position="br" />

                    <!-- Top Pills -->
                    <div class="space-y-2">
                        <div>
                            <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                                <span class="text-xs font-semibold tracking-[0.25em] text-[#6e1c27] uppercase font-cormorant">
                                    MENGHITUNG HARI
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                                <span class="text-xs font-semibold tracking-[0.2em] text-[#6e1c27] uppercase font-cormorant">
                                    MENUJU HARI BAHAGIA
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- 4 Tile Square Countdown Box -->
                    <div class="bg-white/95 rounded-2xl p-4 sm:p-5 border border-[#6e1c27]/20 shadow-xs max-w-sm mx-auto">
                        <div id="countdown-container" data-wedding-date="{{ $weddingDate->format('Y-m-d\TH:i:s+07:00') }}" class="grid grid-cols-4 gap-2">
                            <!-- Days -->
                            <div class="p-2 sm:p-3 rounded-2xl bg-white border border-[#6e1c27]/20 text-center shadow-2xs">
                                <span id="fe-count-days" class="text-2xl sm:text-3xl font-bold font-playfair text-[#6e1c27] block">00</span>
                                <span class="text-[10px] sm:text-xs text-stone-500 font-semibold font-cormorant">Hari</span>
                            </div>
                            <!-- Hours -->
                            <div class="p-2 sm:p-3 rounded-2xl bg-white border border-[#6e1c27]/20 text-center shadow-2xs">
                                <span id="fe-count-hours" class="text-2xl sm:text-3xl font-bold font-playfair text-[#6e1c27] block">00</span>
                                <span class="text-[10px] sm:text-xs text-stone-500 font-semibold font-cormorant">Jam</span>
                            </div>
                            <!-- Minutes -->
                            <div class="p-2 sm:p-3 rounded-2xl bg-white border border-[#6e1c27]/20 text-center shadow-2xs">
                                <span id="fe-count-minutes" class="text-2xl sm:text-3xl font-bold font-playfair text-[#6e1c27] block">00</span>
                                <span class="text-[10px] sm:text-xs text-stone-500 font-semibold font-cormorant">Menit</span>
                            </div>
                            <!-- Seconds -->
                            <div class="p-2 sm:p-3 rounded-2xl bg-white border border-[#6e1c27]/20 text-center shadow-2xs">
                                <span id="fe-count-seconds" class="text-2xl sm:text-3xl font-bold font-playfair text-[#6e1c27] block">00</span>
                                <span class="text-[10px] sm:text-xs text-stone-500 font-semibold font-cormorant">Detik</span>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            <!-- SECTION 6: KISAH PERJALANAN CINTA (ACCORDION TIMELINE) -->
            <section id="section-story" class="fe-page-card p-6 sm:p-8 space-y-3">
                <x-floral-corner-vine position="tl" />
                <x-floral-corner-vine position="br" />

                <!-- Top Pills -->
                <div class="text-center space-y-2 mb-2">
                    <div>
                        <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                            <span class="text-xs font-semibold tracking-[0.25em] text-[#6e1c27] uppercase font-cormorant">
                                OUR JOURNEY
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                            <span class="text-xs font-semibold tracking-[0.2em] text-[#6e1c27] uppercase font-cormorant">
                                KISAH CINTA KAMI
                            </span>
                        </div>
                    </div>
                </div>

                <div class="space-y-2.5 max-w-sm mx-auto">
                    <!-- Accordion 1 -->
                    <div class="fe-accordion-item rounded-2xl border border-[#6e1c27]/20 bg-white/95 overflow-hidden fe-accordion-active transition shadow-xs">
                        <button type="button" class="fe-accordion-header w-full p-3.5 flex items-center justify-between text-left">
                            <div class="flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-full bg-[#6e1c27] text-white flex items-center justify-center text-xs font-bold">1</span>
                                <span class="font-bold text-xs sm:text-sm text-[#6e1c27] font-playfair">Awal Bertemu</span>
                            </div>
                            <svg class="fe-accordion-icon w-3.5 h-3.5 text-[#6e1c27] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="fe-accordion-content px-3.5 pb-3.5 text-xs text-stone-600 font-cormorant leading-relaxed">
                            {{ $couple?->story ?? 'Pertemuan tak sengaja di sebuah kedai kopi di Bandung berbuah percakapan hangat yang membuka awal cerita indah kami berdua.' }}
                        </div>
                    </div>

                    <!-- Accordion 2 -->
                    <div class="fe-accordion-item rounded-2xl border border-[#6e1c27]/20 bg-white/95 overflow-hidden transition shadow-xs">
                        <button type="button" class="fe-accordion-header w-full p-3.5 flex items-center justify-between text-left">
                            <div class="flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-full bg-[#b98b52] text-white flex items-center justify-center text-xs font-bold">2</span>
                                <span class="font-bold text-xs sm:text-sm text-[#6e1c27] font-playfair">Mengikat Janji</span>
                            </div>
                            <svg class="fe-accordion-icon w-3.5 h-3.5 text-[#6e1c27] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="fe-accordion-content px-3.5 pb-3.5 text-xs text-stone-600 font-cormorant leading-relaxed">
                            Setelah melewati berbagai suka dan duka bersama, dengan restu kedua orang tua kami memutuskan untuk mengikat komitmen suci menuju pelaminan.
                        </div>
                    </div>

                    <!-- Accordion 3 -->
                    <div class="fe-accordion-item rounded-2xl border border-[#6e1c27]/20 bg-white/95 overflow-hidden transition shadow-xs">
                        <button type="button" class="fe-accordion-header w-full p-3.5 flex items-center justify-between text-left">
                            <div class="flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-full bg-[#6f8260] text-white flex items-center justify-center text-xs font-bold">3</span>
                                <span class="font-bold text-xs sm:text-sm text-[#6e1c27] font-playfair">Menuju Pelaminan</span>
                            </div>
                            <svg class="fe-accordion-icon w-3.5 h-3.5 text-[#6e1c27] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="fe-accordion-content px-3.5 pb-3.5 text-xs text-stone-600 font-cormorant leading-relaxed">
                            Kini kami melangkah dengan penuh rasa syukur ke hadapan Sang Pencipta untuk menyatukan dua hati dan keluarga dalam ikatan pernikahan yang suci.
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 7: GALERI FOTO PREWEDDING -->
            @if ($galleryPhotos->isNotEmpty())
                <section id="section-gallery" class="fe-page-card p-6 sm:p-8 space-y-4">
                    <x-floral-corner-vine position="tl" />
                    <x-floral-corner-vine position="br" />

                    <!-- Top Pills -->
                    <div class="text-center space-y-2 mb-2">
                        <div>
                            <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                                <span class="text-xs font-semibold tracking-[0.25em] text-[#6e1c27] uppercase font-cormorant">
                                    SWEET MEMORIES
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                                <span class="text-xs font-semibold tracking-[0.2em] text-[#6e1c27] uppercase font-cormorant">
                                    GALERI PREWEDDING
                                </span>
                            </div>
                        </div>
                    </div>

                    <div id="gallery-grid" class="grid grid-cols-2 gap-2.5 max-w-sm mx-auto">
                        @foreach ($galleryPhotos as $photo)
                            <div 
                                class="fe-gallery-item group relative aspect-4/5 rounded-2xl overflow-hidden shadow-xs cursor-pointer border border-[#6e1c27]/20 bg-stone-100"
                                data-full-img="{{ asset('storage/' . $photo->photo_path) }}"
                                data-caption="{{ $photo->caption }}"
                            >
                                <img 
                                    src="{{ asset('storage/' . $photo->photo_path) }}" 
                                    alt="{{ $photo->caption ?? 'Foto Prewedding' }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    loading="lazy"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-[#6e1c27]/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-2">
                                    <span class="text-[9px] text-white font-medium truncate">
                                        {{ $photo->caption ?? 'Lihat Foto' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Gallery Pagination Controls -->
                    <div id="gallery-pagination" class="hidden max-w-sm mx-auto items-center justify-between pt-2 px-1 text-xs">
                        <button 
                            type="button" 
                            data-page-action="prev"
                            class="py-1 px-3 rounded-full border border-[#6e1c27]/30 text-[#6e1c27] hover:bg-[#6e1c27]/10 disabled:opacity-30 disabled:pointer-events-none transition text-[11px] font-bold font-cormorant flex items-center gap-1 shadow-2xs"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Sebelumnya</span>
                        </button>

                        <span data-page-info class="text-[11px] text-stone-500 font-semibold font-cormorant">
                            Halaman <strong class="text-stone-800 font-bold">1</strong> dari <strong class="text-stone-800 font-bold">1</strong>
                        </span>

                        <button 
                            type="button" 
                            data-page-action="next"
                            class="py-1 px-3 rounded-full border border-[#6e1c27]/30 text-[#6e1c27] hover:bg-[#6e1c27]/10 disabled:opacity-30 disabled:pointer-events-none transition text-[11px] font-bold font-cormorant flex items-center gap-1 shadow-2xs"
                        >
                            <span>Selanjutnya</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </section>
            @endif

            <!-- SECTION 8: KONFIRMASI KEHADIRAN (RSVP ONLINE) -->
            <section id="section-rsvp" class="fe-page-card p-6 sm:p-8 space-y-4">
                <x-floral-corner-vine position="tl" />
                <x-floral-corner-vine position="br" />

                <!-- Top Pills -->
                <div class="text-center space-y-2 mb-2">
                    <div>
                        <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                            <span class="text-xs font-semibold tracking-[0.25em] text-[#6e1c27] uppercase font-cormorant">
                                KONFIRMASI
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                            <span class="text-xs font-semibold tracking-[0.2em] text-[#6e1c27] uppercase font-cormorant">
                                KEHADIRAN TAMU (RSVP)
                            </span>
                        </div>
                    </div>
                </div>

                <div id="rsvp-success-notice" class="hidden mb-3 p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold text-center"></div>

                <form id="rsvp-form" action="{{ route('invitation.rsvp', $invitation->slug) }}" method="POST" class="space-y-3 max-w-sm mx-auto">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-semibold text-stone-700 mb-1">Nama Lengkap</label>
                        <input 
                            type="text" 
                            name="guest_name" 
                            value="{{ $guestName !== 'Tamu Undangan' ? $guestName : '' }}"
                            required 
                            placeholder="Nama Lengkap"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#6e1c27]/25 bg-white/95 text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-[#6e1c27]/30 transition shadow-2xs"
                        >
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-stone-700 mb-1">Konfirmasi Kehadiran</label>
                        <div class="grid grid-cols-3 gap-1.5">
                            <label class="cursor-pointer">
                                <input type="radio" name="attendance_status" value="attending" class="sr-only peer" checked>
                                <div class="py-2 px-2 rounded-xl border border-[#6e1c27]/25 bg-white/80 text-center text-[11px] font-semibold text-stone-700 peer-checked:bg-[#6e1c27] peer-checked:text-white transition">
                                    Hadir
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="attendance_status" value="tentative" class="sr-only peer">
                                <div class="py-2 px-2 rounded-xl border border-[#6e1c27]/25 bg-white/80 text-center text-[11px] font-semibold text-stone-700 peer-checked:bg-[#b98b52] peer-checked:text-white transition">
                                    Ragu-ragu
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="attendance_status" value="declined" class="sr-only peer">
                                <div class="py-2 px-2 rounded-xl border border-[#6e1c27]/25 bg-white/80 text-center text-[11px] font-semibold text-stone-700 peer-checked:bg-stone-600 peer-checked:text-white transition">
                                    Tidak Hadir
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-stone-700 mb-1">Jumlah Tamu</label>
                        <select 
                            name="guest_count"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#6e1c27]/25 bg-white/95 text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-[#6e1c27]/30 transition shadow-2xs"
                        >
                            <option value="1">1 Orang</option>
                            <option value="2">2 Orang</option>
                            <option value="3">3 Orang</option>
                            <option value="4">4 Orang</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-stone-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <input 
                            type="text" 
                            name="note" 
                            placeholder="Contoh: Datang bersama keluarga"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#6e1c27]/25 bg-white/95 text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-[#6e1c27]/30 transition shadow-2xs"
                        >
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-3 px-6 rounded-full bg-[#6e1c27] hover:bg-[#52121b] text-white text-[11px] font-bold tracking-wider uppercase shadow-md shadow-[#6e1c27]/25 transition duration-200"
                    >
                        Kirim Konfirmasi
                    </button>
                </form>

                <!-- Divider -->
                <div class="border-t border-dashed border-[#6e1c27]/20 pt-4 max-w-sm mx-auto"></div>

                <!-- RSVP Attendance Header & Stats -->
                <div class="max-w-sm mx-auto flex items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#6e1c27] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="font-bold text-xs sm:text-sm text-stone-900 font-playfair tracking-tight">Daftar Kehadiran</span>
                    </div>
                    @php
                        $attendingRsvps = isset($rsvps) ? $rsvps->where('attendance_status', 'attending') : collect();
                        $attendingCount = $attendingRsvps->count();
                        $totalPax = $attendingRsvps->sum('guest_count') ?: $attendingCount;
                    @endphp
                    <span id="rsvp-attending-counter" class="text-[10px] font-bold text-[#6e1c27] bg-[#6e1c27]/10 px-2.5 py-0.5 rounded-full font-cormorant tracking-wide">
                        {{ $attendingCount }} Tamu Hadir
                    </span>
                </div>

                <!-- Live Stream RSVPs -->
                <div id="rsvp-stream-list" class="space-y-2.5 max-h-72 overflow-y-auto pr-1 max-w-sm mx-auto">
                    @forelse ($rsvps ?? [] as $item)
                        <div class="p-3 rounded-xl bg-white/95 border border-[#6e1c27]/15 shadow-2xs transition" data-guest-name="{{ strtolower($item->guest_name) }}" data-attendance-status="{{ $item->attendance_status }}">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-6 h-6 rounded-full bg-[#6e1c27]/10 text-[#6e1c27] font-bold text-[10px] flex items-center justify-center font-playfair shrink-0">
                                        {{ strtoupper(substr($item->guest_name, 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-xs text-stone-900 font-playfair truncate">{{ $item->guest_name }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    @if ($item->attendance_status === 'attending')
                                        <span class="inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Hadir ({{ $item->guest_count ?? 1 }})
                                        </span>
                                    @elseif ($item->attendance_status === 'tentative')
                                        <span class="inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Ragu-ragu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full bg-stone-100 border border-stone-200 text-stone-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-stone-400"></span>
                                            Tidak Hadir
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center justify-between gap-2 pl-8">
                                @if ($item->note)
                                    <p class="text-xs text-stone-600 font-cormorant leading-relaxed italic truncate">
                                        &ldquo;{{ $item->note }}&rdquo;
                                    </p>
                                @else
                                    <span class="text-[10px] text-stone-400 font-cormorant">Telah konfirmasi</span>
                                @endif
                                <span class="text-[9px] text-stone-400 font-medium shrink-0">
                                    {{ $item->created_at ? $item->created_at->diffForHumans() : 'Baru saja' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div id="empty-rsvp-notice" class="py-5 text-center text-xs text-stone-400 font-cormorant">
                            Belum ada konfirmasi kehadiran. Jadilah yang pertama mengonfirmasi!
                        </div>
                    @endforelse
                </div>

                <!-- RSVP Pagination Controls -->
                <div id="rsvp-pagination" class="hidden max-w-sm mx-auto items-center justify-between pt-2 px-1 text-xs">
                    <button 
                        type="button" 
                        data-page-action="prev"
                        class="py-1 px-3 rounded-full border border-[#6e1c27]/30 text-[#6e1c27] hover:bg-[#6e1c27]/10 disabled:opacity-30 disabled:pointer-events-none transition text-[11px] font-bold font-cormorant flex items-center gap-1 shadow-2xs"
                    >
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>Sebelumnya</span>
                    </button>

                    <span data-page-info class="text-[11px] text-stone-500 font-semibold font-cormorant">
                        Halaman <strong class="text-stone-800 font-bold">1</strong> dari <strong class="text-stone-800 font-bold">1</strong>
                    </span>

                    <button 
                        type="button" 
                        data-page-action="next"
                        class="py-1 px-3 rounded-full border border-[#6e1c27]/30 text-[#6e1c27] hover:bg-[#6e1c27]/10 disabled:opacity-30 disabled:pointer-events-none transition text-[11px] font-bold font-cormorant flex items-center gap-1 shadow-2xs"
                    >
                        <span>Selanjutnya</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </section>

            <!-- SECTION 9: BUKU TAMU & UCAPAN DOA -->
            <section id="section-guestbook" class="fe-page-card p-6 sm:p-8 space-y-4">
                <x-floral-corner-vine position="tl" />
                <x-floral-corner-vine position="br" />

                <!-- Top Pills -->
                <div class="text-center space-y-2 mb-2">
                    <div>
                        <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                            <span class="text-xs font-semibold tracking-[0.25em] text-[#6e1c27] uppercase font-cormorant">
                                PRAYERS &amp; WISHES
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                            <span class="text-xs font-semibold tracking-[0.2em] text-[#6e1c27] uppercase font-cormorant">
                                BUKU TAMU &amp; DOA RESTU
                            </span>
                        </div>
                    </div>
                </div>

                <form id="guestbook-form" action="{{ route('invitation.messages', $invitation->slug) }}" method="POST" class="space-y-3 max-w-sm mx-auto">
                    @csrf
                    <div>
                        <input 
                            type="text" 
                            name="guest_name" 
                            value="{{ $guestName !== 'Tamu Undangan' ? $guestName : '' }}"
                            required 
                            placeholder="Nama Pengirim"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#6e1c27]/25 bg-white/95 text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-[#6e1c27]/30 transition shadow-2xs"
                        >
                    </div>

                    <div>
                        <textarea 
                            name="message" 
                            rows="2" 
                            required 
                            placeholder="Tuliskan ucapan dan doa terbaik Anda..."
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#6e1c27]/25 bg-white/95 text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-[#6e1c27]/30 transition shadow-2xs resize-none font-cormorant"
                        ></textarea>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-2.5 px-6 rounded-full bg-[#b98b52] hover:bg-[#a07641] text-white text-[11px] font-bold tracking-wider uppercase shadow-md transition duration-200"
                    >
                        Kirim Doa Restu
                    </button>
                </form>

                <!-- Live Stream Messages -->
                <div id="messages-stream-list" class="space-y-2.5 max-h-72 overflow-y-auto pr-1 max-w-sm mx-auto">
                    @forelse ($guestMessages as $msg)
                        <div class="p-3 rounded-xl bg-white/95 border border-[#6e1c27]/15 shadow-2xs">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-[#6e1c27]/10 text-[#6e1c27] font-bold text-[10px] flex items-center justify-center font-playfair">
                                        {{ strtoupper(substr($msg->guest_name, 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-xs text-stone-900 font-playfair">{{ $msg->guest_name }}</span>
                                </div>
                                <span class="text-[9px] text-stone-400 font-medium">
                                    {{ $msg->created_at ? $msg->created_at->diffForHumans() : 'Baru saja' }}
                                </span>
                            </div>
                            <p class="text-xs text-stone-600 font-cormorant leading-relaxed pl-8">{{ $msg->message }}</p>
                        </div>
                    @empty
                        <div id="empty-messages-notice" class="py-5 text-center text-xs text-stone-400 font-cormorant">
                            Belum ada ucapan doa. Jadilah yang pertama memberikan doa restu!
                        </div>
                    @endforelse
                </div>

                <!-- Guestbook Pagination Controls -->
                <div id="guestbook-pagination" class="hidden max-w-sm mx-auto items-center justify-between pt-2 px-1 text-xs">
                    <button 
                        type="button" 
                        data-page-action="prev"
                        class="py-1 px-3 rounded-full border border-[#6e1c27]/30 text-[#6e1c27] hover:bg-[#6e1c27]/10 disabled:opacity-30 disabled:pointer-events-none transition text-[11px] font-bold font-cormorant flex items-center gap-1 shadow-2xs"
                    >
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>Sebelumnya</span>
                    </button>

                    <span data-page-info class="text-[11px] text-stone-500 font-semibold font-cormorant">
                        Halaman <strong class="text-stone-800 font-bold">1</strong> dari <strong class="text-stone-800 font-bold">1</strong>
                    </span>

                    <button 
                        type="button" 
                        data-page-action="next"
                        class="py-1 px-3 rounded-full border border-[#6e1c27]/30 text-[#6e1c27] hover:bg-[#6e1c27]/10 disabled:opacity-30 disabled:pointer-events-none transition text-[11px] font-bold font-cormorant flex items-center gap-1 shadow-2xs"
                    >
                        <span>Selanjutnya</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </section>

            <!-- SECTION 10: TANDA KASIH (AMPLOP DIGITAL) -->
            <section id="section-gift" class="fe-page-card p-6 sm:p-8 text-center space-y-4">
                <x-floral-corner-vine position="tl" />
                <x-floral-corner-vine position="br" />

                <!-- Top Pills -->
                <div class="space-y-2 mb-2">
                    <div>
                        <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                            <span class="text-xs font-semibold tracking-[0.25em] text-[#6e1c27] uppercase font-cormorant">
                                WEDDING GIFT
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="fe-pill-badge w-full max-w-[280px] mx-auto py-1.5">
                            <span class="text-xs font-semibold tracking-[0.2em] text-[#6e1c27] uppercase font-cormorant">
                                TANDA KASIH
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-white/95 rounded-2xl p-4 border border-[#6e1c27]/15 shadow-xs max-w-sm mx-auto">
                    <p class="text-xs text-stone-600 font-cormorant leading-relaxed">
                        Doa restu Anda merupakan karunia terindah. Namun apabila berkehendak memberikan tanda kasih, dapat disalurkan melalui rekening berikut:
                    </p>
                </div>

                <div class="space-y-2.5 max-w-sm mx-auto">
                    <!-- BCA -->
                    <div class="p-4 rounded-2xl bg-white/95 border border-[#b98b52]/30 shadow-xs text-left">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-xs tracking-wider text-blue-900">BCA</span>
                            <span class="text-[9px] text-stone-400">Bank Central Asia</span>
                        </div>
                        <p class="text-sm font-mono font-bold text-stone-900">1234 5678 90</p>
                        <p class="text-[11px] text-stone-500 mb-2.5 font-cormorant">a.n {{ $shortOne }}</p>
                        <button 
                            type="button" 
                            class="fe-btn-copy w-full py-2 px-3 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-800 text-[11px] font-semibold flex items-center justify-center gap-1.5 transition"
                            data-copy-text="1234567890"
                        >
                            <svg class="w-3 h-3 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span class="copy-label">Salin Nomor Rekening</span>
                        </button>
                    </div>

                    <!-- Mandiri -->
                    <div class="p-4 rounded-2xl bg-white/95 border border-[#b98b52]/30 shadow-xs text-left">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-xs tracking-wider text-amber-800">MANDIRI</span>
                            <span class="text-[9px] text-stone-400">Bank Mandiri</span>
                        </div>
                        <p class="text-sm font-mono font-bold text-stone-900">9876 5432 10</p>
                        <p class="text-[11px] text-stone-500 mb-2.5 font-cormorant">a.n {{ $shortTwo }}</p>
                        <button 
                            type="button" 
                            class="fe-btn-copy w-full py-2 px-3 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-800 text-[11px] font-semibold flex items-center justify-center gap-1.5 transition"
                            data-copy-text="9876543210"
                        >
                            <svg class="w-3 h-3 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span class="copy-label">Salin Nomor Rekening</span>
                        </button>
                    </div>
                </div>
            </section>

            <!-- SECTION 11: PENUTUP & TERIMA KASIH -->
            <section class="fe-page-card p-6 sm:p-8 text-center space-y-3">
                <x-floral-corner-vine position="tl" />
                <x-floral-corner-vine position="br" />

                <p class="text-xs text-stone-600 leading-relaxed max-w-xs mx-auto font-cormorant italic">
                    "Tiada yang lebih berharga selain doa dan kehadiran Bapak/Ibu/Saudara/i untuk melengkapi kebahagiaan kami."
                </p>

                <span class="text-[9px] font-bold tracking-[0.25em] text-[#b98b52] uppercase block font-cormorant">
                    KAMI YANG BERBAHAGIA
                </span>
                <h3 class="text-2xl sm:text-3xl font-bold font-playfair text-[#6e1c27]">
                    {{ $shortOne }} &amp; {{ $shortTwo }}
                </h3>
                <p class="text-[11px] text-stone-500 font-cormorant">Beserta Keluarga Besar</p>

                <div class="pt-3 border-t border-[#6e1c27]/15 text-[10px] text-stone-400 font-cormorant">
                    <p>Digital Wedding Invitation by <span class="font-bold text-[#6e1c27]">Lumina Invites</span></p>
                </div>
            </section>

        </div>
    </div>

    <!-- ================= FLOATING BOTTOM ICON NAVIGATION BAR ================= -->
    <div 
        id="fe-bottom-navbar" 
        class="fe-bottom-nav fe-nav-hidden flex items-center gap-1.5 sm:gap-2 px-3 py-1.5 rounded-full shadow-2xl"
    >
        <button type="button" class="fe-nav-item active w-8 h-8 rounded-full text-[#6e1c27] flex items-center justify-center transition" data-target="section-cover" title="Cover">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </button>

        <button type="button" class="fe-nav-item w-8 h-8 rounded-full text-[#6e1c27] flex items-center justify-center transition" data-target="section-quote" title="Ayat Suci">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </button>

        <button type="button" class="fe-nav-item w-8 h-8 rounded-full text-[#6e1c27] flex items-center justify-center transition" data-target="section-events" title="Acara">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </button>

        <button type="button" class="fe-nav-item w-8 h-8 rounded-full text-[#6e1c27] flex items-center justify-center transition" data-target="section-countdown" title="Countdown">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>

        <button type="button" class="fe-nav-item w-8 h-8 rounded-full text-[#6e1c27] flex items-center justify-center transition" data-target="section-rsvp" title="RSVP">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </button>

        <button type="button" class="fe-nav-item w-8 h-8 rounded-full text-[#6e1c27] flex items-center justify-center transition" data-target="section-gift" title="Amplop Digital">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>
    </div>

    <!-- ================= LIGHTBOX MODAL FOR GALLERY ================= -->
    <div id="gallery-lightbox-modal" class="fixed inset-0 z-50 bg-[#36090f]/90 backdrop-blur-md hidden items-center justify-center p-4">
        <button 
            type="button" 
            id="btn-close-lightbox"
            class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/20 text-white flex items-center justify-center hover:bg-white/30 transition duration-200"
            title="Tutup"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="max-w-2xl w-full text-center">
            <img id="lightbox-image" src="" alt="Prewedding Photo" class="max-h-[75vh] w-auto mx-auto rounded-2xl shadow-2xl object-contain border border-white/20">
            <p id="lightbox-caption" class="text-xs text-stone-200 mt-2.5 font-medium font-cormorant"></p>
        </div>
    </div>

</body>
</html>
