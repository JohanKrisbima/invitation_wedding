<x-admin.layout title="Buku Tamu & Konfirmasi RSVP">
    @php
        $selectedId = $selectedInvitation->id ?? null;
    @endphp

    <div 
        x-data="guestbook({ invitationId: {{ $selectedId ? $selectedId : 'null' }} })" 
        class="space-y-6"
    >
        <!-- Top Breadcrumbs & Title Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <!-- Breadcrumbs -->
                <div class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-800 transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-stone-800 font-medium">Buku Tamu & RSVP</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight font-serif">
                    Buku Tamu & Konfirmasi RSVP
                </h1>
                <p class="text-xs sm:text-sm text-stone-500 mt-0.5">
                    Pantau respon kehadiran tamu undangan dan kelola ucapan serta doa restu yang masuk.
                </p>
            </div>

            <!-- Top Quick Info -->
            @if ($selectedInvitation)
                <div class="flex items-center gap-2">
                    <a 
                        href="{{ route('admin.guests.index', ['invitation_id' => $selectedInvitation->id]) }}" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white hover:bg-stone-50 text-stone-700 border border-stone-200/80 text-xs font-semibold shadow-xs transition duration-150"
                    >
                        <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Ke Menu Daftar Tamu</span>
                    </a>
                </div>
            @endif
        </div>

        @if ($invitations->isEmpty())
            <!-- Empty Invitations Alert -->
            <x-admin.card>
                <div class="py-12 text-center text-stone-500">
                    <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <p class="font-bold text-stone-800 text-sm">Belum Ada Undangan Digital yang Dibuat</p>
                    <p class="text-xs text-stone-400 mt-1 max-w-md mx-auto">
                        Untuk memantau buku tamu dan RSVP, Anda perlu memvalidasi pesanan dan membuat undangan digital terlebih dahulu di menu Pesanan.
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
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
                <!-- 1. Total RSVP -->
                <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-stone-500">Total Konfirmasi</span>
                        <span class="w-2 h-2 rounded-full bg-stone-400"></span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-stone-900 font-serif">{{ $totalRsvps }}</span>
                        <span class="text-[11px] text-stone-400">tamu RSVP</span>
                    </div>
                </div>

                <!-- 2. Tamu Hadir & Total Pax -->
                <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-emerald-700">Pasti Hadir</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-emerald-900 font-serif">{{ $attendingPax }}</span>
                        <span class="text-[11px] text-emerald-600">pax ({{ $attendingCount }} tamu)</span>
                    </div>
                </div>

                <!-- 3. Tidak Hadir & Ragu-ragu -->
                <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-rose-700">Tidak Hadir / Ragu</span>
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-rose-900 font-serif">{{ $notAttendingCount + $tentativeCount }}</span>
                        <span class="text-[11px] text-rose-600">tamu</span>
                    </div>
                </div>

                <!-- 4. Total Ucapan & Doa -->
                <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-amber-700">Ucapan & Doa</span>
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-amber-900 font-serif">{{ $totalMessages }}</span>
                        <span class="text-[11px] text-amber-600">pesan masuk</span>
                    </div>
                </div>
            </div>

            <!-- Main Content Container with Tabs -->
            <x-admin.card>
                <div class="space-y-5">
                    <!-- Tab Selector Bar -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-stone-100">
                        <div class="flex items-center gap-2 p-1 bg-stone-100 rounded-2xl w-full sm:w-auto">
                            <!-- Tab 1: RSVP Button -->
                            <button 
                                type="button" 
                                @click="switchTab('rsvp')"
                                :class="activeTab === 'rsvp' ? 'bg-white text-stone-900 font-bold shadow-xs' : 'text-stone-500 hover:text-stone-800'"
                                class="flex-1 sm:flex-none px-4 py-2 rounded-xl text-xs transition flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4 text-[#C25E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Konfirmasi Kehadiran (RSVP)</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-stone-100 text-stone-700">
                                    {{ $totalRsvps }}
                                </span>
                            </button>

                            <!-- Tab 2: Messages Button -->
                            <button 
                                type="button" 
                                @click="switchTab('messages')"
                                :class="activeTab === 'messages' ? 'bg-white text-stone-900 font-bold shadow-xs' : 'text-stone-500 hover:text-stone-800'"
                                class="flex-1 sm:flex-none px-4 py-2 rounded-xl text-xs transition flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                <span>Buku Tamu & Ucapan Doa</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-[#C25E2E]">
                                    {{ $totalMessages }}
                                </span>
                            </button>
                        </div>

                        <!-- Right Download Action (Contextual based on Active Tab) -->
                        <div>
                            <!-- Export Button for RSVP -->
                            <button 
                                x-show="activeTab === 'rsvp'" 
                                type="button" 
                                @click="exportRsvpExcel()"
                                :disabled="isExportingRsvp"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-3.5 py-2 rounded-2xl bg-white hover:bg-stone-50 border border-stone-200/80 text-stone-700 text-xs font-semibold shadow-xs transition duration-150 disabled:opacity-50"
                                title="Download rekap kehadiran tamu untuk katering & wedding organizer"
                            >
                                <svg x-show="!isExportingRsvp" class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7.2 14.5L9.6 13.8l-2.2 3.7H5.2l3.3-5.2L5.4 7.5h2.2l2 3.5 2-3.5h2.2l-3.1 4.8 3.3 5.2h-2.2zM19 17h-4v-2h4v2zm0-4h-4v-2h4v2zm0-4h-4V7h4v2z" />
                                </svg>
                                <svg x-show="isExportingRsvp" class="w-3.5 h-3.5 animate-spin text-[#C25E2E]" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span x-text="isExportingRsvp ? 'Mengunduh...' : 'Download Rekap RSVP (.xlsx)'"></span>
                            </button>

                            <!-- Export Button for Messages -->
                            <button 
                                x-show="activeTab === 'messages'" 
                                type="button" 
                                @click="exportMessagesExcel()"
                                :disabled="isExportingMsg"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-3.5 py-2 rounded-2xl bg-white hover:bg-stone-50 border border-stone-200/80 text-stone-700 text-xs font-semibold shadow-xs transition duration-150 disabled:opacity-50"
                                title="Download seluruh ucapan doa dari tamu undangan"
                                x-cloak
                            >
                                <svg x-show="!isExportingMsg" class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7.2 14.5L9.6 13.8l-2.2 3.7H5.2l3.3-5.2L5.4 7.5h2.2l2 3.5 2-3.5h2.2l-3.1 4.8 3.3 5.2h-2.2zM19 17h-4v-2h4v2zm0-4h-4v-2h4v2zm0-4h-4V7h4v2z" />
                                </svg>
                                <svg x-show="isExportingMsg" class="w-3.5 h-3.5 animate-spin text-[#C25E2E]" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span x-text="isExportingMsg ? 'Mengunduh...' : 'Download Ucapan Tamu (.xlsx)'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- ================= TAB 1 VIEW: RSVP SECTION ================= -->
                    <div x-show="activeTab === 'rsvp'" class="space-y-4">
                        <!-- Filters Bar for RSVP -->
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                            <!-- Search by Guest Name / Note -->
                            <div class="sm:col-span-8 relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    x-model="rsvpSearch"
                                    @input.debounce.350ms="filterRsvp()"
                                    placeholder="Cari nama tamu yang konfirmasi, isi pesan..." 
                                    class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-stone-200 bg-white/70 backdrop-blur-xs text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                >
                            </div>

                            <!-- Filter by Attendance Status -->
                            <div class="sm:col-span-4">
                                <select 
                                    x-model="rsvpStatus"
                                    @change="filterRsvp()"
                                    class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white/70 backdrop-blur-xs text-xs text-stone-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                >
                                    <option value="all">Semua Status Kehadiran</option>
                                    <option value="attending">Hadir (Attending)</option>
                                    <option value="not_attending">Tidak Hadir</option>
                                    <option value="tentative">Ragu-ragu (Tentative)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Relative RSVP Table Container with Loading Overlay -->
                        <div class="relative min-h-[220px]">
                            <!-- Loading Overlay -->
                            <div 
                                x-show="isRsvpLoading" 
                                class="absolute inset-0 bg-white/60 backdrop-blur-xs rounded-2xl flex items-center justify-center z-10"
                                x-cloak
                            >
                                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-2xl bg-stone-900/85 text-white text-xs font-medium shadow-xl">
                                    <svg class="w-4 h-4 animate-spin text-[#C25E2E]" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span>Memuat data RSVP...</span>
                                </div>
                            </div>

                            <div id="rsvp-table-container">
                                @include('admin.guestbook._rsvp_table', ['rsvps' => $rsvps, 'selectedInvitation' => $selectedInvitation])
                            </div>
                        </div>
                    </div>

                    <!-- ================= TAB 2 VIEW: GUEST MESSAGES SECTION ================= -->
                    <div x-show="activeTab === 'messages'" class="space-y-4" x-cloak>
                        <!-- Filters Bar for Messages -->
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                            <!-- Search by Guest Name / Message Text -->
                            <div class="sm:col-span-8 relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    x-model="msgSearch"
                                    @input.debounce.350ms="filterMessages()"
                                    placeholder="Cari pengirim atau kata dalam ucapan doa..." 
                                    class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-stone-200 bg-white/70 backdrop-blur-xs text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                >
                            </div>

                            <!-- Filter by Approval Status -->
                            <div class="sm:col-span-4">
                                <select 
                                    x-model="msgStatus"
                                    @change="filterMessages()"
                                    class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white/70 backdrop-blur-xs text-xs text-stone-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                >
                                    <option value="all">Semua Status Moderasi</option>
                                    <option value="approved">Ditampilkan di Publik</option>
                                    <option value="hidden">Disembunyikan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Relative Messages Container with Loading Overlay -->
                        <div class="relative min-h-[220px]">
                            <!-- Loading Overlay -->
                            <div 
                                x-show="isMsgLoading" 
                                class="absolute inset-0 bg-white/60 backdrop-blur-xs rounded-2xl flex items-center justify-center z-10"
                                x-cloak
                            >
                                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-2xl bg-stone-900/85 text-white text-xs font-medium shadow-xl">
                                    <svg class="w-4 h-4 animate-spin text-[#C25E2E]" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span>Memuat ucapan doa...</span>
                                </div>
                            </div>

                            <div id="messages-list-container">
                                @include('admin.guestbook._messages_list', ['messages' => $messages, 'selectedInvitation' => $selectedInvitation])
                            </div>
                        </div>
                    </div>
                </div>
            </x-admin.card>
        @endif
    </div>
</x-admin.layout>
