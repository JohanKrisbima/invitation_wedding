<div class="overflow-x-auto -mx-6">
    <table class="w-full text-left text-xs text-stone-700">
        <thead class="text-[11px] uppercase tracking-wider text-stone-400 bg-stone-50/70 border-y border-stone-200/50">
            <tr>
                <th scope="col" class="py-3 px-6 font-semibold">No. Pesanan</th>
                <th scope="col" class="py-3 px-6 font-semibold">Pemesan</th>
                <th scope="col" class="py-3 px-6 font-semibold">WhatsApp</th>
                <th scope="col" class="py-3 px-6 font-semibold">Template</th>
                <th scope="col" class="py-3 px-6 font-semibold">Tgl Acara</th>
                <th scope="col" class="py-3 px-6 font-semibold">Bayar (Manual)</th>
                <th scope="col" class="py-3 px-6 font-semibold">Status Order</th>
                <th scope="col" class="py-3 px-6 font-semibold text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @forelse ($orders as $order)
                <tr class="hover:bg-amber-50/40 transition-colors duration-150">
                    <!-- No. Pesanan -->
                    <td class="py-4 px-6 font-bold text-stone-900 whitespace-nowrap">
                        <span class="text-[#C25E2E]">{{ $order->order_number }}</span>
                    </td>

                    <!-- Pemesan -->
                    <td class="py-4 px-6 font-medium text-stone-800 whitespace-nowrap">
                        {{ $order->customer_name }}
                    </td>

                    <!-- WhatsApp -->
                    <td class="py-4 px-6 text-stone-600 font-mono whitespace-nowrap">
                        {{ $order->customer_phone }}
                    </td>

                    <!-- Template -->
                    <td class="py-4 px-6 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-stone-100/90 text-stone-700 font-medium text-[11px]">
                            {{ $order->template->name ?? 'Custom' }}
                        </span>
                    </td>

                    <!-- Tgl Acara -->
                    <td class="py-4 px-6 text-stone-600 whitespace-nowrap">
                        {{ $order->wedding_date ? $order->wedding_date->translatedFormat('d M Y') : '-' }}
                    </td>

                    <!-- Bayar (Manual) -->
                    <td class="py-4 px-6 whitespace-nowrap">
                        <x-admin.badge :status="$order->payment_status" />
                    </td>

                    <!-- Status Order -->
                    <td class="py-4 px-6 whitespace-nowrap">
                        <x-admin.badge :status="$order->order_status" />
                    </td>

                    <!-- Aksi -->
                    <td class="py-4 px-6 text-right whitespace-nowrap">
                        <div class="inline-flex items-center justify-end gap-2">
                            <!-- Tombol WA -->
                            <a 
                                href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}?text={{ urlencode('Halo Kak ' . $order->customer_name . ', terkait pesanan undangan digital nomor ' . $order->order_number . '...') }}" 
                                target="_blank"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-medium text-xs transition"
                                title="Hubungi via WhatsApp"
                            >
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                                <span>WA</span>
                            </a>

                            <!-- Tombol Kelola Undangan -->
                            @if ($order->invitation)
                                <a 
                                    href="{{ route('admin.invitations.edit', $order->invitation->id) }}" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-stone-100 text-stone-700 hover:bg-[#C25E2E] hover:text-white font-medium text-xs transition-colors duration-150"
                                    title="Kelola Konten Undangan"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Kelola</span>
                                </a>
                                <a 
                                    href="{{ url('/' . $order->invitation->slug) }}" 
                                    target="_blank"
                                    class="p-1.5 rounded-xl text-stone-400 hover:text-stone-700 hover:bg-stone-100 transition"
                                    title="Lihat Halaman Publik"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @else
                                <button 
                                    type="button"
                                    @click="confirmCreateInvitation('{{ route('admin.orders.create-invitation', $order->id) }}', '{{ addslashes($order->customer_name) }}', '{{ $order->order_number }}')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition duration-150"
                                    title="Validasi Pembayaran Manual & Buat Undangan"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Konfirmasi & Buat Undangan</span>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="py-12 text-center text-stone-500">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <p class="font-semibold text-stone-700">Tidak ada pesanan ditemukan</p>
                            <p class="text-xs text-stone-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter status Anda.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Footer: Pagination Info & Controls -->
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 pt-4 border-t border-stone-200/50 text-xs text-stone-500">
    <div>
        @if ($orders->total() > 0)
            Menampilkan <span class="font-bold text-stone-800">{{ $orders->firstItem() }}</span> - 
            <span class="font-bold text-stone-800">{{ $orders->lastItem() }}</span> dari 
            <span class="font-bold text-stone-800">{{ $orders->total() }}</span> pesanan
        @else
            0 pesanan ditemukan
        @endif
    </div>

    <!-- Interactive Pagination Links (AJAX via Alpine) -->
    @if ($orders->hasPages())
        <div class="flex items-center gap-1.5">
            {{-- Previous Page Link --}}
            @if ($orders->onFirstPage())
                <span class="px-3 py-1.5 rounded-xl border border-stone-200 text-stone-300 cursor-not-allowed">
                    &laquo;
                </span>
            @else
                <button 
                    type="button" 
                    @click="fetchPage('{{ $orders->previousPageUrl() }}')"
                    class="px-3 py-1.5 rounded-xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 transition"
                >
                    &laquo;
                </button>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
                @if ($page == $orders->currentPage())
                    <span class="px-3.5 py-1.5 rounded-xl font-bold bg-[#C25E2E] text-white shadow-xs">
                        {{ $page }}
                    </span>
                @else
                    <button 
                        type="button" 
                        @click="fetchPage('{{ $url }}')"
                        class="px-3.5 py-1.5 rounded-xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 transition"
                    >
                        {{ $page }}
                    </button>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($orders->hasMorePages())
                <button 
                    type="button" 
                    @click="fetchPage('{{ $orders->nextPageUrl() }}')"
                    class="px-3 py-1.5 rounded-xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 transition"
                >
                    &raquo;
                </button>
            @else
                <span class="px-3 py-1.5 rounded-xl border border-stone-200 text-stone-300 cursor-not-allowed">
                    &raquo;
                </span>
            @endif
        </div>
    @endif
</div>
