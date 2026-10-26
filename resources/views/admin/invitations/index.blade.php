<x-admin.layout title="Daftar Undangan Digital">
    <div class="space-y-6">
        <!-- Top Breadcrumbs & Title Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-800 transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-stone-800 font-medium">Undangan Digital</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight font-serif">
                    Daftar Undangan Digital
                </h1>
                <p class="text-xs sm:text-sm text-stone-500 mt-0.5">
                    Kelola profil mempelai, rangkaian jadwal acara, galeri prewedding, dan status publikasi.
                </p>
            </div>
        </div>

        <!-- Metric Stat Cards Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            <!-- 1. Total Undangan -->
            <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-stone-500">Total Undangan</span>
                    <span class="w-2 h-2 rounded-full bg-stone-400"></span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-stone-900 font-serif">{{ $totalInvitations }}</span>
                    <span class="text-[11px] text-stone-400">undangan</span>
                </div>
            </div>

            <!-- 2. Published (Tayang) -->
            <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-emerald-700">Published (Tayang)</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-emerald-900 font-serif">{{ $publishedInvitations }}</span>
                    <span class="text-[11px] text-emerald-600">aktif diakses</span>
                </div>
            </div>

            <!-- 3. Draft (Penyusunan) -->
            <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-amber-700">Draft (Penyusunan)</span>
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-amber-900 font-serif">{{ $draftInvitations }}</span>
                    <span class="text-[11px] text-amber-600">proses edit</span>
                </div>
            </div>

            <!-- 4. Archived (Selesai) -->
            <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-stone-600">Archived (Selesai)</span>
                    <span class="w-2 h-2 rounded-full bg-stone-400"></span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-stone-800 font-serif">{{ $archivedInvitations }}</span>
                    <span class="text-[11px] text-stone-500">acara lewat</span>
                </div>
            </div>
        </div>

        <!-- Invitations Table Card with Server-Side Alpine.js Controller -->
        <x-admin.card>
            <div x-data="invitationsTable()" class="space-y-5">
                <!-- Filter Controls Bar (Identical Layout to Orders) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-3">
                    <!-- Live Search (Debounced 350ms - Otomatis Search saat Diketik) -->
                    <div class="md:col-span-6 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            x-model="search"
                            @input.debounce.350ms="filterInvitations()"
                            placeholder="Cari slug URL, nama mempelai, pemesan, no order..." 
                            class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-stone-200 bg-white/70 backdrop-blur-xs text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                        >
                    </div>

                    <!-- Filter Status Publikasi -->
                    <div class="md:col-span-3">
                        <select 
                            x-model="status" 
                            @change="filterInvitations()"
                            class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white/70 backdrop-blur-xs text-xs text-stone-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                        >
                            <option value="all">Semua Status Publikasi</option>
                            <option value="published">PUBLISHED (Tayang)</option>
                            <option value="draft">DRAFT (Penyusunan)</option>
                            <option value="archived">ARCHIVED (Selesai)</option>
                        </select>
                    </div>

                    <!-- Filter Template Desain -->
                    <div class="md:col-span-3">
                        <select 
                            x-model="template" 
                            @change="filterInvitations()"
                            class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white/70 backdrop-blur-xs text-xs text-stone-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                        >
                            <option value="all">Semua Template Desain</option>
                            @foreach ($templates as $tmpl)
                                <option value="{{ $tmpl->id }}">{{ $tmpl->name }} ({{ $tmpl->category }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Table Container with Relative Loading Overlay -->
                <div class="relative min-h-[220px]">
                    <!-- Loading Overlay (Frosted Blur Spinner) -->
                    <div 
                        x-show="isLoading" 
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute inset-0 bg-white/60 backdrop-blur-xs rounded-2xl flex items-center justify-center z-10"
                        x-cloak
                    >
                        <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-2xl bg-stone-900/85 text-white text-xs font-medium shadow-xl">
                            <svg class="w-4 h-4 animate-spin text-[#C25E2E]" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span>Memuat data undangan...</span>
                        </div>
                    </div>

                    <!-- Rendered Server-Side Table Body -->
                    <div id="invitations-table-container">
                        @include('admin.invitations._table', ['invitations' => $invitations])
                    </div>
                </div>
            </div>
        </x-admin.card>
    </div>
</x-admin.layout>
