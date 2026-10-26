<div class="overflow-x-auto -mx-6">
    <table class="w-full text-left text-xs text-stone-700">
        <thead class="text-[11px] uppercase tracking-wider text-stone-400 bg-stone-50/70 border-y border-stone-200/50">
            <tr>
                <th scope="col" class="py-3 px-6 font-semibold">Mempelai</th>
                <th scope="col" class="py-3 px-6 font-semibold">Slug & Tautan</th>
                <th scope="col" class="py-3 px-6 font-semibold">Template</th>
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
                            <div class="w-9 h-9 rounded-2xl bg-[#C25E2E]/10 text-[#C25E2E] flex items-center justify-center font-bold text-xs shrink-0 border border-[#C25E2E]/20">
                                @if ($inv->couple)
                                    {{ mb_substr($inv->couple->partner_one_name, 0, 1) }}&{{ mb_substr($inv->couple->partner_two_name, 0, 1) }}
                                @else
                                    <svg class="w-4 h-4 text-[#C25E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="font-bold text-stone-900 text-xs">
                                    {{ $inv->couple ? $inv->couple->partner_one_name . ' & ' . $inv->couple->partner_two_name : 'Belum diisi' }}
                                </p>
                                <p class="text-[10px] text-stone-400">
                                    ID: #{{ $inv->id }}
                                </p>
                            </div>
                        </div>
                    </td>

                    <!-- Slug & Tautan -->
                    <td class="py-4 px-6 whitespace-nowrap">
                        <div class="inline-flex items-center gap-1.5">
                            <span class="px-2.5 py-1 rounded-xl bg-stone-100/90 text-stone-700 font-mono text-[11px]">
                                /{{ $inv->slug }}
                            </span>
                            <button 
                                type="button" 
                                @click="copyLink('{{ $inv->slug }}')"
                                class="p-1 rounded-lg text-stone-400 hover:text-stone-700 hover:bg-stone-100 transition"
                                title="Salin Link Undangan"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </button>
                        </div>
                    </td>

                    <!-- Template -->
                    <td class="py-4 px-6 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-stone-100/90 text-stone-700 font-medium text-[11px]">
                            {{ $inv->template->name ?? '-' }}
                        </span>
                    </td>

                    <!-- Pemesan (Order) -->
                    <td class="py-4 px-6 whitespace-nowrap">
                        <div class="font-medium text-stone-800">{{ $inv->order->customer_name ?? '-' }}</div>
                        <div class="text-[11px] text-stone-400 font-mono">{{ $inv->order->order_number ?? '-' }}</div>
                    </td>

                    <!-- Status Publikasi -->
                    <td class="py-4 px-6 whitespace-nowrap">
                        <x-admin.badge :status="$inv->publication_status" />
                    </td>

                    <!-- Aksi -->
                    <td class="py-4 px-6 text-right whitespace-nowrap">
                        <div class="inline-flex items-center justify-end gap-2">
                            <!-- Tombol WA Pemesan (jika ada nomor telepon) -->
                            @if ($inv->order && $inv->order->customer_phone)
                                <a 
                                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $inv->order->customer_phone) }}?text={{ urlencode('Halo Kak ' . $inv->order->customer_name . ', link undangan digital Anda: ' . url('/' . $inv->slug)) }}" 
                                    target="_blank"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-medium text-xs transition"
                                    title="Hubungi Pemesan via WhatsApp"
                                >
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                    <span>WA</span>
                                </a>
                            @endif

                            <!-- Tombol Kelola Konten Undangan -->
                            <a 
                                href="{{ route('admin.invitations.edit', $inv->id) }}" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-stone-100 text-stone-700 hover:bg-[#C25E2E] hover:text-white font-medium text-xs transition-colors duration-150"
                                title="Kelola Konten Undangan"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>Kelola</span>
                            </a>

                            <!-- Tombol Lihat Halaman Publik -->
                            <a 
                                href="{{ url('/' . $inv->slug) }}" 
                                target="_blank"
                                class="p-1.5 rounded-xl text-stone-400 hover:text-stone-700 hover:bg-stone-100 transition"
                                title="Buka Halaman Publik"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
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
                            <p class="text-xs text-stone-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter status Anda.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Footer: Pagination Info & Interactive AJAX Controls -->
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 pt-4 border-t border-stone-200/50 text-xs text-stone-500">
    <div>
        @if ($invitations->total() > 0)
            Menampilkan <span class="font-bold text-stone-800">{{ $invitations->firstItem() }}</span> - 
            <span class="font-bold text-stone-800">{{ $invitations->lastItem() }}</span> dari 
            <span class="font-bold text-stone-800">{{ $invitations->total() }}</span> undangan
        @else
            0 undangan ditemukan
        @endif
    </div>

    <!-- Interactive Pagination Links (AJAX via Alpine) -->
    @if ($invitations->hasPages())
        <div class="flex items-center gap-1.5">
            {{-- Previous Page Link --}}
            @if ($invitations->onFirstPage())
                <span class="px-3 py-1.5 rounded-xl border border-stone-200 text-stone-300 cursor-not-allowed">
                    &laquo;
                </span>
            @else
                <button 
                    type="button" 
                    @click="fetchPage('{{ $invitations->previousPageUrl() }}')"
                    class="px-3 py-1.5 rounded-xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 transition"
                >
                    &laquo;
                </button>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($invitations->getUrlRange(max(1, $invitations->currentPage() - 2), min($invitations->lastPage(), $invitations->currentPage() + 2)) as $page => $url)
                @if ($page == $invitations->currentPage())
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
            @if ($invitations->hasMorePages())
                <button 
                    type="button" 
                    @click="fetchPage('{{ $invitations->nextPageUrl() }}')"
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
