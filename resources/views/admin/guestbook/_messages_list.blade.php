<div class="space-y-4">
    @if ($messages->isEmpty())
        <div class="py-12 text-center text-stone-400">
            <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-stone-300 mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </div>
            <p class="font-bold text-stone-700 text-sm">Belum Ada Ucapan & Doa Restu</p>
            <p class="text-xs text-stone-400 mt-1">Ucapan dan doa dari para tamu undangan yang mengisi buku tamu akan terkumpul di sini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($messages as $msg)
                <div class="p-5 rounded-3xl bg-white/90 backdrop-blur-xs border border-stone-200/70 shadow-xs flex flex-col justify-between hover:border-amber-200/80 transition duration-150 group">
                    <div>
                        <!-- Header: Guest info & moderation status badge -->
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-amber-100/70 text-[#C25E2E] flex items-center justify-center font-bold text-xs shrink-0 border border-amber-200/50">
                                    {{ mb_substr($msg->guest_name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-stone-900 text-xs">{{ $msg->guest_name }}</h4>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] text-stone-400 font-mono">{{ $msg->created_at->translatedFormat('d M Y, H:i') }}</span>
                                        @if ($msg->guest && $msg->guest->phone)
                                            <span class="text-[10px] text-stone-400 font-mono">• {{ $msg->guest->phone }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Moderation Badge -->
                            @if ($msg->is_approved)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Ditampilkan
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-[10px] font-bold bg-stone-100 text-stone-500 border border-stone-200/80 shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-stone-400"></span>
                                    Disembunyikan
                                </span>
                            @endif
                        </div>

                        <!-- Message Body Quote -->
                        <div class="p-3.5 rounded-2xl bg-stone-50/70 border border-stone-200/50 text-xs text-stone-700 leading-relaxed font-serif italic">
                            "{{ $msg->message }}"
                        </div>
                    </div>

                    <!-- Footer Action Controls -->
                    <div class="mt-4 pt-3 border-t border-stone-100 flex items-center justify-between">
                        <!-- Toggle Button -->
                        <button 
                            type="button" 
                            @click="toggleApproval({{ $msg->id }})"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl transition duration-150 {{ $msg->is_approved ? 'text-amber-800 bg-amber-50 hover:bg-amber-100' : 'text-emerald-800 bg-emerald-50 hover:bg-emerald-100' }}"
                        >
                            @if ($msg->is_approved)
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                                <span>Sembunyikan dari Publik</span>
                            @else
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>Tampilkan ke Publik</span>
                            @endif
                        </button>

                        <!-- Delete Button -->
                        <button 
                            type="button" 
                            @click="confirmDeleteMessage({{ $msg->id }}, '{{ addslashes($msg->guest_name) }}')"
                            class="p-1.5 rounded-xl text-stone-400 hover:text-rose-600 hover:bg-rose-50 transition duration-150"
                            title="Hapus Pesan"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if ($messages->hasPages())
            <div class="px-6 py-4 border-t border-stone-100 flex items-center justify-between text-xs mt-4">
                <span class="text-stone-500">
                    Menampilkan {{ $messages->firstItem() }} - {{ $messages->lastItem() }} dari {{ $messages->total() }} ucapan
                </span>
                <div class="flex items-center gap-1">
                    @if ($messages->onFirstPage())
                        <span class="px-3 py-1.5 rounded-xl border border-stone-200 text-stone-300 cursor-not-allowed">Sebelumnya</span>
                    @else
                        <button type="button" @click="fetchMessagesPage('{{ $messages->previousPageUrl() }}')" class="px-3 py-1.5 rounded-xl border border-stone-200 hover:bg-stone-50 text-stone-700 transition">Sebelumnya</button>
                    @endif

                    @if ($messages->hasMorePages())
                        <button type="button" @click="fetchMessagesPage('{{ $messages->nextPageUrl() }}')" class="px-3 py-1.5 rounded-xl border border-stone-200 hover:bg-stone-50 text-stone-700 transition">Selanjutnya</button>
                    @else
                        <span class="px-3 py-1.5 rounded-xl border border-stone-200 text-stone-300 cursor-not-allowed">Selanjutnya</span>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>
