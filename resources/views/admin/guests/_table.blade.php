<div class="overflow-x-auto -mx-6">
    <table class="w-full text-left text-xs text-stone-700">
        <thead class="text-[11px] uppercase tracking-wider text-stone-400 bg-stone-50/70 border-y border-stone-200/50">
            <tr>
                <th scope="col" class="py-3 px-6 font-semibold">Nama Tamu & Alamat</th>
                <th scope="col" class="py-3 px-6 font-semibold">No. WhatsApp</th>
                <th scope="col" class="py-3 px-6 font-semibold">Link Khusus Tamu</th>
                <th scope="col" class="py-3 px-6 font-semibold">Status Kirim WA</th>
                <th scope="col" class="py-3 px-6 font-semibold">Konfirmasi RSVP</th>
                <th scope="col" class="py-3 px-6 font-semibold text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @forelse ($guests as $guest)
                <tr class="hover:bg-amber-50/40 transition-colors duration-150">
                    <!-- Nama Tamu & Alamat -->
                    <td class="py-4 px-6 font-medium text-stone-900 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-2xl bg-[#C25E2E]/10 text-[#C25E2E] flex items-center justify-center font-bold text-xs shrink-0 border border-[#C25E2E]/20">
                                {{ mb_substr($guest->name, 0, 1) }}
                            </div>
                            <div>
                                <span class="font-bold text-stone-900 text-xs block">{{ $guest->name }}</span>
                                <div class="flex items-center gap-2 mt-0.5">
                                    @if ($guest->address)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-medium text-amber-800 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200/50">
                                            <svg class="w-2.5 h-2.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            di {{ $guest->address }}
                                        </span>
                                    @else
                                        <span class="text-[10px] text-stone-400">di Tempat</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- No WhatsApp -->
                    <td class="py-4 px-6 whitespace-nowrap">
                        @if ($guest->phone)
                            <span class="font-mono text-stone-700 text-xs font-semibold">{{ $guest->phone }}</span>
                        @else
                            <span class="text-stone-300 text-xs">-</span>
                        @endif
                    </td>

                    <!-- Link Khusus Tamu (?to=...) -->
                    <td class="py-4 px-6 whitespace-nowrap">
                        <div class="inline-flex items-center gap-1.5">
                            <span class="px-2.5 py-1 rounded-xl bg-stone-100/90 text-stone-700 font-mono text-[11px] max-w-[200px] truncate" title="{{ $guest->invitation_url }}">
                                ?to={{ urlencode($guest->name) }}
                            </span>
                            <button 
                                type="button" 
                                @click="copyLink('{{ $guest->invitation_url }}')"
                                class="p-1 rounded-lg text-stone-400 hover:text-stone-700 hover:bg-stone-100 transition"
                                title="Salin Tautan Lengkap"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </button>
                        </div>
                    </td>

                    <!-- Status Kirim WA -->
                    <td class="py-4 px-6 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            @if ($guest->invitation_sent_at)
                                <form method="POST" action="{{ route('admin.guests.toggle-sent', $guest->id) }}">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200/60 hover:bg-emerald-200/70 transition"
                                        title="Klik untuk menandai belum dikirim"
                                    >
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Terkirim ({{ $guest->invitation_sent_at->format('d/m H:i') }})</span>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.guests.toggle-sent', $guest->id) }}">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-stone-100 text-stone-500 border border-stone-200 hover:bg-amber-100 hover:text-amber-800 transition"
                                        title="Klik untuk menandai sudah dikirim"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full bg-stone-400"></span>
                                        <span>Belum Dikirim</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>

                    <!-- Konfirmasi RSVP -->
                    <td class="py-4 px-6 whitespace-nowrap">
                        @if ($guest->rsvp)
                            @if ($guest->rsvp->attendance_status === 'attending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    Hadir ({{ $guest->rsvp->guest_count }} Pax)
                                </span>
                            @elseif ($guest->rsvp->attendance_status === 'not_attending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                    Tidak Hadir
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                    Ragu-ragu
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-stone-100 text-stone-500">
                                Belum Konfirmasi
                            </span>
                        @endif
                    </td>

                    <!-- Aksi -->
                    <td class="py-4 px-6 text-right whitespace-nowrap">
                        <div class="inline-flex items-center justify-end gap-1.5">
                            <!-- Tombol Kirim WhatsApp -->
                            @php
                                $coupleNames = $selectedInvitation && $selectedInvitation->couple 
                                    ? $selectedInvitation->couple->partner_one_name . ' & ' . $selectedInvitation->couple->partner_two_name 
                                    : 'Mempelai';
                            @endphp
                            <button 
                                type="button" 
                                @click="sendWhatsApp('{{ $guest->phone }}', '{{ addslashes($guest->name) }}', '{{ addslashes($coupleNames) }}', '{{ $guest->invitation_url }}', '{{ addslashes($guest->address ?? '') }}')"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold text-xs transition"
                                title="Kirim Undangan WhatsApp"
                            >
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                                <span>WA</span>
                            </button>

                            <!-- Tombol Edit -->
                            <button 
                                type="button" 
                                @click="openEdit({ id: {{ $guest->id }}, name: '{{ addslashes($guest->name) }}', phone: '{{ $guest->phone }}', address: '{{ addslashes($guest->address ?? '') }}' })"
                                class="p-1.5 rounded-xl text-stone-500 hover:text-stone-800 hover:bg-stone-100 transition"
                                title="Edit Tamu"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            <!-- Tombol Hapus -->
                            <button 
                                type="button" 
                                @click="confirmDelete('{{ addslashes($guest->name) }}', '{{ route('admin.guests.destroy', $guest->id) }}')"
                                class="p-1.5 rounded-xl text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition"
                                title="Hapus Tamu"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-stone-500">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <p class="font-semibold text-stone-700">Belum ada tamu undangan</p>
                            <p class="text-xs text-stone-400 mt-1">Tambahkan nama tamu secara manual atau gunakan tombol import massal.</p>
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
        @if ($guests->total() > 0)
            Menampilkan <span class="font-bold text-stone-800">{{ $guests->firstItem() }}</span> - 
            <span class="font-bold text-stone-800">{{ $guests->lastItem() }}</span> dari 
            <span class="font-bold text-stone-800">{{ $guests->total() }}</span> tamu undangan
        @else
            0 tamu ditemukan
        @endif
    </div>

    <!-- Interactive Pagination Links (AJAX via Alpine) -->
    @if ($guests->hasPages())
        <div class="flex items-center gap-1.5">
            {{-- Previous Page Link --}}
            @if ($guests->onFirstPage())
                <span class="px-3 py-1.5 rounded-xl border border-stone-200 text-stone-300 cursor-not-allowed">
                    &laquo;
                </span>
            @else
                <button 
                    type="button" 
                    @click="fetchPage('{{ $guests->previousPageUrl() }}')"
                    class="px-3 py-1.5 rounded-xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 transition"
                >
                    &laquo;
                </button>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($guests->getUrlRange(max(1, $guests->currentPage() - 2), min($guests->lastPage(), $guests->currentPage() + 2)) as $page => $url)
                @if ($page == $guests->currentPage())
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
            @if ($guests->hasMorePages())
                <button 
                    type="button" 
                    @click="fetchPage('{{ $guests->nextPageUrl() }}')"
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
