<x-admin.layout title="Katalog Undangan Digital">
    <div class="space-y-6">
        <!-- Header Page -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <!-- Breadcrumbs -->
                <div class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-800 transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-stone-800 font-medium">Undangan</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight font-serif">
                    Daftar Undangan Digital
                </h1>
                <p class="text-xs sm:text-sm text-stone-500 mt-0.5">
                    Kelola profil mempelai, jadwal acara, galeri prewedding, dan status publikasi.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('admin.dashboard') }}" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white hover:bg-stone-50 text-stone-700 border border-stone-200/80 text-xs font-semibold shadow-xs transition"
                >
                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Filter & Search Card -->
        <x-admin.card>
            <form method="GET" action="{{ route('admin.invitations.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                <!-- Search Box -->
                <div class="relative flex-1 max-w-md">
                    <svg class="w-4 h-4 text-stone-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari slug, mempelai, atau pemesan..." 
                        class="w-full pl-10 pr-4 py-2 bg-stone-50/70 border border-stone-200 rounded-2xl text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/20 focus:border-[#C25E2E] transition"
                    >
                </div>

                <!-- Status Filter -->
                <div class="flex items-center gap-3">
                    <select 
                        name="status" 
                        onchange="this.form.submit()"
                        class="py-2 px-3.5 bg-stone-50/70 border border-stone-200 rounded-2xl text-xs text-stone-700 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/20 focus:border-[#C25E2E] transition"
                    >
                        <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>Semua Publikasi</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published (Tayang)</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>

                    @if(request('search') || (request('status') && request('status') !== 'all'))
                        <a 
                            href="{{ route('admin.invitations.index') }}" 
                            class="px-3 py-2 rounded-2xl bg-stone-100 hover:bg-stone-200 text-stone-600 text-xs font-medium transition"
                            title="Reset Filter"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </x-admin.card>

        <!-- Invitations Table Card -->
        <x-admin.card>
            <div class="overflow-x-auto -mx-6">
                <table class="w-full text-left text-xs text-stone-700">
                    <thead class="text-[11px] uppercase tracking-wider text-stone-400 bg-stone-50/70 border-y border-stone-200/50">
                        <tr>
                            <th scope="col" class="py-3 px-6 font-semibold">Mempelai & Pasangan</th>
                            <th scope="col" class="py-3 px-6 font-semibold">Slug & URL Link</th>
                            <th scope="col" class="py-3 px-6 font-semibold">Desain Template</th>
                            <th scope="col" class="py-3 px-6 font-semibold">Pemesan (Order)</th>
                            <th scope="col" class="py-3 px-6 font-semibold">Status Publikasi</th>
                            <th scope="col" class="py-3 px-6 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse ($invitations as $inv)
                            <tr class="hover:bg-amber-50/40 transition-colors duration-150">
                                <!-- Mempelai -->
                                <td class="py-4 px-6 font-medium text-stone-900 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-[#C25E2E]/10 text-[#C25E2E] flex items-center justify-center font-bold text-xs shrink-0 border border-[#C25E2E]/20">
                                            @if($inv->couple)
                                                {{ mb_substr($inv->couple->partner_one_name, 0, 1) }}&{{ mb_substr($inv->couple->partner_two_name, 0, 1) }}
                                            @else
                                                <svg class="w-4 h-4 text-[#C25E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-bold text-stone-900 text-sm">
                                                {{ $inv->couple ? $inv->couple->partner_one_name . ' & ' . $inv->couple->partner_two_name : 'Belum diisi' }}
                                            </p>
                                            <p class="text-[11px] text-stone-400">
                                                ID: #{{ $inv->id }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Slug / Link -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-stone-100 font-mono text-[11px] text-stone-700">
                                        <span>/{{ $inv->slug }}</span>
                                    </div>
                                </td>

                                <!-- Template -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-stone-100 text-stone-700 font-medium text-[11px]">
                                        {{ $inv->template->name ?? '-' }}
                                    </span>
                                </td>

                                <!-- Pemesan -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <p class="font-medium text-stone-800">{{ $inv->order->customer_name ?? '-' }}</p>
                                    <p class="text-[11px] text-stone-400 font-mono">{{ $inv->order->order_number ?? '-' }}</p>
                                </td>

                                <!-- Status Publikasi -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <x-admin.badge :status="$inv->publication_status" />
                                </td>

                                <!-- Aksi -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <!-- Tombol Kelola Konten -->
                                        <a 
                                            href="{{ route('admin.invitations.edit', $inv->id) }}" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#C25E2E] hover:bg-[#A84B20] text-white font-semibold text-xs shadow-xs shadow-[#C25E2E]/20 transition"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Kelola Konten</span>
                                        </a>

                                        <!-- Preview Public URL -->
                                        <a 
                                            href="{{ url('/' . $inv->slug) }}" 
                                            target="_blank"
                                            class="p-2 rounded-xl text-stone-500 hover:text-stone-800 hover:bg-stone-100 transition"
                                            title="Buka Halaman Tamu"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-stone-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <p class="font-semibold text-stone-700">Tidak ada undangan ditemukan</p>
                                        <p class="text-xs text-stone-400 mt-1">Belum ada data undangan atau filter pencarian tidak cocok.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if ($invitations->hasPages())
                <div class="mt-6 pt-4 border-t border-stone-200/50 flex items-center justify-between text-xs text-stone-500">
                    <div>
                        Menampilkan <span class="font-bold text-stone-800">{{ $invitations->firstItem() }}</span> - 
                        <span class="font-bold text-stone-800">{{ $invitations->lastItem() }}</span> dari 
                        <span class="font-bold text-stone-800">{{ $invitations->total() }}</span> undangan
                    </div>
                    <div>
                        {{ $invitations->links() }}
                    </div>
                </div>
            @endif
        </x-admin.card>
    </div>
</x-admin.layout>
