<x-admin.layout title="Manajemen Tamu Undangan">
    @php
        $selectedId = $selectedInvitation->id ?? null;
    @endphp

    <div 
        x-data="guestsTable({ invitationId: {{ $selectedId ? $selectedId : 'null' }} })" 
        class="space-y-6"
    >
        <!-- Top Breadcrumbs & Title Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <!-- Breadcrumbs -->
                <div class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-800 transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-stone-800 font-medium">Daftar Tamu</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight font-serif">
                    Manajemen Tamu Undangan
                </h1>
                <p class="text-xs sm:text-sm text-stone-500 mt-0.5">
                    Kelola daftar tamu, bagikan link personal via WhatsApp, dan pantau konfirmasi kehadiran (RSVP).
                </p>
            </div>

            <!-- Top Action Controls -->
            @if ($selectedInvitation)
                <div class="flex items-center gap-2.5">
                    <!-- Dropdown Kelola Data Excel (Download & Import) -->
                    <div class="relative" x-data="{ openExcelMenu: false }">
                        <button 
                            type="button" 
                            @click="openExcelMenu = !openExcelMenu"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white hover:bg-stone-50 text-stone-700 border border-stone-200/80 text-xs font-semibold shadow-xs transition duration-150"
                        >
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7.2 14.5L9.6 13.8l-2.2 3.7H5.2l3.3-5.2L5.4 7.5h2.2l2 3.5 2-3.5h2.2l-3.1 4.8 3.3 5.2h-2.2zM19 17h-4v-2h4v2zm0-4h-4v-2h4v2zm0-4h-4V7h4v2z" />
                            </svg>
                            <span>Kelola Data Excel</span>
                            <svg class="w-3.5 h-3.5 text-stone-400 transition-transform duration-200" :class="{ 'rotate-180': openExcelMenu }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Popover Menu -->
                        <div 
                            x-show="openExcelMenu" 
                            @click.away="openExcelMenu = false"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                            class="absolute right-0 sm:left-0 sm:right-auto mt-2 w-72 rounded-2xl bg-white/95 backdrop-blur-md border border-stone-200 shadow-xl py-1.5 z-30 divide-y divide-stone-100"
                            x-cloak
                        >
                            <div class="px-3.5 py-2">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Import & Export Excel</p>
                            </div>

                            <div class="py-1">
                                <!-- Option 1: Download Tamu & Link Undangan (.xlsx) -->
                                <button 
                                    type="button" 
                                    @click="openExcelMenu = false; exportGuestsExcel()"
                                    :disabled="isExporting"
                                    class="w-full text-left px-3.5 py-2.5 flex items-center gap-3 text-xs text-stone-700 hover:bg-emerald-50/70 hover:text-emerald-900 transition group disabled:opacity-50"
                                >
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100/70 text-emerald-700 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition">
                                        <svg x-show="!isExporting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        <svg x-show="isExporting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-bold block text-stone-800 group-hover:text-emerald-950">Download Tamu & Link</span>
                                        <span class="text-[10px] text-stone-400 block mt-0.5">Semua data & link personal (.xlsx)</span>
                                    </div>
                                </button>

                                <!-- Option 2: Import Tamu Massal -->
                                <button 
                                    type="button" 
                                    @click="openExcelMenu = false; showImportModal = true"
                                    class="w-full text-left px-3.5 py-2.5 flex items-center gap-3 text-xs text-stone-700 hover:bg-amber-50/70 hover:text-amber-900 transition group"
                                >
                                    <div class="w-8 h-8 rounded-xl bg-amber-100/70 text-[#C25E2E] flex items-center justify-center shrink-0 group-hover:bg-[#C25E2E] group-hover:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-bold block text-stone-800 group-hover:text-amber-950">Import Tamu Massal</span>
                                        <span class="text-[10px] text-stone-400 block mt-0.5">Upload file Excel atau tempel teks</span>
                                    </div>
                                </button>
                            </div>

                            <div class="py-1">
                                <!-- Option 3: Download Template Excel Kosong -->
                                <button 
                                    type="button" 
                                    @click="openExcelMenu = false; downloadExcelTemplate()"
                                    class="w-full text-left px-3.5 py-2 flex items-center gap-2.5 text-[11px] text-stone-500 hover:bg-stone-50 hover:text-stone-800 transition"
                                >
                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span>Download Contoh Template Excel</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Tambah Tamu Manual -->
                    <button 
                        type="button" 
                        @click="showAddModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-[#C25E2E] hover:bg-[#A84B20] text-white text-xs font-semibold shadow-sm shadow-[#C25E2E]/25 transition duration-150"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Tamu</span>
                    </button>
                </div>
            @endif
        </div>

        @if ($invitations->isEmpty())
            <!-- Empty Invitations Alert -->
            <x-admin.card>
                <div class="py-12 text-center text-stone-500">
                    <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="font-bold text-stone-800 text-sm">Belum Ada Undangan Digital yang Dibuat</p>
                    <p class="text-xs text-stone-400 mt-1 max-w-md mx-auto">
                        Untuk mengelola daftar tamu, Anda perlu memvalidasi pesanan dan membuat undangan digital terlebih dahulu di menu Pesanan.
                    </p>
                    <div class="mt-4">
                        <a 
                            href="{{ route('admin.orders.index') }}" 
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-[#C25E2E] text-white text-xs font-semibold shadow-xs hover:bg-[#A84B20] transition"
                        >
                            <span>Buka Menu Pesanan</span>
                        </a>
                    </div>
                </div>
            </x-admin.card>
        @else
            <!-- Invitation Selector Bar -->
            <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-100/70 text-[#C25E2E] flex items-center justify-center font-bold text-xs shrink-0 border border-amber-200/50">
                        <svg class="w-5 h-5 text-[#C25E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400 block">Undangan yang Dipilih:</span>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-stone-900">
                                {{ $selectedInvitation->couple ? $selectedInvitation->couple->partner_one_name . ' & ' . $selectedInvitation->couple->partner_two_name : $selectedInvitation->slug }}
                            </span>
                            <span class="text-xs font-mono text-stone-400">(/{{ $selectedInvitation->slug }})</span>
                        </div>
                    </div>
                </div>

                <!-- Dropdown Switcher -->
                <div class="flex items-center gap-2">
                    <label class="text-xs font-semibold text-stone-600 whitespace-nowrap">Ganti Undangan:</label>
                    <select 
                        @change="switchInvitation($event.target.value)"
                        class="px-3.5 py-2 rounded-2xl border border-stone-200 bg-white text-xs font-semibold text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                    >
                        @foreach ($invitations as $inv)
                            <option value="{{ $inv->id }}" {{ $selectedInvitation->id === $inv->id ? 'selected' : '' }}>
                                {{ $inv->couple ? $inv->couple->partner_one_name . ' & ' . $inv->couple->partner_two_name : $inv->slug }} 
                                ({{ $inv->order->order_number ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Metric Stat Cards Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                <!-- 1. Total Tamu -->
                <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-stone-500">Total Tamu</span>
                        <span class="w-2 h-2 rounded-full bg-stone-400"></span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-stone-900 font-serif">{{ $totalGuests }}</span>
                        <span class="text-[11px] text-stone-400">tamu terdaftar</span>
                    </div>
                </div>

                <!-- 2. Terkirim via WA -->
                <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-emerald-700">Sudah Dikirim (WA)</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-emerald-900 font-serif">{{ $sentGuests }}</span>
                        <span class="text-[11px] text-emerald-600">tersebar</span>
                    </div>
                </div>

                <!-- 3. Konfirmasi Hadir -->
                <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-sky-700">Konfirmasi Hadir</span>
                        <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-sky-900 font-serif">{{ $attendingGuests }}</span>
                        <span class="text-[11px] text-sky-600">pax / orang</span>
                    </div>
                </div>

                <!-- 4. Belum Dikirim -->
                <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-amber-700">Belum Dikirim</span>
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-amber-900 font-serif">{{ $unsentGuests }}</span>
                        <span class="text-[11px] text-amber-600">pending WA</span>
                    </div>
                </div>
            </div>

            <!-- Table Card with Live Search & AJAX Filters -->
            <x-admin.card>
                <div class="space-y-5">
                    <!-- Filter Controls Bar -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-3">
                        <!-- Live Search (Debounced 350ms) -->
                        <div class="md:col-span-6 relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                x-model="search"
                                @input.debounce.350ms="filterGuests()"
                                placeholder="Cari nama tamu, no WhatsApp, kota/alamat..." 
                                class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-stone-200 bg-white/70 backdrop-blur-xs text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                            >
                        </div>

                        <!-- Filter Status RSVP -->
                        <div class="md:col-span-3">
                            <select 
                                x-model="rsvp" 
                                @change="filterGuests()"
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white/70 backdrop-blur-xs text-xs text-stone-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                            >
                                <option value="all">Semua Status RSVP</option>
                                <option value="attending">Hadir (Attending)</option>
                                <option value="not_attending">Tidak Hadir</option>
                                <option value="tentative">Ragu-ragu (Tentative)</option>
                                <option value="no_rsvp">Belum Konfirmasi</option>
                            </select>
                        </div>

                        <!-- Filter Status Pengiriman WA -->
                        <div class="md:col-span-3">
                            <select 
                                x-model="sent" 
                                @change="filterGuests()"
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white/70 backdrop-blur-xs text-xs text-stone-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                            >
                                <option value="all">Semua Status Kirim</option>
                                <option value="sent">Sudah Terkirim (WA)</option>
                                <option value="unsent">Belum Dikirim</option>
                            </select>
                        </div>
                    </div>

                    <!-- Table Container with Relative Loading Overlay -->
                    <div class="relative min-h-[220px]">
                        <!-- Loading Overlay -->
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
                                <span>Memuat data tamu...</span>
                            </div>
                        </div>

                        <!-- Table Body Container -->
                        <div id="guests-table-container">
                            @include('admin.guests._table', ['guests' => $guests, 'selectedInvitation' => $selectedInvitation])
                        </div>
                    </div>
                </div>
            </x-admin.card>

            <!-- ================= MODAL 1: TAMBAH TAMU MANUAL ================= -->
            <div 
                x-show="showAddModal" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/50 backdrop-blur-xs flex items-center justify-center p-4"
                x-cloak
            >
                <div 
                    @click.away="showAddModal = false"
                    class="bg-white rounded-3xl p-6 sm:p-7 max-w-md w-full shadow-2xl border border-stone-200 space-y-5"
                >
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                        <div>
                            <h3 class="text-base font-bold text-stone-900 font-serif">Tambah Tamu Baru</h3>
                            <p class="text-xs text-stone-500">Tambahkan 1 nama tamu ke undangan ini.</p>
                        </div>
                        <button type="button" @click="showAddModal = false" class="text-stone-400 hover:text-stone-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.guests.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="invitation_id" value="{{ $selectedInvitation->id }}">

                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1.5">Nama Lengkap Tamu *</label>
                            <input 
                                type="text" 
                                name="name" 
                                required 
                                placeholder="Contoh: Bpk. Joko Widodo & Keluarga"
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-stone-50/50 text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1.5">Nomor WhatsApp (Opsional)</label>
                            <input 
                                type="text" 
                                name="phone" 
                                placeholder="Contoh: 081234567890"
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-stone-50/50 text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1.5">Kota / Alamat (Opsional)</label>
                            <input 
                                type="text" 
                                name="address" 
                                placeholder="Contoh: Jakarta Selatan / Surabaya"
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-stone-50/50 text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                            >
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <input 
                                type="checkbox" 
                                name="is_sent" 
                                id="add_is_sent" 
                                value="1"
                                class="w-4 h-4 rounded text-[#C25E2E] focus:ring-[#C25E2E] border-stone-300"
                            >
                            <label for="add_is_sent" class="text-xs text-stone-600 font-medium cursor-pointer">
                                Tandai undangan langsung sudah dikirim
                            </label>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-stone-100">
                            <button 
                                type="button" 
                                @click="showAddModal = false"
                                class="px-4 py-2 rounded-2xl border border-stone-200 text-stone-600 text-xs font-semibold hover:bg-stone-50 transition"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                class="px-5 py-2 rounded-2xl bg-[#C25E2E] hover:bg-[#A84B20] text-white text-xs font-bold shadow-sm shadow-[#C25E2E]/25 transition"
                            >
                                Simpan Tamu
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ================= MODAL 2: IMPORT MASSAL (EXCEL & QUICK PASTE) ================= -->
            <div 
                x-show="showImportModal" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/50 backdrop-blur-xs flex items-center justify-center p-4"
                x-cloak
            >
                <div 
                    @click.away="!isUploadingChunks && (showImportModal = false)"
                    class="bg-white rounded-3xl p-6 sm:p-7 max-w-lg w-full shadow-2xl border border-stone-200 space-y-5"
                >
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                        <div>
                            <h3 class="text-base font-bold text-stone-900 font-serif">Import Tamu Massal</h3>
                            <p class="text-xs text-stone-500">Mendukung file Excel (.xlsx) dengan proses chunking anti-RTO.</p>
                        </div>
                        <button 
                            type="button" 
                            @click="!isUploadingChunks && (showImportModal = false)" 
                            :disabled="isUploadingChunks"
                            class="text-stone-400 hover:text-stone-700 disabled:opacity-30 disabled:cursor-not-allowed"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Import Tab Selector -->
                    <div class="flex items-center gap-2 p-1 bg-stone-100 rounded-2xl">
                        <button 
                            type="button" 
                            @click="!isUploadingChunks && (importTab = 'excel')"
                            :class="importTab === 'excel' ? 'bg-white text-stone-900 font-bold shadow-xs' : 'text-stone-500 hover:text-stone-800'"
                            :disabled="isUploadingChunks"
                            class="flex-1 py-1.5 rounded-xl text-xs transition flex items-center justify-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7.2 14.5L9.6 13.8l-2.2 3.7H5.2l3.3-5.2L5.4 7.5h2.2l2 3.5 2-3.5h2.2l-3.1 4.8 3.3 5.2h-2.2zM19 17h-4v-2h4v2zm0-4h-4v-2h4v2zm0-4h-4V7h4v2z" />
                            </svg>
                            <span>Upload File Excel (.xlsx)</span>
                        </button>
                        <button 
                            type="button" 
                            @click="!isUploadingChunks && (importTab = 'paste')"
                            :class="importTab === 'paste' ? 'bg-white text-stone-900 font-bold shadow-xs' : 'text-stone-500 hover:text-stone-800'"
                            :disabled="isUploadingChunks"
                            class="flex-1 py-1.5 rounded-xl text-xs transition flex items-center justify-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span>Tempel Teks (Quick Paste)</span>
                        </button>
                    </div>

                    <!-- ================= TAB 1: UPLOAD FILE EXCEL (CHUNKED & ANTI-RTO) ================= -->
                    <div x-show="importTab === 'excel'" class="space-y-4">
                        <!-- Dropzone / Picker -->
                        <div>
                            <input 
                                type="file" 
                                id="excel_guest_file" 
                                @change="handleExcelFile($event)" 
                                accept=".xlsx,.xls" 
                                class="hidden"
                            >

                            <!-- State 1: Belum Memilih File -->
                            <div 
                                x-show="!excelRows.length && !isParsingExcel"
                                onclick="document.getElementById('excel_guest_file').click()"
                                class="p-6 rounded-2xl border-2 border-dashed border-stone-200 hover:border-[#C25E2E] bg-stone-50/50 hover:bg-amber-50/20 cursor-pointer text-center transition group"
                            >
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-2.5 group-hover:scale-105 transition">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7.2 14.5L9.6 13.8l-2.2 3.7H5.2l3.3-5.2L5.4 7.5h2.2l2 3.5 2-3.5h2.2l-3.1 4.8 3.3 5.2h-2.2zM19 17h-4v-2h4v2zm0-4h-4v-2h4v2zm0-4h-4V7h4v2z" />
                                    </svg>
                                </div>
                                <p class="text-xs font-bold text-stone-800">Klik untuk Pilih File Excel (.xlsx / .xls)</p>
                                <p class="text-[11px] text-stone-400 mt-0.5">Dapat membaca data ratusan baris secara otomatis</p>
                            </div>

                            <!-- State 2: Sedang Membaca Excel di Browser -->
                            <div 
                                x-show="isParsingExcel" 
                                class="p-6 rounded-2xl bg-stone-50 border border-stone-200 text-center space-y-2"
                                x-cloak
                            >
                                <svg class="w-6 h-6 animate-spin text-[#C25E2E] mx-auto" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <p class="text-xs font-semibold text-stone-700">Membaca dan memvalidasi file Excel...</p>
                            </div>

                            <!-- State 3: File Terpilih & Siap Diimpor -->
                            <div 
                                x-show="excelRows.length > 0 && !isParsingExcel" 
                                class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 flex items-center justify-between"
                                x-cloak
                            >
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7.2 14.5L9.6 13.8l-2.2 3.7H5.2l3.3-5.2L5.4 7.5h2.2l2 3.5 2-3.5h2.2l-3.1 4.8 3.3 5.2h-2.2zM19 17h-4v-2h4v2zm0-4h-4v-2h4v2zm0-4h-4V7h4v2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-emerald-950 truncate max-w-[220px]" x-text="excelFileName"></p>
                                        <p class="text-[11px] text-emerald-700 font-medium">
                                            ✓ <span class="font-bold" x-text="excelRows.length"></span> tamu terdeteksi siap diimpor
                                        </p>
                                    </div>
                                </div>
                                <button 
                                    type="button" 
                                    @click="resetExcelImport()" 
                                    :disabled="isUploadingChunks"
                                    class="text-xs text-stone-500 hover:text-rose-600 font-semibold px-2.5 py-1 rounded-lg hover:bg-white transition"
                                >
                                    Ganti File
                                </button>
                            </div>
                        </div>

                        <!-- Animated Progress Bar (Anti-RTO Chunk Processing) -->
                        <div x-show="isUploadingChunks" class="space-y-2 p-3.5 rounded-2xl bg-stone-50 border border-stone-200" x-cloak>
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-semibold text-stone-700 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 animate-spin text-[#C25E2E]" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span x-text="progressStatus"></span>
                                </span>
                                <span class="font-bold text-[#C25E2E]" x-text="uploadProgress + '%'"></span>
                            </div>
                            <div class="w-full h-2.5 bg-stone-200/80 rounded-full overflow-hidden">
                                <div 
                                    class="h-full bg-gradient-to-r from-[#C25E2E] to-amber-500 rounded-full transition-all duration-300"
                                    :style="'width: ' + uploadProgress + '%'"
                                ></div>
                            </div>
                            <p class="text-[10px] text-stone-400">
                                ⚡ Chunking aktif: Data dikirim secara bertahap (batch 50) agar proses stabil dan bebas timeout server (anti-RTO).
                            </p>
                        </div>

                        <!-- Template Excel Download Option -->
                        <div class="p-3.5 rounded-2xl bg-stone-50 border border-stone-200/60 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold text-stone-800 block">Butuh contoh template?</span>
                                <span class="text-[11px] text-stone-500">Gunakan file contoh resmi berformat .xlsx</span>
                            </div>
                            <button 
                                type="button" 
                                @click="downloadExcelTemplate()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-stone-200 text-[#C25E2E] hover:bg-amber-50 text-xs font-bold shadow-xs transition"
                            >
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7.2 14.5L9.6 13.8l-2.2 3.7H5.2l3.3-5.2L5.4 7.5h2.2l2 3.5 2-3.5h2.2l-3.1 4.8 3.3 5.2h-2.2zM19 17h-4v-2h4v2zm0-4h-4v-2h4v2zm0-4h-4V7h4v2z" />
                                </svg>
                                <span>Download Format Excel (.xlsx)</span>
                            </button>
                        </div>

                        <!-- Tombol Aksi Excel -->
                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-stone-100">
                            <button 
                                type="button" 
                                @click="showImportModal = false"
                                :disabled="isUploadingChunks"
                                class="px-4 py-2 rounded-2xl border border-stone-200 text-stone-600 text-xs font-semibold hover:bg-stone-50 disabled:opacity-40 transition"
                            >
                                Batal
                            </button>
                            <button 
                                type="button" 
                                @click="startChunkedExcelImport()"
                                :disabled="excelRows.length === 0 || isUploadingChunks || isParsingExcel"
                                class="inline-flex items-center gap-2 px-5 py-2 rounded-2xl bg-[#C25E2E] hover:bg-[#A84B20] text-white text-xs font-bold shadow-sm shadow-[#C25E2E]/25 disabled:opacity-50 disabled:cursor-not-allowed transition"
                            >
                                <svg x-show="isUploadingChunks" class="w-3.5 h-3.5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span x-text="isUploadingChunks ? 'Sedang Mengimpor...' : 'Mulai Import Excel (Anti RTO)'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- ================= TAB 2: QUICK PASTE TEXTAREA ================= -->
                    <div x-show="importTab === 'paste'" class="space-y-4">
                        <form method="POST" action="{{ route('admin.guests.import') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="invitation_id" value="{{ $selectedInvitation->id }}">

                            <div class="space-y-2">
                                <label class="block text-xs font-semibold text-stone-700">
                                    Tempel Daftar Nama Tamu (1 baris = 1 nama):
                                </label>
                                <textarea 
                                    name="raw_text" 
                                    rows="6" 
                                    placeholder="Contoh format:&#10;Bpk. Budi Santoso, 081234567890, Jakarta&#10;Sdr. Dimas Pratama, Surabaya&#10;Ibu Siti Rahmawati, 087811228899&#10;Keluarga Besar H. Ridwan, Bandung"
                                    class="w-full px-4 py-3 rounded-2xl border border-stone-200 bg-stone-50/50 text-xs font-mono text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                ></textarea>
                                <p class="text-[11px] text-stone-400">
                                    Format: <span class="font-mono text-stone-600">Nama, No WA, Alamat</span> atau <span class="font-mono text-stone-600">Nama, Alamat</span> (pisahkan koma).
                                </p>
                            </div>

                            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-stone-100">
                                <button 
                                    type="button" 
                                    @click="showImportModal = false"
                                    class="px-4 py-2 rounded-2xl border border-stone-200 text-stone-600 text-xs font-semibold hover:bg-stone-50 transition"
                                >
                                    Batal
                                </button>
                                <button 
                                    type="submit" 
                                    class="px-5 py-2 rounded-2xl bg-[#C25E2E] hover:bg-[#A84B20] text-white text-xs font-bold shadow-sm shadow-[#C25E2E]/25 transition"
                                >
                                    Mulai Import Teks
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ================= MODAL 3: EDIT TAMU ================= -->
            <div 
                x-show="showEditModal" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/50 backdrop-blur-xs flex items-center justify-center p-4"
                x-cloak
            >
                <div 
                    @click.away="showEditModal = false"
                    class="bg-white rounded-3xl p-6 sm:p-7 max-w-md w-full shadow-2xl border border-stone-200 space-y-5"
                >
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                        <div>
                            <h3 class="text-base font-bold text-stone-900 font-serif">Edit Data Tamu</h3>
                            <p class="text-xs text-stone-500">Perbarui nama atau nomor kontak tamu.</p>
                        </div>
                        <button type="button" @click="showEditModal = false" class="text-stone-400 hover:text-stone-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form :action="editGuest.actionUrl" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1.5">Nama Tamu *</label>
                            <input 
                                type="text" 
                                name="name" 
                                x-model="editGuest.name" 
                                required 
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-stone-50/50 text-xs text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1.5">Nomor WhatsApp</label>
                            <input 
                                type="text" 
                                name="phone" 
                                x-model="editGuest.phone" 
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-stone-50/50 text-xs text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1.5">Kota / Alamat (Opsional)</label>
                            <input 
                                type="text" 
                                name="address" 
                                x-model="editGuest.address" 
                                placeholder="Contoh: Jakarta Selatan / Surabaya"
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-stone-50/50 text-xs text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                            >
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-stone-100">
                            <button 
                                type="button" 
                                @click="showEditModal = false"
                                class="px-4 py-2 rounded-2xl border border-stone-200 text-stone-600 text-xs font-semibold hover:bg-stone-50 transition"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                class="px-5 py-2 rounded-2xl bg-[#C25E2E] hover:bg-[#A84B20] text-white text-xs font-bold shadow-sm shadow-[#C25E2E]/25 transition"
                            >
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-admin.layout>
