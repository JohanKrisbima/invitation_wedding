<x-admin.layout title="Dashboard Pesanan">
    <div class="space-y-6">
        <!-- Page Title & Period Indicator -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight font-serif">
                    Dashboard
                </h1>
                <p class="text-xs sm:text-sm text-stone-500 mt-0.5">
                    Ringkasan pesanan masuk dan aktivitas undangan digital periode ini.
                </p>
            </div>

            <div class="inline-flex items-center gap-2 self-start sm:self-auto bg-white/70 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-stone-200/60 shadow-xs text-xs font-semibold text-stone-700">
                <span class="w-2 h-2 rounded-full bg-[#C25E2E]"></span>
                <span>Periode: {{ now()->translatedFormat('F Y') }}</span>
            </div>
        </div>

        <!-- Metric Stat Cards (4 Cards Grid) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <!-- 1. Total Pesanan -->
            <x-admin.stat-card 
                label="Total Pesanan" 
                :value="$totalOrders" 
                subtext="Semua pesanan terdaftar" 
                icon="orders" 
                color="terracotta" 
            />

            <!-- 2. Menunggu Konfirmasi -->
            <x-admin.stat-card 
                label="Menunggu Konfirmasi" 
                :value="$pendingOrders" 
                subtext="Perlu tindak lanjut WA" 
                icon="pending" 
                color="amber" 
            />

            <!-- 3. Sedang Dikerjakan -->
            <x-admin.stat-card 
                label="Sedang Dikerjakan" 
                :value="$inProgressOrders" 
                subtext="Proses isi konten" 
                icon="progress" 
                color="sky" 
            />

            <!-- 4. Lunas & Selesai -->
            <x-admin.stat-card 
                label="Lunas & Aktif" 
                :value="$completedOrders" 
                subtext="Tautan aktif publik" 
                icon="completed" 
                color="emerald" 
            />
        </div>

        <!-- Orders Table Card with Server-Side Alpine.js Controller -->
        <x-admin.card>
            <div x-data="ordersTable()" class="space-y-5">
                <!-- Header: Title & Action Controls -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-stone-900 tracking-tight">
                            Manajemen Pesanan Masuk
                        </h2>
                        <p class="text-xs text-stone-500 mt-0.5">
                            Data diproses secara server-side. Gunakan filter untuk menyaring data dengan cepat.
                        </p>
                    </div>

                    <!-- Action Button: Buat Undangan -->
                    <div class="flex items-center gap-3">
                        <button 
                            type="button" 
                            @click="showToast('Fitur form pemesanan baru akan segera dibuka!', 'info')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-[#C25E2E] hover:bg-[#A84B20] text-white text-xs font-semibold shadow-sm shadow-[#C25E2E]/25 transition-all duration-200"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>+ Buat Pesanan Baru</span>
                        </button>
                    </div>
                </div>

                <!-- Filter Controls Bar -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-3 pt-2">
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
                            @input.debounce.350ms="filterOrders()"
                            placeholder="Cari nomor pesanan, nama pemesan, no WA..." 
                            class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-stone-200 bg-white/70 backdrop-blur-xs text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                        >
                    </div>

                    <!-- Filter Status Order -->
                    <div class="md:col-span-3">
                        <select 
                            x-model="status" 
                            @change="filterOrders()"
                            class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white/70 backdrop-blur-xs text-xs text-stone-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                        >
                            <option value="all">Semua Status Order</option>
                            <option value="pending">Pending (Menunggu)</option>
                            <option value="in_progress">In Progress (Dikerjakan)</option>
                            <option value="completed">Completed (Selesai)</option>
                            <option value="cancelled">Cancelled (Dibatalkan)</option>
                        </select>
                    </div>

                    <!-- Filter Payment Status -->
                    <div class="md:col-span-3">
                        <select 
                            x-model="payment" 
                            @change="filterOrders()"
                            class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white/70 backdrop-blur-xs text-xs text-stone-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                        >
                            <option value="all">Semua Pembayaran</option>
                            <option value="paid">PAID (Sudah Bayar)</option>
                            <option value="unpaid">UNPAID (Belum Bayar)</option>
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
                            <span>Memuat data pesanan...</span>
                        </div>
                    </div>

                    <!-- Rendered Server-Side Table Body -->
                    <div id="orders-table-container">
                        @include('admin.orders._table', ['orders' => $orders])
                    </div>
                </div>
            </div>
        </x-admin.card>
    </div>
</x-admin.layout>
