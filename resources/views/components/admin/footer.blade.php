<footer class="mt-8 pt-4 pb-2">
    <div class="px-5 py-3.5 rounded-2xl bg-white/60 backdrop-blur-md border border-stone-200/60 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-stone-500 font-medium">
        <!-- Sisi Kiri: Brand, Hak Cipta & Tagline -->
        <div class="flex items-center flex-wrap justify-center sm:justify-start gap-2.5">
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded-lg bg-[#C25E2E]/15 text-[#C25E2E] flex items-center justify-center font-bold text-[10px] shadow-2xs">
                    W
                </div>
                <span class="font-bold text-stone-800 tracking-tight">WeddingAdmin</span>
            </div>
            <span class="text-stone-300 hidden sm:inline">•</span>
            <span class="text-stone-500">&copy; {{ date('Y') }} Wedding Invitation Studio. Hak cipta dilindungi.</span>
        </div>

        <!-- Sisi Kanan: Status Server, Tautan Bantuan, dan Versi -->
        <div class="flex items-center flex-wrap justify-center sm:justify-end gap-3 text-[11px]">
            <!-- Status Server Operational -->
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50/80 text-emerald-700 border border-emerald-200/50 font-semibold shadow-2xs">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span>Sistem Aktif</span>
            </div>

            <span class="text-stone-300 hidden sm:inline">•</span>

            <!-- Versi Aplikasi -->
            <div class="inline-flex items-center gap-1.5 font-mono text-stone-500 bg-stone-100/80 px-2 py-0.5 rounded-lg border border-stone-200/40">
                <svg class="w-3 h-3 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                <span>v1.2.0</span>
            </div>
        </div>
    </div>
</footer>
