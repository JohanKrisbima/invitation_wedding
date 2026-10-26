<x-admin.layout title="Dashboard Monitoring">
    <div class="space-y-6">
        <!-- Page Title & Period Indicator -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight font-serif">
                    Dashboard Monitoring
                </h1>
                <p class="text-xs sm:text-sm text-stone-500 mt-0.5">
                    Ringkasan performa penjualan, monitoring status pesanan masuk, dan progres undangan digital.
                </p>
            </div>

            <div class="inline-flex items-center gap-2 self-start sm:self-auto bg-white/80 backdrop-blur-md px-4 py-2 rounded-2xl border border-stone-200/60 shadow-xs text-xs font-semibold text-stone-700">
                <span class="w-2.5 h-2.5 rounded-full bg-[#C25E2E] animate-pulse"></span>
                <span>Periode: {{ now()->translatedFormat('F Y') }}</span>
            </div>
        </div>

        <!-- 4 Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <!-- 1. Total Pendapatan / Omset Lunas -->
            <x-admin.stat-card 
                label="Total Pendapatan" 
                :value="'Rp ' . number_format($totalRevenue, 0, ',', '.')" 
                subtext="Dari pesanan berstatus Lunas (PAID)" 
                icon="chart" 
                color="terracotta" 
            />

            <!-- 2. Total Pesanan -->
            <x-admin.stat-card 
                label="Total Pesanan" 
                :value="$totalOrders" 
                :subtext="$unpaidOrders . ' pesanan belum bayar'" 
                icon="orders" 
                color="sky" 
            />

            <!-- 3. Menunggu Validasi -->
            <x-admin.stat-card 
                label="Menunggu Validasi" 
                :value="$pendingOrders" 
                subtext="Perlu konfirmasi transfer" 
                icon="pending" 
                color="amber" 
            />

            <!-- 4. Undangan Aktif Publik -->
            <x-admin.stat-card 
                label="Undangan Aktif (Live)" 
                :value="$activeInvitations" 
                :subtext="'Dari ' . $totalInvitations . ' total undangan digital'" 
                icon="completed" 
                color="emerald" 
            />
        </div>

        <!-- Monitoring Section: 2 Columns Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- SISI KIRI (7 Kolom): Monitoring Pesanan Terbaru -->
            <div class="lg:col-span-7 space-y-6">
                <x-admin.card>
                    <div class="space-y-4">
                        <!-- Header Card -->
                        <div class="flex items-center justify-between pb-3 border-b border-stone-200/60">
                            <div>
                                <h2 class="text-base font-bold text-stone-900 tracking-tight font-serif">
                                    Monitoring Pesanan Terbaru
                                </h2>
                                <p class="text-xs text-stone-500 mt-0.5">
                                    Transaksi pemesanan yang baru masuk dari pelanggan.
                                </p>
                            </div>

                            <a 
                                href="{{ route('admin.orders.index') }}" 
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-[#C25E2E] hover:text-[#A84B20] transition"
                            >
                                <span>Buka Menu Pesanan</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>

                        <!-- Info Banner Alur Kerja -->
                        <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/60 flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-xs text-amber-900 leading-relaxed">
                                <strong>Catatan Admin:</strong> Halaman Dashboard ini khusus untuk <strong>monitoring</strong>. Untuk memvalidasi bukti pembayaran dan membuatkan undangan pengantin, silakan gunakan tombol aksi di <a href="{{ route('admin.orders.index') }}" class="font-bold underline hover:text-[#C25E2E]">Menu Pesanan</a>.
                            </div>
                        </div>

                        <!-- Table Feed Pesanan Terbaru -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-stone-700">
                                <thead>
                                    <tr class="border-b border-stone-200/60 text-[11px] font-bold uppercase tracking-wider text-stone-400">
                                        <th class="py-2.5 pr-3">Pesanan</th>
                                        <th class="py-2.5 px-3">Template</th>
                                        <th class="py-2.5 px-3">Total</th>
                                        <th class="py-2.5 px-3">Status Bayar</th>
                                        <th class="py-2.5 pl-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-100">
                                    @forelse ($recentOrders as $order)
                                        <tr class="hover:bg-stone-50/60 transition">
                                            <!-- Pesanan & Pemesan -->
                                            <td class="py-3 pr-3">
                                                <div class="font-bold text-stone-900">{{ $order->customer_name }}</div>
                                                <div class="text-[11px] font-mono text-stone-400">{{ $order->order_number }} • {{ $order->created_at->diffForHumans() }}</div>
                                            </td>

                                            <!-- Template -->
                                            <td class="py-3 px-3">
                                                <span class="font-medium text-stone-800">{{ $order->template->name ?? '-' }}</span>
                                                <span class="block text-[10px] text-stone-400">{{ $order->template->category ?? '' }}</span>
                                            </td>

                                            <!-- Total Harga -->
                                            <td class="py-3 px-3 font-semibold text-stone-900">
                                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                            </td>

                                            <!-- Status Bayar -->
                                            <td class="py-3 px-3">
                                                @if ($order->payment_status === 'paid')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200/60">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                                        PAID
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200/60">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                                        UNPAID
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Aksi Cepat -->
                                            <td class="py-3 pl-3 text-right whitespace-nowrap">
                                                <a 
                                                    href="{{ route('admin.orders.index', ['search' => $order->order_number]) }}" 
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-white border border-stone-200 hover:bg-stone-100 text-stone-700 text-[11px] font-semibold transition"
                                                >
                                                    <span>Kelola</span>
                                                    <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-8 text-center text-stone-400">
                                                Belum ada data pesanan masuk.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </x-admin.card>
            </div>

            <!-- SISI KANAN (5 Kolom): Status Undangan & Template Terpopuler -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Status Progres Undangan Digital -->
                <x-admin.card>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-stone-200/60">
                            <div>
                                <h2 class="text-base font-bold text-stone-900 tracking-tight font-serif">
                                    Status Undangan Digital
                                </h2>
                                <p class="text-xs text-stone-500 mt-0.5">
                                    Monitoring publikasi website undangan.
                                </p>
                            </div>
                            <a 
                                href="{{ route('admin.invitations.index') }}" 
                                class="inline-flex items-center gap-1 text-xs font-bold text-[#C25E2E] hover:text-[#A84B20] transition"
                            >
                                <span>Lihat Semua</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>

                        <!-- Progress Breakdown Boxes -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-200/60">
                                <span class="text-[11px] font-semibold text-emerald-700 block">Published (Aktif)</span>
                                <div class="mt-1 flex items-baseline gap-1.5">
                                    <span class="text-2xl font-extrabold text-emerald-900 font-serif">{{ $activeInvitations }}</span>
                                    <span class="text-[11px] text-emerald-600">undangan</span>
                                </div>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/60">
                                <span class="text-[11px] font-semibold text-amber-700 block">Draft (Penyusunan)</span>
                                <div class="mt-1 flex items-baseline gap-1.5">
                                    <span class="text-2xl font-extrabold text-amber-900 font-serif">{{ $draftInvitations }}</span>
                                    <span class="text-[11px] text-amber-600">undangan</span>
                                </div>
                            </div>
                        </div>

                        <!-- List Undangan Terbaru -->
                        <div class="space-y-2 pt-1">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400 block">Undangan Terbaru</span>
                            @forelse ($recentInvitations as $inv)
                                <div class="flex items-center justify-between p-2.5 rounded-2xl bg-stone-50/70 border border-stone-200/50 hover:bg-white transition">
                                    <div class="truncate mr-2">
                                        <div class="text-xs font-bold text-stone-900 truncate">
                                            {{ $inv->couple ? $inv->couple->partner_one_name . ' & ' . $inv->couple->partner_two_name : $inv->slug }}
                                        </div>
                                        <div class="text-[10px] text-stone-400 font-mono">
                                            /{{ $inv->slug }} • {{ $inv->template->name ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="shrink-0 flex items-center gap-2">
                                        <x-admin.badge :status="$inv->publication_status" />
                                        <a 
                                            href="{{ route('admin.invitations.edit', $inv->id) }}" 
                                            class="w-7 h-7 rounded-xl bg-white border border-stone-200 text-stone-600 hover:text-[#C25E2E] flex items-center justify-center shadow-2xs transition"
                                            title="Edit Undangan"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-stone-400 py-2">Belum ada undangan digital.</p>
                            @endforelse
                        </div>
                    </div>
                </x-admin.card>

                <!-- Template Terpopuler -->
                <x-admin.card>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-stone-200/60">
                            <h2 class="text-sm font-bold text-stone-900 tracking-tight font-serif">
                                Template Paling Banyak Dipesan
                            </h2>
                            <span class="text-[11px] font-semibold text-stone-400">Total: {{ $totalTemplates }} Template</span>
                        </div>

                        <div class="space-y-2">
                            @foreach ($popularTemplates as $tmpl)
                                <div class="flex items-center justify-between p-2.5 rounded-2xl bg-stone-50/60 border border-stone-200/50">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-xl bg-[#C25E2E]/10 text-[#C25E2E] flex items-center justify-center font-bold text-xs">
                                            #{{ $loop->iteration }}
                                        </div>
                                        <div>
                                            <span class="text-xs font-bold text-stone-800 block">{{ $tmpl->name }}</span>
                                            <span class="text-[10px] text-stone-400 block">{{ $tmpl->category }} • Rp {{ number_format($tmpl->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-xl bg-white border border-stone-200 text-xs font-extrabold text-stone-800 shadow-2xs">
                                        {{ $tmpl->orders_count }} pesanan
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-admin.card>
            </div>
        </div>
    </div>
</x-admin.layout>
