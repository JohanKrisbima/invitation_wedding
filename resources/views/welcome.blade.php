<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="LuminaInvites — Platform pembuatan undangan pernikahan digital terbaik yang mengutamakan keindahan desain, kemudahan penggunaan, dan fitur modern.">

    <title>LuminaInvites — Undangan Pernikahan Digital Elegan</title>

    <!-- Bundled Assets Local Only (Tailwind v4, Cormorant Garamond, Instrument Sans, Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF9F6] text-stone-800 font-sans antialiased selection:bg-[#C6905E]/20 selection:text-stone-900 overflow-x-hidden" x-data="{ mobileOpen: false }">

    <!-- ===================================================================== -->
    <!-- 1. NAVBAR (STICKY GLASSMORPHIC) -->
    <!-- ===================================================================== -->
    <header class="sticky top-0 z-50 bg-[#FAF9F6]/90 backdrop-blur-md border-b border-stone-200/50 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo: LuminaInvites -->
                <a href="{{ url('/') }}" class="font-serif text-2xl sm:text-3xl font-bold tracking-tight hover:opacity-90 transition">
                    <span class="text-[#C6905E]">Lumina</span><span class="text-stone-900">Invites</span>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-stone-600">
                    <a href="#fitur" class="hover:text-[#C6905E] transition-colors">Fitur</a>
                    <a href="#katalog" class="hover:text-[#C6905E] transition-colors">Katalog</a>
                    <a href="#harga" class="hover:text-[#C6905E] transition-colors">Harga</a>
                    <a href="#testimoni" class="hover:text-[#C6905E] transition-colors">Testimoni</a>
                </nav>

                <!-- Right Action Buttons -->
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <a 
                            href="{{ route('admin.dashboard') }}" 
                            class="px-4 py-2 rounded-full text-xs font-semibold bg-stone-100 hover:bg-stone-200 text-stone-700 transition"
                        >
                            Dashboard Admin
                        </a>
                    @endauth

                    <a 
                        href="#harga" 
                        class="px-6 py-2.5 rounded-full bg-[#C6905E] hover:bg-[#B37F50] text-white text-xs sm:text-sm font-medium shadow-xs hover:shadow-md transition-all duration-200"
                    >
                        Buat Undangan
                    </a>
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="md:hidden flex items-center">
                    <button 
                        type="button" 
                        @click="mobileOpen = !mobileOpen"
                        class="p-2 rounded-xl text-stone-700 hover:bg-stone-100 transition focus:outline-none"
                        aria-label="Toggle Navigation"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!mobileOpen">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="mobileOpen" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div 
            x-show="mobileOpen" 
            x-cloak
            @click.outside="mobileOpen = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="md:hidden bg-white/95 backdrop-blur-xl border-b border-stone-200 px-5 pt-3 pb-6 space-y-3"
        >
            <a href="#fitur" @click="mobileOpen = false" class="block py-2 text-sm font-medium text-stone-700 hover:text-[#C6905E]">Fitur</a>
            <a href="#katalog" @click="mobileOpen = false" class="block py-2 text-sm font-medium text-stone-700 hover:text-[#C6905E]">Katalog</a>
            <a href="#harga" @click="mobileOpen = false" class="block py-2 text-sm font-medium text-stone-700 hover:text-[#C6905E]">Harga</a>
            <a href="#testimoni" @click="mobileOpen = false" class="block py-2 text-sm font-medium text-stone-700 hover:text-[#C6905E]">Testimoni</a>

            <div class="pt-3 border-t border-stone-100 flex flex-col gap-2">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="w-full text-center py-2.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-800">
                        Dashboard Admin
                    </a>
                @endauth
                <a href="#harga" @click="mobileOpen = false" class="w-full text-center py-2.5 rounded-full text-xs font-semibold bg-[#C6905E] text-white shadow-xs">
                    Buat Undangan
                </a>
            </div>
        </div>
    </header>


    <!-- ===================================================================== -->
    <!-- 2. HERO SECTION [TEMA 1: BONE WHITE HONEYCOMB] -->
    <!-- ===================================================================== -->
    <section class="bg-honeycomb min-h-[85vh] flex flex-col justify-center items-center text-center px-4 sm:px-6 lg:px-8 py-20 relative">
        <div class="max-w-4xl mx-auto z-10">
            <!-- Small Uppercase Tagline -->
            <p class="text-[11px] sm:text-xs font-bold tracking-[0.22em] text-[#C6905E] uppercase mb-4">
                MULAI PERJALANAN ABADI ANDA
            </p>

            <!-- Headline -->
            <h1 class="text-4xl sm:text-6xl lg:text-[4.2rem] font-serif font-normal text-stone-900 tracking-tight leading-[1.18] mb-6">
                Sebarkan Kabar Bahagia<br class="hidden sm:inline" /> dengan Cara yang <span class="italic font-serif text-[#C6905E]">Elegan</span>
            </h1>

            <!-- Subtitle -->
            <p class="text-sm sm:text-base text-stone-500 max-w-2xl mx-auto leading-relaxed mb-10 font-normal">
                Buat undangan pernikahan digital yang indah, interaktif, dan mudah disesuaikan.<br class="hidden sm:inline" />
                Bagikan momen spesial Anda ke seluruh dunia hanya dengan satu tautan.
            </p>

            <!-- 2 Buttons (Lihat Desain & Pelajari Fitur) -->
            <div class="flex items-center justify-center gap-4">
                <a 
                    href="#katalog" 
                    class="bg-[#232323] hover:bg-black text-white text-xs sm:text-sm font-medium px-8 py-3.5 rounded-full shadow-md hover:-translate-y-0.5 transition duration-200"
                >
                    Lihat Desain
                </a>

                <a 
                    href="#fitur" 
                    class="bg-white hover:bg-stone-50 text-stone-700 text-xs sm:text-sm font-medium px-8 py-3.5 rounded-full border border-stone-200/80 shadow-xs hover:-translate-y-0.5 transition duration-200"
                >
                    Pelajari Fitur
                </a>
            </div>
        </div>
    </section>


    <!-- ===================================================================== -->
    <!-- 3. BAGIAN FITUR [TEMA 2: WARM SOFT LINEN / SAND #F4EFEB] -->
    <!-- ===================================================================== -->
    <section id="fitur" class="bg-[#F4EFEB] border-y border-stone-200/60 py-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-serif font-normal text-stone-900">
                    Fitur Undangan Digital
                </h2>
                <!-- Gold Line Ornament -->
                <div class="w-12 h-0.5 bg-[#C6905E] mx-auto mt-3 mb-4 rounded-full"></div>
                <p class="text-xs sm:text-sm text-stone-500 leading-relaxed font-normal">
                    Semua yang Anda butuhkan untuk mempermudah persiapan hari H.
                </p>
            </div>

            <!-- 6 Feature Cards Grid (Crisp White Cards contrasting against Linen Sand) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- 1. RSVP Online Cepat -->
                <div class="bg-white rounded-3xl p-8 border border-stone-200/60 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-start">
                    <div class="w-12 h-12 rounded-full bg-[#FAF9F6] border border-stone-100 shadow-2xs flex items-center justify-center text-[#C6905E] mb-6">
                        <!-- Send/Bell Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-serif font-normal text-stone-900 mb-2.5">
                        RSVP Online Cepat
                    </h3>
                    <p class="text-xs sm:text-sm text-stone-500 leading-relaxed">
                        Kumpulkan konfirmasi kehadiran tamu Anda secara real-time langsung ke dashboard Anda tanpa repot.
                    </p>
                </div>

                <!-- 2. Navigasi Peta -->
                <div class="bg-white rounded-3xl p-8 border border-stone-200/60 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-start">
                    <div class="w-12 h-12 rounded-full bg-[#FAF9F6] border border-stone-100 shadow-2xs flex items-center justify-center text-[#C6905E] mb-6">
                        <!-- Location Pin Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-serif font-normal text-stone-900 mb-2.5">
                        Navigasi Peta
                    </h3>
                    <p class="text-xs sm:text-sm text-stone-500 leading-relaxed">
                        Terintegrasi dengan Google Maps. Mudahkan tamu Anda menemukan lokasi acara pernikahan Anda.
                    </p>
                </div>

                <!-- 3. Galeri Pre-Wedding -->
                <div class="bg-white rounded-3xl p-8 border border-stone-200/60 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-start">
                    <div class="w-12 h-12 rounded-full bg-[#FAF9F6] border border-stone-100 shadow-2xs flex items-center justify-center text-[#C6905E] mb-6">
                        <!-- Heart Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-serif font-normal text-stone-900 mb-2.5">
                        Galeri Pre-Wedding
                    </h3>
                    <p class="text-xs sm:text-sm text-stone-500 leading-relaxed">
                        Tampilkan foto dan video perjalanan cinta Anda dalam galeri cantik yang bisa dinikmati oleh para tamu.
                    </p>
                </div>

                <!-- 4. Backsound Musik Kustom -->
                <div class="bg-white rounded-3xl p-8 border border-stone-200/60 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-start">
                    <div class="w-12 h-12 rounded-full bg-[#FAF9F6] border border-stone-100 shadow-2xs flex items-center justify-center text-[#C6905E] mb-6">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-serif font-normal text-stone-900 mb-2.5">
                        Backsound Musik Kustom
                    </h3>
                    <p class="text-xs sm:text-sm text-stone-500 leading-relaxed">
                        Ciptakan suasana romantis dengan alunan lagu pilihan yang otomatis diputar saat undangan dibuka.
                    </p>
                </div>

                <!-- 5. Hitung Mundur Waktu -->
                <div class="bg-white rounded-3xl p-8 border border-stone-200/60 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-start">
                    <div class="w-12 h-12 rounded-full bg-[#FAF9F6] border border-stone-100 shadow-2xs flex items-center justify-center text-[#C6905E] mb-6">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-serif font-normal text-stone-900 mb-2.5">
                        Hitung Mundur Waktu
                    </h3>
                    <p class="text-xs sm:text-sm text-stone-500 leading-relaxed">
                        Pengingat hari H pernikahan otomatis secara presisi agar tamu siap menyambut hari bahagia Anda.
                    </p>
                </div>

                <!-- 6. Desain Responsif & Cepat -->
                <div class="bg-white rounded-3xl p-8 border border-stone-200/60 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-start">
                    <div class="w-12 h-12 rounded-full bg-[#FAF9F6] border border-stone-100 shadow-2xs flex items-center justify-center text-[#C6905E] mb-6">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-serif font-normal text-stone-900 mb-2.5">
                        Desain Responsif &amp; Cepat
                    </h3>
                    <p class="text-xs sm:text-sm text-stone-500 leading-relaxed">
                        Tampilan sempurna dan loading super cepat di semua perangkat smartphone, tablet, maupun laptop.
                    </p>
                </div>
            </div>
        </div>
    </section>


    <!-- ===================================================================== -->
    <!-- 4. KOLEKSI DESAIN KAMI [TEMA 1: BONE WHITE #FAF9F6] -->
    <!-- ===================================================================== -->
    <section id="katalog" class="bg-[#FAF9F6] py-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header with Right Link -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-12">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-serif font-normal text-stone-900">
                        Koleksi Desain Kami
                    </h2>
                    <!-- Gold Line Ornament -->
                    <div class="w-12 h-0.5 bg-[#C6905E] mt-3 mb-4 rounded-full"></div>
                    <p class="text-xs sm:text-sm text-stone-500 max-w-xl leading-relaxed">
                        Pilih dari berbagai template eksklusif yang dirancang oleh desainer profesional, sesuaikan dengan tema pernikahan Anda.
                    </p>
                </div>
                <div>
                    <a href="#harga" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-[#C6905E] hover:underline">
                        <span>Lihat Semua Tema</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            @php
                // Palet warna pastel persis seperti di Screenshot 3
                $pastelStyles = [
                    0 => ['bg' => 'bg-[#FDF0DE]', 'text' => 'text-[#2C241E]'], // Warm Peach (Tema Classic Rose)
                    1 => ['bg' => 'bg-[#E9EEF5]', 'text' => 'text-[#1E252D]'], // Light Blue-Grey (Tema Minimalist)
                    2 => ['bg' => 'bg-[#D9F5E5]', 'text' => 'text-[#143224]'], // Soft Mint-Green (Tema Botanical)
                    3 => ['bg' => 'bg-[#FBEBE8]', 'text' => 'text-[#3D1D16]'], // Blush Rose
                ];
            @endphp

            <!-- Template Cards (Looping from Database templates) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse ($templates as $index => $template)
                    @php
                        $style = $pastelStyles[$index % count($pastelStyles)];
                    @endphp
                    <div class="group flex flex-col justify-between">
                        <!-- Pastel Card Container -->
                        <div class="aspect-[4/3] rounded-3xl {{ $style['bg'] }} flex items-center justify-center p-6 text-center shadow-xs group-hover:shadow-md transition-all duration-300 overflow-hidden relative">
                            @if ($template->thumbnail_url)
                                <img 
                                    src="{{ $template->thumbnail_url }}" 
                                    alt="{{ $template->name }}" 
                                    class="w-full h-full object-cover rounded-2xl group-hover:scale-105 transition duration-500"
                                />
                            @else
                                <!-- Centered Serif Text matching Screenshot 3 -->
                                <h3 class="text-2xl sm:text-3xl font-serif font-bold tracking-tight {{ $style['text'] }} leading-snug">
                                    Tema {{ $template->name }}
                                </h3>
                            @endif

                            <!-- Category Pill -->
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 rounded-full bg-white/80 backdrop-blur-xs text-[10px] font-semibold text-stone-700 shadow-2xs">
                                    {{ $template->category }}
                                </span>
                            </div>
                        </div>

                        <!-- Bottom Name & Price -->
                        <div class="text-center mt-5">
                            <h4 class="text-lg font-serif font-normal text-stone-900 group-hover:text-[#C6905E] transition-colors">
                                {{ $template->name }}
                            </h4>
                            <div class="flex items-center justify-center gap-2 mt-1">
                                <span class="text-xs font-semibold text-[#C6905E]">
                                    Rp {{ number_format($template->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Fallback jika belum ada data di database -->
                    <div class="aspect-[4/3] rounded-3xl bg-[#FDF0DE] flex items-center justify-center p-6">
                        <h3 class="text-2xl font-serif font-bold text-[#2C241E]">Tema Classic Rose</h3>
                    </div>
                    <div class="aspect-[4/3] rounded-3xl bg-[#E9EEF5] flex items-center justify-center p-6">
                        <h3 class="text-2xl font-serif font-bold text-[#1E252D]">Tema Minimalist</h3>
                    </div>
                    <div class="aspect-[4/3] rounded-3xl bg-[#D9F5E5] flex items-center justify-center p-6">
                        <h3 class="text-2xl font-serif font-bold text-[#143224]">Tema Botanical</h3>
                    </div>
                @endforelse
            </div>
        </div>
    </section>


    <!-- ===================================================================== -->
    <!-- 5. PAKET HARGA SPESIAL [TEMA 2: WARM SOFT LINEN / SAND #F4EFEB] -->
    <!-- ===================================================================== -->
    <section id="harga" class="bg-[#F4EFEB] border-y border-stone-200/60 py-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-serif font-normal text-stone-900">
                    Paket Harga Spesial
                </h2>
                <!-- Gold Line Ornament -->
                <div class="w-12 h-0.5 bg-[#C6905E] mx-auto mt-3 mb-4 rounded-full"></div>
                <p class="text-xs sm:text-sm text-stone-500 leading-relaxed font-normal">
                    Pilih paket undangan yang paling sesuai dengan kebutuhan pernikahan<br class="hidden sm:inline" />
                    Anda. Tidak ada biaya tersembunyi.
                </p>
            </div>

            <!-- 3 Pricing Cards Grid (Crisp White Cards Pop on Sand Background) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center max-w-6xl mx-auto">
                <!-- 1. Silver -->
                <div class="rounded-3xl bg-white p-8 sm:p-10 border border-stone-200/80 shadow-xs hover:shadow-lg transition-all duration-300">
                    <h3 class="text-lg font-medium text-stone-900">Silver</h3>
                    <div class="font-serif text-3xl font-medium text-stone-900 my-4">
                        Rp 149.000
                    </div>

                    <ul class="space-y-3.5 text-xs text-stone-600 mb-8 font-normal">
                        <li class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Pilihan Tema Basic</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Maks. 5 Foto Galeri</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Navigasi Lokasi</span>
                        </li>
                        <li class="flex items-center gap-3 text-stone-400">
                            <svg class="w-4 h-4 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>RSVP Online</span>
                        </li>
                        <li class="flex items-center gap-3 text-stone-400">
                            <svg class="w-4 h-4 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Backsound Musik</span>
                        </li>
                    </ul>

                    <a 
                        href="https://wa.me/6281234567890?text=Halo%20LuminaInvites,%20saya%20tertarik%20dengan%20Paket%20Silver" 
                        target="_blank"
                        class="block w-full py-2.5 rounded-full text-center text-xs font-semibold border border-stone-800 text-stone-800 hover:bg-stone-800 hover:text-white transition duration-200"
                    >
                        Pilih Silver
                    </a>
                </div>

                <!-- 2. Gold (PALING POPULER) -->
                <div class="relative rounded-3xl bg-white p-8 sm:p-10 border-2 border-[#C6905E] shadow-xl scale-100 lg:scale-105 z-10">
                    <!-- Badge Populer -->
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2">
                        <span class="px-4 py-0.5 rounded-full bg-[#C6905E] text-white text-[11px] font-bold uppercase tracking-wider shadow-xs">
                            PALING POPULER
                        </span>
                    </div>

                    <h3 class="text-lg font-medium text-stone-900 mt-1">Gold</h3>
                    <div class="font-serif text-4xl font-medium text-stone-900 my-4">
                        Rp 249.000
                    </div>

                    <ul class="space-y-3.5 text-xs text-stone-700 mb-8 font-normal">
                        <li class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-[#C6905E] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Bebas Pilih Tema Premium</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-[#C6905E] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Maks. 15 Foto Galeri</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-[#C6905E] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Navigasi Lokasi</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-[#C6905E] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>RSVP Online Terpusat</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-[#C6905E] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Backsound Musik Kustom</span>
                        </li>
                    </ul>

                    <a 
                        href="https://wa.me/6281234567890?text=Halo%20LuminaInvites,%20saya%20ingin%20memesan%20Paket%20Gold" 
                        target="_blank"
                        class="block w-full py-3 rounded-full text-center text-xs font-semibold bg-[#C6905E] hover:bg-[#B37F50] text-white shadow-md transition duration-200"
                    >
                        Pilih Gold
                    </a>
                </div>

                <!-- 3. Platinum -->
                <div class="rounded-3xl bg-white p-8 sm:p-10 border border-stone-200/80 shadow-xs hover:shadow-lg transition-all duration-300">
                    <h3 class="text-lg font-medium text-stone-900">Platinum</h3>
                    <div class="font-serif text-3xl font-medium text-stone-900 my-4">
                        Rp 499.000
                    </div>

                    <ul class="space-y-3.5 text-xs text-stone-600 mb-8 font-normal">
                        <li class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Desain Custom (Eksklusif)</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Galeri Foto &amp; Video Unlimited</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Custom Domain (.com)</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Buku Tamu Digital</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Prioritas Support 24/7</span>
                        </li>
                    </ul>

                    <a 
                        href="https://wa.me/6281234567890?text=Halo%20LuminaInvites,%20saya%20ingin%20memesan%20Paket%20Platinum" 
                        target="_blank"
                        class="block w-full py-2.5 rounded-full text-center text-xs font-semibold border border-stone-800 text-stone-800 hover:bg-stone-800 hover:text-white transition duration-200"
                    >
                        Pilih Platinum
                    </a>
                </div>
            </div>
        </div>
    </section>


    <!-- ===================================================================== -->
    <!-- 6. TESTIMONI [TEMA 1: BONE WHITE #FAF9F6] -->
    <!-- ===================================================================== -->
    <section id="testimoni" class="bg-[#FAF9F6] py-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <h2 class="text-3xl font-serif font-normal text-stone-900">
                    Kata Mereka
                </h2>
                <div class="w-12 h-0.5 bg-[#C6905E] mx-auto mt-3 mb-4 rounded-full"></div>
                <p class="text-xs sm:text-sm text-stone-500 font-normal">
                    Cerita bahagia dari pasangan yang telah menggunakan LuminaInvites.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-3xl p-8 border border-stone-200/70 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                    <p class="text-xs sm:text-sm text-stone-600 font-serif italic leading-relaxed mb-6">
                        &ldquo;Desainnya sangat elegan dan tidak pasaran. Fitur RSVP-nya sangat mempermudah kami mendata tamu undangan.&rdquo;
                    </p>
                    <div class="flex items-center gap-3 pt-4 border-t border-stone-100">
                        <div class="w-9 h-9 rounded-full bg-[#FAF9F6] text-[#C6905E] border border-stone-200/60 flex items-center justify-center font-bold text-xs">
                            DR
                        </div>
                        <div>
                            <div class="text-xs font-bold text-stone-900">Dimas &amp; Rania</div>
                            <div class="text-[10px] text-stone-400">Pernikahan di Bandung</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 border border-stone-200/70 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                    <p class="text-xs sm:text-sm text-stone-600 font-serif italic leading-relaxed mb-6">
                        &ldquo;Pelayanannya cepat, tampilannya responsif di HP, dan tamu-tamu kami sangat kagum saat membuka undangannya.&rdquo;
                    </p>
                    <div class="flex items-center gap-3 pt-4 border-t border-stone-100">
                        <div class="w-9 h-9 rounded-full bg-[#FAF9F6] text-[#C6905E] border border-stone-200/60 flex items-center justify-center font-bold text-xs">
                            KS
                        </div>
                        <div>
                            <div class="text-xs font-bold text-stone-900">Kevin &amp; Stephanie</div>
                            <div class="text-[10px] text-stone-400">Pernikahan di Jakarta</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 border border-stone-200/70 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                    <p class="text-xs sm:text-sm text-stone-600 font-serif italic leading-relaxed mb-6">
                        &ldquo;Paket Gold sangat lengkap! Lagu latarnya syahdu dan navigasi petanya langsung presisi mengarahkan tamu.&rdquo;
                    </p>
                    <div class="flex items-center gap-3 pt-4 border-t border-stone-100">
                        <div class="w-9 h-9 rounded-full bg-[#FAF9F6] text-[#C6905E] border border-stone-200/60 flex items-center justify-center font-bold text-xs">
                            AS
                        </div>
                        <div>
                            <div class="text-xs font-bold text-stone-900">Adit &amp; Sarah</div>
                            <div class="text-[10px] text-stone-400">Pernikahan di Yogyakarta</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ===================================================================== -->
    <!-- 7. CTA BANNER GELAP [TEMA 3: LUXURY DARK CHARCOAL #1C1A17] -->
    <!-- ===================================================================== -->
    <section class="bg-[#1C1A17] text-white py-24 px-6 sm:px-12 text-center relative overflow-hidden">
        <div class="max-w-3xl mx-auto z-10 relative">
            <h2 class="text-3xl sm:text-5xl font-serif font-normal text-white mb-4 leading-tight">
                Siap Membuat Undangan Impian Anda?
            </h2>
            <p class="text-stone-300 text-xs sm:text-sm max-w-xl mx-auto leading-relaxed mb-8 font-normal">
                Hanya butuh 5 menit untuk mendaftar dan mulai mengatur undangan pernikahan digital Anda.
            </p>

            <a 
                href="https://wa.me/6281234567890?text=Halo%20LuminaInvites,%20saya%20ingin%20membuat%20undangan%20pernikahan%20digital" 
                target="_blank"
                class="bg-[#C6905E] hover:bg-[#B37F50] text-white px-8 py-3.5 rounded-full text-xs sm:text-sm font-semibold shadow-lg transition duration-200 inline-block hover:scale-105"
            >
                Mulai Sekarang - Gratis Coba
            </a>
        </div>
    </section>


    <!-- ===================================================================== -->
    <!-- 8. FOOTER [TEMA 4: WARM TAUPE / LINEN #ECE7DC] -->
    <!-- ===================================================================== -->
    <footer class="bg-[#ECE7DC] border-t border-stone-300/60 py-16 px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                <!-- Col 1: Brand Info -->
                <div class="space-y-4">
                    <a href="{{ url('/') }}" class="font-serif text-2xl font-bold tracking-tight">
                        <span class="text-[#C6905E]">Lumina</span><span class="text-stone-900">Invites</span>
                    </a>
                    <p class="text-xs text-stone-600 leading-relaxed max-w-sm">
                        Platform pembuatan undangan pernikahan digital terbaik yang mengutamakan keindahan desain, kemudahan penggunaan, dan fitur modern.
                    </p>
                </div>

                <!-- Col 2: Menu Bantuan -->
                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-stone-900">Menu Bantuan</h4>
                    <ul class="space-y-2 text-xs text-stone-600 font-medium">
                        <li><a href="#" class="hover:text-[#C6905E] transition">Tentang Kami</a></li>
                        <li><a href="#harga" class="hover:text-[#C6905E] transition">Cara Pemesanan</a></li>
                        <li><a href="#" class="hover:text-[#C6905E] transition">Syarat &amp; Ketentuan</a></li>
                        <li><a href="#" class="hover:text-[#C6905E] transition">Kebijakan Privasi</a></li>
                    </ul>
                </div>

                <!-- Col 3: Kontak Kami -->
                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-stone-900">Kontak Kami</h4>
                    <div class="space-y-2 text-xs text-stone-600 font-medium">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <a href="mailto:hello@luminainvites.com" class="hover:text-[#C6905E]">hello@luminainvites.com</a>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <a href="https://wa.me/6281234567890" target="_blank" class="hover:text-[#C6905E]">+62 812 3456 7890</a>
                        </div>
                    </div>

                    <!-- Social Icons (Twitter & Instagram) -->
                    <div class="flex items-center gap-3 pt-2 text-stone-500">
                        <a href="#" class="hover:text-[#C6905E] transition" title="Twitter / X">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                        <a href="#" class="hover:text-[#C6905E] transition" title="Instagram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Line -->
            <div class="pt-8 border-t border-stone-300/50 text-center sm:text-left text-xs text-stone-500">
                &copy; {{ date('Y') }} LuminaInvites. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

</body>
</html>
