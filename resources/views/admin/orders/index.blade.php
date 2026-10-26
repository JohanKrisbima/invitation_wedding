<x-admin.layout title="Manajemen Pesanan (Orders)">
    <div class="space-y-6">
        <!-- Top Breadcrumbs & Title Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-800 transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-stone-800 font-medium">Pesanan (Orders)</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight font-serif">
                    Manajemen Pesanan
                </h1>
                <p class="text-xs sm:text-sm text-stone-500 mt-0.5">
                    Kelola pesanan pelanggan, verifikasi bukti transfer manual via WhatsApp, dan buatkan undangan digital.
                </p>
            </div>

            <!-- Tombol Tambah Pesanan Baru -->
            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('admin.orders.create') }}" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-[#C25E2E] hover:bg-[#A84B20] text-white text-xs font-semibold shadow-sm shadow-[#C25E2E]/25 transition duration-150"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Buat Pesanan Baru</span>
                </a>
            </div>
        </div>

        <!-- Metric Stat Cards Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            <!-- 1. Total Pesanan -->
            <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-stone-500">Total Pesanan</span>
                    <span class="w-2 h-2 rounded-full bg-stone-400"></span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-stone-900 font-serif">{{ $totalOrders }}</span>
                    <span class="text-[11px] text-stone-400">pesanan</span>
                </div>
            </div>

            <!-- 2. Belum Bayar (Unpaid) -->
            <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-amber-700">Belum Bayar (UNPAID)</span>
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-amber-900 font-serif">{{ $unpaidOrders }}</span>
                    <span class="text-[11px] text-amber-600">perlu transfer</span>
                </div>
            </div>

            <!-- 3. Menunggu Konfirmasi (Pending) -->
            <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-sky-700">Menunggu Validasi</span>
                    <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-sky-900 font-serif">{{ $pendingOrders }}</span>
                    <span class="text-[11px] text-sky-600">siap divalidasi</span>
                </div>
            </div>

            <!-- 4. Selesai (Completed) -->
            <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-emerald-700">Lunas & Selesai</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-emerald-900 font-serif">{{ $completedOrders }}</span>
                    <span class="text-[11px] text-emerald-600">tuntas</span>
                </div>
            </div>
        </div>

        <!-- Orders Table Card with Server-Side Alpine.js Controller -->
        <x-admin.card>
            <div x-data="ordersTable()" class="space-y-5">
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
                            @input.debounce.350ms="filterOrders()"
                            placeholder="Cari nomor pesanan (ORD-xxxx), nama pemesan, no WhatsApp..." 
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
