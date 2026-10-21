<header class="relative z-40 w-full bg-white/70 backdrop-blur-md rounded-3xl border border-stone-200/60 shadow-xs px-6 py-3.5 flex items-center justify-between gap-4">
    <!-- Left Info Pills -->
    <div class="flex items-center flex-wrap gap-3 text-xs text-stone-600 font-medium">
        <!-- App Indicator -->
        <div class="flex items-center gap-2 bg-stone-100/80 px-3 py-1.5 rounded-full border border-stone-200/40">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="font-semibold text-stone-800">WeddingAdmin</span>
        </div>

        <!-- Date Pill -->
        <div class="hidden sm:flex items-center gap-2 bg-stone-100/80 px-3 py-1.5 rounded-full border border-stone-200/40">
            <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>{{ now()->translatedFormat('l, d M Y') }}</span>
        </div>

        <!-- Live Clock Pill (Alpine.js live ticker) -->
        <div 
            x-data="{
                time: '',
                updateClock() {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    this.time = `${hours}.${minutes}.${seconds} WIB`;
                }
            }"
            x-init="updateClock(); setInterval(() => updateClock(), 1000)"
            class="hidden md:flex items-center gap-2 bg-stone-100/80 px-3 py-1.5 rounded-full border border-stone-200/40"
        >
            <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span x-text="time">12:00:00 WIB</span>
        </div>
    </div>

    <!-- Right: User Avatar & Dropdown -->
    <div x-data="{ open: false }" class="relative">
        <button 
            @click="open = !open" 
            class="flex items-center gap-3 p-1 sm:pr-3 rounded-full hover:bg-stone-100/60 transition duration-150 focus:outline-none"
        >
            <!-- Initials Avatar -->
            <div class="w-9 h-9 rounded-full bg-[#C25E2E] text-white flex items-center justify-center font-bold text-xs shadow-xs">
                {{ strtoupper(substr(auth()->user()->name ?? 'SA', 0, 2)) }}
            </div>
            <!-- Name & Role -->
            <div class="hidden sm:block text-left">
                <div class="text-xs font-bold text-stone-800 leading-tight">
                    {{ auth()->user()->name ?? 'Super Admin' }}
                </div>
                <div class="text-[10px] uppercase tracking-wider text-stone-500 font-medium">
                    ADMINISTRATOR
                </div>
            </div>
            <!-- Chevron -->
            <svg class="w-4 h-4 text-stone-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div 
            x-show="open" 
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.outside="open = false"
            class="absolute right-0 mt-2.5 w-56 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-stone-200/80 py-2 z-50 text-xs"
            x-cloak
        >
            <div class="px-4 py-2 border-b border-stone-100">
                <p class="font-semibold text-stone-800">{{ auth()->user()->name ?? 'Super Admin' }}</p>
                <p class="text-[11px] text-stone-500 truncate">{{ auth()->user()->email ?? 'admin@wedding.test' }}</p>
            </div>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-stone-700 hover:bg-stone-50 transition">
                <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <div class="my-1 border-t border-stone-100"></div>

            <form method="POST" action="{{ route('logout') ?? '#' }}">
                @csrf
                <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-rose-600 hover:bg-rose-50 transition">
                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </div>
</header>
