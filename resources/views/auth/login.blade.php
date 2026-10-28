<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Masuk ke Portal Admin — Prewedding & Wedding Invitation</title>

    <!-- Asset Bundles (Tailwind v4, Alpine.js, SweetAlert2) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#FBF9F5] text-stone-800 font-sans antialiased selection:bg-[#C25E2E]/20 selection:text-[#C25E2E]">
    <!-- Flash Messages (SweetAlert2 - Zero Inline Script) -->
    <x-flash-message />

    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- ================= LEFT COLUMN: LOGIN FORM ================= -->
        <div class="w-full lg:w-1/2 p-6 sm:p-10 lg:p-16 flex flex-col justify-between bg-[#FBF9F5]">
            <!-- Top Header: Brand & Back -->
            <div class="flex items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#C25E2E] to-[#A84B20] text-white flex items-center justify-center shadow-md shadow-[#C25E2E]/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21a9 9 0 100-18 9 9 0 000 18z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 10a3 3 0 116 0c0 2-3 3-3 5" />
                            <circle cx="12" cy="18" r="0.8" fill="currentColor" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-stone-900 tracking-tight block">WeddingAdmin</span>
                        <span class="text-[10px] text-stone-400 font-medium block">Prewedding & Wedding Portal</span>
                    </div>
                </div>

                <a 
                    href="{{ url('/') }}" 
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-stone-500 hover:text-stone-900 transition"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Beranda</span>
                </a>
            </div>

            <!-- Form Content Body -->
            <div class="max-w-md w-full mx-auto my-auto py-6">
                <!-- Portal Tracker Tag -->
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-[10px] font-bold tracking-widest text-[#C25E2E] uppercase">
                        PORTAL ADMIN / MASUK
                    </span>
                </div>

                <!-- Big Main Heading -->
                <h1 class="text-3xl sm:text-4xl font-extrabold text-stone-900 tracking-tight font-serif mb-2">
                    Masuk ke akun Anda.
                </h1>
                <p class="text-xs sm:text-sm text-stone-500 mb-8 leading-relaxed">
                    Gunakan email dan kata sandi akun administrator Anda untuk mengakses pengelolaan undangan.
                </p>

                <!-- Error Alert Box -->
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-800 text-xs flex items-start gap-3 shadow-xs">
                        <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <p class="font-bold text-rose-900">Autentikasi Gagal</p>
                            @foreach ($errors->all() as $error)
                                <p class="leading-relaxed">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Field 1: Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-stone-700 mb-2">
                            Email Administrator
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-stone-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input 
                                id="email"
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                required 
                                autofocus 
                                placeholder="nama@domain.com"
                                class="w-full pl-11 pr-4 py-3 rounded-2xl border border-stone-200 bg-white/80 backdrop-blur-md text-xs sm:text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition shadow-xs"
                            >
                        </div>
                    </div>

                    <!-- Field 2: Password with Alpine Toggle Show/Hide -->
                    <div x-data="{ showPassword: false }">
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="text-xs font-semibold text-stone-700">
                                Kata Sandi
                            </label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-stone-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input 
                                id="password"
                                :type="showPassword ? 'text' : 'password'" 
                                name="password" 
                                required 
                                placeholder="••••••••"
                                class="w-full pl-11 pr-11 py-3 rounded-2xl border border-stone-200 bg-white/80 backdrop-blur-md text-xs sm:text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition shadow-xs"
                            >
                            <!-- Eye Button -->
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-stone-400 hover:text-stone-600 transition focus:outline-none"
                            >
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Toggle -->
                    <div class="flex items-center gap-3 pt-1">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="sr-only peer" {{ old('remember') ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C25E2E]"></div>
                        </label>
                        <span class="text-xs text-stone-600 font-medium">
                            Ingat akun di perangkat ini
                        </span>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full py-3.5 px-6 rounded-2xl bg-[#C25E2E] hover:bg-[#A84B20] text-white text-xs sm:text-sm font-bold shadow-md shadow-[#C25E2E]/25 transition duration-200 flex items-center justify-center gap-2 group"
                    >
                        <span>Masuk ke Portal Admin</span>
                        <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Left Bottom Footer -->
            <div class="flex items-center justify-between text-[11px] text-stone-400 pt-6 border-t border-stone-200/60">
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span>Koneksi aman terenkripsi</span>
                </div>
                <span>&copy; {{ date('Y') }} Wedding Invitation</span>
            </div>
        </div>

        <!-- ================= RIGHT COLUMN: BRAND SHOWCASE ================= -->
        <div class="hidden lg:flex lg:w-1/2 bg-stone-900 text-stone-100 p-12 lg:p-16 flex-col justify-between relative overflow-hidden">
            <!-- Decorative Frosted Glow Blobs -->
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-[#C25E2E]/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-amber-600/10 blur-3xl pointer-events-none"></div>

            <!-- Top Tracker -->
            <div class="relative z-10 flex items-center justify-between text-xs text-stone-400 font-medium">
                <span class="tracking-widest uppercase">PREWEDDING / IDENTITY CONTROL</span>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-stone-300">SYSTEM OPERATIONAL</span>
                </div>
            </div>

            <!-- Middle Big Message -->
            <div class="relative z-10 max-w-lg my-auto py-12">
                <span class="text-xs font-bold tracking-widest text-[#C25E2E] uppercase block mb-3">
                    PORTAL KHUSUS PENGELOLA
                </span>
                <h2 class="text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight font-serif mb-6">
                    Akses kontrol penuh kebahagiaan setiap pasangan.
                </h2>
                <p class="text-stone-400 text-sm leading-relaxed mb-10">
                    Masuk untuk mengelola pesanan undangan digital, kustomisasi data mempelai dan jadwal acara, serta memantau kehadiran tamu undangan dalam satu alur data yang terpadu.
                </p>

                <!-- 3 Grid Features -->
                <div class="grid grid-cols-3 gap-4 pt-6 border-t border-stone-800">
                    <div>
                        <div class="w-7 h-7 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-xs font-bold text-stone-200">Data Terenkripsi</h3>
                        <p class="text-[10px] text-stone-400 mt-0.5">Keamanan data mempelai</p>
                    </div>

                    <div>
                        <div class="w-7 h-7 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-xs font-bold text-stone-200">Server-Side Kilat</h3>
                        <p class="text-[10px] text-stone-400 mt-0.5">Performa cepat tanpa lag</p>
                    </div>

                    <div>
                        <div class="w-7 h-7 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-xs font-bold text-stone-200">Sinkron WhatsApp</h3>
                        <p class="text-[10px] text-stone-400 mt-0.5">Link sebar tamu otomatis</p>
                    </div>
                </div>
            </div>

            <!-- Right Bottom Security Tip -->
            <div class="relative z-10 flex items-center gap-2 text-xs text-stone-400 border-t border-stone-800 pt-6">
                <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span class="text-[11px]">Jangan membagikan kata sandi akun administrator kepada siapapun.</span>
            </div>
        </div>
    </div>
</body>
</html>
