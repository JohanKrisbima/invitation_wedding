<div class="overflow-x-auto -mx-6">
    <table class="w-full text-left text-xs text-stone-700">
        <thead class="text-[11px] uppercase tracking-wider text-stone-400 bg-stone-50/70 border-y border-stone-200/50">
            <tr>
                <th scope="col" class="py-3 px-6 font-semibold">Tamu Undangan</th>
                <th scope="col" class="py-3 px-6 font-semibold">Status Kehadiran</th>
                <th scope="col" class="py-3 px-6 font-semibold">Jumlah (Pax)</th>
                <th scope="col" class="py-3 px-6 font-semibold">Catatan / Pesan</th>
                <th scope="col" class="py-3 px-6 font-semibold">Waktu RSVP</th>
                <th scope="col" class="py-3 px-6 font-semibold text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @forelse ($rsvps as $rsvp)
                <tr class="hover:bg-amber-50/40 transition-colors duration-150">
                    <!-- Tamu Undangan -->
                    <td class="py-4 px-6 font-medium text-stone-900 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-2xl bg-amber-100/70 text-[#C25E2E] flex items-center justify-center font-bold text-xs shrink-0 border border-amber-200/50">
                                {{ mb_substr($rsvp->guest_name, 0, 1) }}
                            </div>
                            <div>
                                <span class="font-bold text-stone-900 text-xs block">{{ $rsvp->guest_name }}</span>
                                @if ($rsvp->guest)
                                    <div class="flex items-center gap-2 mt-0.5">
                                        @if ($rsvp->guest->address)
                                            <span class="text-[10px] text-stone-500">📍 {{ $rsvp->guest->address }}</span>
                                        @endif
                                        @if ($rsvp->guest->phone)
                                            <span class="text-[10px] font-mono text-stone-400">• {{ $rsvp->guest->phone }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>

                    <!-- Status Kehadiran -->
                    <td class="py-4 px-6 whitespace-nowrap">
                        @if ($rsvp->attendance_status === 'attending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Hadir
                            </span>
                        @elseif ($rsvp->attendance_status === 'not_attending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                Tidak Hadir
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Ragu-ragu
                            </span>
                        @endif
                    </td>

                    <!-- Jumlah Pax -->
                    <td class="py-4 px-6 whitespace-nowrap">
                        @if ($rsvp->attendance_status === 'attending')
                            <div class="inline-flex items-center gap-1.5 font-bold text-stone-900 bg-stone-100 px-2.5 py-1 rounded-xl">
                                <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>{{ $rsvp->guest_count }} orang</span>
                            </div>
                        @else
                            <span class="text-stone-400">-</span>
                        @endif
                    </td>

                    <!-- Catatan / Note -->
                    <td class="py-4 px-6">
                        @if ($rsvp->note)
                            <p class="text-xs text-stone-700 italic max-w-xs break-words line-clamp-2" title="{{ $rsvp->note }}">
                                "{{ $rsvp->note }}"
                            </p>
                        @else
                            <span class="text-stone-300 text-xs">-</span>
                        @endif
                    </td>

                    <!-- Waktu RSVP -->
                    <td class="py-4 px-6 whitespace-nowrap text-stone-500 text-[11px]">
                        <span>{{ $rsvp->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </td>

                    <!-- Aksi -->
                    <td class="py-4 px-6 whitespace-nowrap text-right">
                        <button 
                            type="button" 
                            @click="confirmDeleteRsvp({{ $rsvp->id }}, '{{ addslashes($rsvp->guest_name) }}')"
                            class="p-2 rounded-xl text-stone-400 hover:text-rose-600 hover:bg-rose-50 transition duration-150"
                            title="Hapus RSVP"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-stone-400">
                        <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-stone-300 mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="font-bold text-stone-700 text-sm">Belum Ada Data Konfirmasi Kehadiran</p>
                        <p class="text-xs text-stone-400 mt-1">Konfirmasi RSVP yang diisi oleh tamu melalui website undangan akan muncul di sini.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Footer -->
@if ($rsvps->hasPages())
    <div class="px-6 py-4 border-t border-stone-100 flex items-center justify-between text-xs">
        <span class="text-stone-500">
            Menampilkan {{ $rsvps->firstItem() }} - {{ $rsvps->lastItem() }} dari {{ $rsvps->total() }} RSVP
        </span>
        <div class="flex items-center gap-1">
            @if ($rsvps->onFirstPage())
                <span class="px-3 py-1.5 rounded-xl border border-stone-200 text-stone-300 cursor-not-allowed">Sebelumnya</span>
            @else
                <button type="button" @click="fetchRsvpPage('{{ $rsvps->previousPageUrl() }}')" class="px-3 py-1.5 rounded-xl border border-stone-200 hover:bg-stone-50 text-stone-700 transition">Sebelumnya</button>
            @endif

            @if ($rsvps->hasMorePages())
                <button type="button" @click="fetchRsvpPage('{{ $rsvps->nextPageUrl() }}')" class="px-3 py-1.5 rounded-xl border border-stone-200 hover:bg-stone-50 text-stone-700 transition">Selanjutnya</button>
            @else
                <span class="px-3 py-1.5 rounded-xl border border-stone-200 text-stone-300 cursor-not-allowed">Selanjutnya</span>
            @endif
        </div>
    </div>
@endif
