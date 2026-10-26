<x-admin.layout :title="'Kelola: ' . ($invitation->couple->partner_one_name ?? $invitation->slug)">
    <div 
        x-data="invitationEdit({
            initialTab: '{{ $errors->has('photos') || $errors->has('photos.*') ? 'gallery' : ($errors->has('events') || $errors->has('events.*') ? 'events' : ($errors->has('slug') || $errors->has('template_id') ? 'publication' : request('tab', 'couple'))) }}',
            initialEvents: {{ Js::from($invitation->events) }},
            maxPhotoSizeMb: 5
        })" 
        class="space-y-6"
    >
        <!-- Top Navigation / Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <!-- Breadcrumbs -->
                <div class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-800 transition">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('admin.invitations.index') }}" class="hover:text-stone-800 transition">Undangan</a>
                    <span>/</span>
                    <span class="text-stone-800 font-medium">Kelola Konten</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight font-serif">
                    {{ $invitation->couple ? $invitation->couple->partner_one_name . ' & ' . $invitation->couple->partner_two_name : 'Undangan: ' . $invitation->slug }}
                </h1>
                <p class="text-xs sm:text-sm text-stone-500 mt-0.5">
                    No. Pesanan: <span class="font-bold text-stone-800">{{ $invitation->order->order_number ?? '-' }}</span> — 
                    Pemesan: <span class="font-medium text-stone-800">{{ $invitation->order->customer_name ?? '-' }}</span>
                </p>
            </div>

            <!-- Top Right Action Controls -->
            <div class="flex items-center gap-2.5">
                <x-admin.badge :status="$invitation->publication_status" />

                <!-- Tombol Kelola Daftar Tamu -->
                <a 
                    href="{{ route('admin.guests.index', ['invitation_id' => $invitation->id]) }}" 
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl bg-white hover:bg-stone-50 text-stone-800 border border-stone-200/80 text-xs font-semibold shadow-xs transition"
                >
                    <svg class="w-3.5 h-3.5 text-[#C25E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Daftar Tamu</span>
                    @if ($invitation->guests->count() > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-amber-100 text-[#C25E2E] font-bold">
                            {{ $invitation->guests->count() }}
                        </span>
                    @endif
                </a>

                <!-- Tombol Lihat Undangan Publik -->
                <a 
                    href="{{ url('/' . $invitation->slug) }}" 
                    target="_blank"
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl bg-white hover:bg-stone-50 text-stone-800 border border-stone-200/80 text-xs font-semibold shadow-xs transition"
                >
                    <span>Lihat Undangan</span>
                    <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Navigation Tabs Bar -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none border-b border-stone-200/60">
            <!-- Tab 1: Profil Mempelai -->
            <button 
                type="button" 
                @click="activeTab = 'couple'"
                :class="activeTab === 'couple' ? 'bg-[#C25E2E] text-white shadow-sm shadow-[#C25E2E]/25' : 'text-stone-600 hover:text-stone-900 hover:bg-white/70'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-semibold whitespace-nowrap transition-all duration-150"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>1. Profil Mempelai</span>
            </button>

            <!-- Tab 2: Jadwal Acara -->
            <button 
                type="button" 
                @click="activeTab = 'events'"
                :class="activeTab === 'events' ? 'bg-[#C25E2E] text-white shadow-sm shadow-[#C25E2E]/25' : 'text-stone-600 hover:text-stone-900 hover:bg-white/70'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-semibold whitespace-nowrap transition-all duration-150"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>2. Rangkaian Acara</span>
                @if ($invitation->events->count() > 0)
                    <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 text-white font-bold">
                        {{ $invitation->events->count() }}
                    </span>
                @endif
            </button>

            <!-- Tab 3: Galeri Prewedding -->
            <button 
                type="button" 
                @click="activeTab = 'gallery'"
                :class="activeTab === 'gallery' ? 'bg-[#C25E2E] text-white shadow-sm shadow-[#C25E2E]/25' : 'text-stone-600 hover:text-stone-900 hover:bg-white/70'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-semibold whitespace-nowrap transition-all duration-150"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>3. Galeri Foto</span>
                @if ($invitation->galleryPhotos->count() > 0)
                    <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 text-white font-bold">
                        {{ $invitation->galleryPhotos->count() }}
                    </span>
                @endif
            </button>

            <!-- Tab 4: Tema & Publikasi -->
            <button 
                type="button" 
                @click="activeTab = 'publication'"
                :class="activeTab === 'publication' ? 'bg-[#C25E2E] text-white shadow-sm shadow-[#C25E2E]/25' : 'text-stone-600 hover:text-stone-900 hover:bg-white/70'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-semibold whitespace-nowrap transition-all duration-150"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>4. URL & Publikasi</span>
            </button>
        </div>

        <!-- ================= TAB 1: PROFIL MEMPELAI ================= -->
        <div x-show="activeTab === 'couple'" x-transition x-cloak>
            <x-admin.card title="Data Profil Kedua Mempelai" subtitle="Lengkapi biodata kedua calon pengantin serta nama kedua orang tua masing-masing.">
                <form @submit="handleSubmit('couple')" method="POST" action="{{ route('admin.invitations.update-couple', $invitation->id) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                        <!-- SISI KIRI: PENGANTIN PRIA -->
                        <div class="p-5 sm:p-6 rounded-3xl bg-stone-50/70 border border-stone-200/60 space-y-4">
                            <div class="flex items-center gap-2 pb-2 border-b border-stone-200/60">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#C25E2E]"></span>
                                <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider">Mempelai Pertama (Pria)</h3>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-stone-700 mb-1.5">Nama Lengkap & Gelar *</label>
                                <input 
                                    type="text" 
                                    name="partner_one_name" 
                                    value="{{ old('partner_one_name', $invitation->couple->partner_one_name ?? '') }}" 
                                    required 
                                    placeholder="Contoh: Andi Pratama, S.Kom"
                                    class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                >
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 mb-1.5">Nama Ayah (Bapak) *</label>
                                    <input 
                                        type="text" 
                                        name="partner_one_father" 
                                        value="{{ old('partner_one_father', $invitation->couple->partner_one_father ?? '') }}" 
                                        required 
                                        placeholder="Nama Bapak"
                                        class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 mb-1.5">Nama Ibu *</label>
                                    <input 
                                        type="text" 
                                        name="partner_one_mother" 
                                        value="{{ old('partner_one_mother', $invitation->couple->partner_one_mother ?? '') }}" 
                                        required 
                                        placeholder="Nama Ibu"
                                        class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                    >
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block text-xs font-semibold text-stone-700">Foto Pengantin Pria (Opsional)</label>
                                    <span class="text-[10px] font-semibold text-amber-700 bg-amber-100/70 px-2 py-0.5 rounded-lg border border-amber-200/60">Maks. 5 MB</span>
                                </div>
                                @if ($invitation->couple && $invitation->couple->partner_one_photo)
                                    <div class="mb-2 flex items-center gap-3">
                                        <img src="{{ asset('storage/' . $invitation->couple->partner_one_photo) }}" alt="Foto Pria" class="w-12 h-12 rounded-2xl object-cover border border-stone-200 shadow-xs">
                                        <span class="text-[11px] text-stone-500">Foto saat ini tersimpan</span>
                                    </div>
                                @endif
                                <input 
                                    type="file" 
                                    name="partner_one_photo" 
                                    accept="image/*"
                                    @change="validateFileSize($event, 5)"
                                    class="w-full text-xs text-stone-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-white file:text-[#C25E2E] file:shadow-xs hover:file:bg-amber-50"
                                >
                                <p class="text-[11px] text-stone-400 mt-1">Format: JPG, JPEG, PNG, WEBP (Maksimal ukuran file: 5 MB).</p>
                            </div>
                        </div>

                        <!-- SISI KANAN: PENGANTIN WANITA -->
                        <div class="p-5 sm:p-6 rounded-3xl bg-stone-50/70 border border-stone-200/60 space-y-4">
                            <div class="flex items-center gap-2 pb-2 border-b border-stone-200/60">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span>
                                <h3 class="text-sm font-bold text-stone-900 uppercase tracking-wider">Mempelai Kedua (Wanita)</h3>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-stone-700 mb-1.5">Nama Lengkap & Gelar *</label>
                                <input 
                                    type="text" 
                                    name="partner_two_name" 
                                    value="{{ old('partner_two_name', $invitation->couple->partner_two_name ?? '') }}" 
                                    required 
                                    placeholder="Contoh: Sinta Lestari, S.E"
                                    class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                >
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 mb-1.5">Nama Ayah (Bapak) *</label>
                                    <input 
                                        type="text" 
                                        name="partner_two_father" 
                                        value="{{ old('partner_two_father', $invitation->couple->partner_two_father ?? '') }}" 
                                        required 
                                        placeholder="Nama Bapak"
                                        class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 mb-1.5">Nama Ibu *</label>
                                    <input 
                                        type="text" 
                                        name="partner_two_mother" 
                                        value="{{ old('partner_two_mother', $invitation->couple->partner_two_mother ?? '') }}" 
                                        required 
                                        placeholder="Nama Ibu"
                                        class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                    >
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block text-xs font-semibold text-stone-700">Foto Pengantin Wanita (Opsional)</label>
                                    <span class="text-[10px] font-semibold text-amber-700 bg-amber-100/70 px-2 py-0.5 rounded-lg border border-amber-200/60">Maks. 5 MB</span>
                                </div>
                                @if ($invitation->couple && $invitation->couple->partner_two_photo)
                                    <div class="mb-2 flex items-center gap-3">
                                        <img src="{{ asset('storage/' . $invitation->couple->partner_two_photo) }}" alt="Foto Wanita" class="w-12 h-12 rounded-2xl object-cover border border-stone-200 shadow-xs">
                                        <span class="text-[11px] text-stone-500">Foto saat ini tersimpan</span>
                                    </div>
                                @endif
                                <input 
                                    type="file" 
                                    name="partner_two_photo" 
                                    accept="image/*"
                                    @change="validateFileSize($event, 5)"
                                    class="w-full text-xs text-stone-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-white file:text-[#C25E2E] file:shadow-xs hover:file:bg-amber-50"
                                >
                                <p class="text-[11px] text-stone-400 mt-1">Format: JPG, JPEG, PNG, WEBP (Maksimal ukuran file: 5 MB).</p>
                            </div>
                        </div>
                    </div>

                    <!-- KISAH CINTA / STORY -->
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1.5">Kisah Cinta / Love Story (Opsional)</label>
                        <textarea 
                            name="story" 
                            rows="4" 
                            placeholder="Ceritakan awal pertemuan atau momen berharga menuju pelaminan..."
                            class="w-full px-4 py-3 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                        >{{ old('story', $invitation->couple->story ?? '') }}</textarea>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <div class="flex justify-end pt-2">
                        <button 
                            type="submit"
                            x-show="!isSubmitting"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl bg-[#C25E2E] hover:bg-[#A84B20] text-white text-xs font-bold shadow-sm shadow-[#C25E2E]/25 transition duration-150"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Profil Mempelai</span>
                        </button>

                        <button 
                            type="button"
                            disabled
                            x-show="isSubmitting"
                            x-cloak
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl bg-stone-400 text-white text-xs font-bold cursor-not-allowed shadow-none"
                        >
                            <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Menyimpan Profil...</span>
                        </button>
                    </div>
                </form>
            </x-admin.card>
        </div>

        <!-- ================= TAB 2: RANGKAIAN ACARA ================= -->
        <div x-show="activeTab === 'events'" x-transition x-cloak>
            <x-admin.card title="Rangkaian Acara Pernikahan" subtitle="Kelola jadwal Akad Nikah, Resepsi, atau acara lainnya secara dinamis.">
                <form @submit="handleSubmit('events')" method="POST" action="{{ route('admin.invitations.update-events', $invitation->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <!-- Dynamic Event Cards -->
                        <template x-for="(event, index) in events" :key="index">
                            <div class="p-5 sm:p-6 rounded-3xl bg-stone-50/80 border border-stone-200/60 relative space-y-4 transition">
                                <!-- Top Bar of Event Card -->
                                <div class="flex items-center justify-between pb-3 border-b border-stone-200/50">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-[#C25E2E] text-white text-xs font-bold flex items-center justify-center" x-text="index + 1"></span>
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-stone-800" x-text="event.event_type || 'Rangkaian Acara'"></h4>
                                    </div>

                                    <!-- Delete Button -->
                                    <button 
                                        type="button" 
                                        @click="removeEvent(index)"
                                        class="inline-flex items-center gap-1 text-xs text-rose-600 hover:text-rose-700 font-medium px-2.5 py-1 rounded-xl hover:bg-rose-50 transition"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Jenis Acara -->
                                    <div>
                                        <label class="block text-xs font-semibold text-stone-700 mb-1">Jenis Acara *</label>
                                        <input 
                                            type="text" 
                                            :name="`events[${index}][event_type]`" 
                                            x-model="event.event_type" 
                                            required 
                                            placeholder="Contoh: Akad Nikah"
                                            class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                        >
                                    </div>

                                    <!-- Waktu Mulai -->
                                    <div>
                                        <label class="block text-xs font-semibold text-stone-700 mb-1">Waktu Mulai *</label>
                                        <input 
                                            type="datetime-local" 
                                            :name="`events[${index}][start_at]`" 
                                            x-model="event.start_at" 
                                            required 
                                            class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                        >
                                    </div>

                                    <!-- Waktu Selesai -->
                                    <div>
                                        <label class="block text-xs font-semibold text-stone-700 mb-1">Waktu Selesai (Opsional)</label>
                                        <input 
                                            type="datetime-local" 
                                            :name="`events[${index}][end_at]`" 
                                            x-model="event.end_at" 
                                            class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                        >
                                    </div>

                                    <!-- Nama Gedung / Lokasi -->
                                    <div>
                                        <label class="block text-xs font-semibold text-stone-700 mb-1">Nama Tempat / Gedung *</label>
                                        <input 
                                            type="text" 
                                            :name="`events[${index}][venue_name]`" 
                                            x-model="event.venue_name" 
                                            required 
                                            placeholder="Contoh: Ballroom Hotel Grand"
                                            class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                        >
                                    </div>

                                    <!-- Alamat Lengkap -->
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-stone-700 mb-1">Alamat Lengkap</label>
                                        <input 
                                            type="text" 
                                            :name="`events[${index}][address]`" 
                                            x-model="event.address" 
                                            placeholder="Contoh: Jl. Sudirman No. 12, Jakarta"
                                            class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                        >
                                    </div>

                                    <!-- Link Google Maps -->
                                    <div class="md:col-span-3">
                                        <label class="block text-xs font-semibold text-stone-700 mb-1">Link Google Maps (URL)</label>
                                        <input 
                                            type="url" 
                                            :name="`events[${index}][maps_url]`" 
                                            x-model="event.maps_url" 
                                            placeholder="Contoh: https://maps.google.com/?q=..."
                                            class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                        >
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Tombol Tambah Acara -->
                        <div class="flex justify-between items-center pt-2">
                            <button 
                                type="button" 
                                @click="addEvent()"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white border border-stone-200 text-stone-800 hover:bg-stone-50 text-xs font-semibold shadow-xs transition"
                            >
                                <svg class="w-4 h-4 text-[#C25E2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>+ Tambah Acara Baru</span>
                            </button>

                            <button 
                                type="submit"
                                x-show="!isSubmitting"
                                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl bg-[#C25E2E] hover:bg-[#A84B20] text-white text-xs font-bold shadow-sm shadow-[#C25E2E]/25 transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Simpan Semua Jadwal Acara</span>
                            </button>

                            <button 
                                type="button"
                                disabled
                                x-show="isSubmitting"
                                x-cloak
                                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl bg-stone-400 text-white text-xs font-bold cursor-not-allowed shadow-none"
                            >
                                <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Menyimpan Jadwal...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </x-admin.card>
        </div>

        <!-- ================= TAB 3: GALERI FOTO PREWEDDING ================= -->
        <div x-show="activeTab === 'gallery'" x-transition x-cloak>
            <x-admin.card title="Galeri Foto Prewedding" subtitle="Unggah foto-foto prewedding yang akan tampil di slide/album undangan digital.">
                <!-- Form Upload Foto Baru -->
                <form @submit="handleSubmit('gallery')" method="POST" action="{{ route('admin.invitations.upload-gallery', $invitation->id) }}" enctype="multipart/form-data" class="p-5 rounded-3xl bg-stone-50/80 border border-stone-200/60 mb-6 space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-semibold text-stone-700">Pilih Foto (Bisa pilih beberapa sekaligus)</label>
                                <span class="text-[11px] font-semibold text-amber-800 bg-amber-100/80 px-2.5 py-0.5 rounded-xl border border-amber-200/70">Batas Maks. 5 MB / foto</span>
                            </div>
                            <input 
                                type="file" 
                                name="photos[]" 
                                multiple 
                                accept="image/*" 
                                required
                                @change="validateFileSize($event, 5)"
                                class="w-full text-xs text-stone-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-white file:text-[#C25E2E] file:shadow-xs hover:file:bg-amber-50"
                            >
                            <p class="text-[11px] text-stone-400 mt-1.5 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Format file: JPG, PNG, WEBP. Ukuran setiap file foto <strong>maksimal 5 MB</strong> (foto lebih dari 5 MB otomatis ditolak).</span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1.5">Keterangan / Caption (Opsional)</label>
                            <input 
                                type="text" 
                                name="caption" 
                                placeholder="Contoh: Prewedding di Pantai"
                                class="w-full px-3.5 py-2 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                            >
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button 
                            type="submit"
                            x-show="!isSubmitting"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-[#C25E2E] hover:bg-[#A84B20] text-white text-xs font-bold shadow-sm shadow-[#C25E2E]/25 transition"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span>Unggah Foto ke Galeri</span>
                        </button>

                        <button 
                            type="button"
                            disabled
                            x-show="isSubmitting"
                            x-cloak
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-stone-400 text-white text-xs font-bold cursor-not-allowed shadow-none"
                        >
                            <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Sedang Mengunggah Foto...</span>
                        </button>
                    </div>
                </form>

                <!-- Grid Foto yang Tersimpan -->
                @if ($invitation->galleryPhotos->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach ($invitation->galleryPhotos as $photo)
                            <div class="group relative rounded-2xl overflow-hidden bg-white border border-stone-200/80 shadow-xs hover:shadow-md transition">
                                <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="{{ $photo->caption ?? 'Galeri' }}" class="w-full h-36 object-cover transition duration-300 group-hover:scale-105">
                                
                                @if ($photo->caption)
                                    <div class="p-2 text-[11px] font-medium text-stone-600 truncate border-t border-stone-100">
                                        {{ $photo->caption }}
                                    </div>
                                @endif

                                <!-- Delete Overlay Button -->
                                <form method="POST" action="{{ route('admin.invitations.delete-gallery', ['invitation' => $invitation->id, 'galleryPhoto' => $photo->id]) }}" class="absolute top-2 right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini?')"
                                        class="w-7 h-7 rounded-full bg-rose-600/90 text-white flex items-center justify-center shadow-md hover:bg-rose-700 transition"
                                        title="Hapus Foto"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-10 text-center text-stone-400">
                        <svg class="w-12 h-12 mx-auto mb-2 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-xs font-medium">Belum ada foto yang diunggah untuk undangan ini.</p>
                    </div>
                @endif
            </x-admin.card>
        </div>

        <!-- ================= TAB 4: TEMA & PUBLIKASI ================= -->
        <div x-show="activeTab === 'publication'" x-transition x-cloak>
            <x-admin.card title="Pengaturan URL & Publikasi Undangan" subtitle="Tentukan slug link unik undangan dan aktifkan status agar dapat diakses oleh tamu.">
                <form @submit="handleSubmit('publication')" method="POST" action="{{ route('admin.invitations.update-publication', $invitation->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Slug URL -->
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1.5">Slug URL Unik Undangan *</label>
                        <div class="flex items-center rounded-2xl border border-stone-200 bg-white overflow-hidden shadow-xs focus-within:ring-2 focus-within:ring-[#C25E2E]/30 focus-within:border-[#C25E2E] transition">
                            <span class="px-4 py-2.5 bg-stone-100 text-stone-500 font-mono text-xs border-r border-stone-200">
                                {{ url('/') }}/
                            </span>
                            <input 
                                type="text" 
                                name="slug" 
                                value="{{ old('slug', $invitation->slug) }}" 
                                required 
                                placeholder="andi-sinta"
                                class="w-full px-4 py-2.5 text-xs text-stone-900 font-bold focus:outline-none"
                            >
                        </div>
                        <p class="text-[11px] text-stone-400 mt-1">Gunakan huruf kecil dan tanda hubung (-), contoh: <code>andi-sinta</code> atau <code>romeo-juliet</code>.</p>
                    </div>

                    <!-- Pilihan Template Desain -->
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-2">Desain Template Katalog *</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach ($templates as $tmpl)
                                <label class="relative flex flex-col p-4 rounded-2xl border cursor-pointer transition {{ $invitation->template_id === $tmpl->id ? 'border-[#C25E2E] bg-amber-50/50 shadow-xs' : 'border-stone-200 bg-white hover:border-stone-300' }}">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-bold text-stone-900">{{ $tmpl->name }}</span>
                                        <input type="radio" name="template_id" value="{{ $tmpl->id }}" {{ $invitation->template_id === $tmpl->id ? 'checked' : '' }} class="text-[#C25E2E] focus:ring-[#C25E2E]">
                                    </div>
                                    <span class="text-[11px] text-stone-500 font-medium">Kategori: {{ $tmpl->category }}</span>
                                    <span class="text-xs font-bold text-[#C25E2E] mt-2">Rp {{ number_format($tmpl->price, 0, ',', '.') }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Status Publikasi -->
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-2">Status Publikasi Undangan *</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <!-- Draft -->
                            <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-stone-200 bg-white hover:bg-stone-50 cursor-pointer transition">
                                <input type="radio" name="publication_status" value="draft" {{ $invitation->publication_status === 'draft' ? 'checked' : '' }} class="text-amber-600 focus:ring-amber-500">
                                <div>
                                    <span class="text-xs font-bold text-stone-800 block">DRAFT</span>
                                    <span class="text-[11px] text-stone-400 block">Masih dalam penyusunan konten</span>
                                </div>
                            </label>

                            <!-- Published -->
                            <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-emerald-300 bg-emerald-50/40 hover:bg-emerald-50 cursor-pointer transition">
                                <input type="radio" name="publication_status" value="published" {{ $invitation->publication_status === 'published' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <span class="text-xs font-bold text-emerald-800 block">PUBLISHED (Aktif)</span>
                                    <span class="text-[11px] text-emerald-600 block">Dapat diakses oleh seluruh tamu</span>
                                </div>
                            </label>

                            <!-- Archived -->
                            <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-stone-200 bg-white hover:bg-stone-50 cursor-pointer transition">
                                <input type="radio" name="publication_status" value="archived" {{ $invitation->publication_status === 'archived' ? 'checked' : '' }} class="text-purple-600 focus:ring-purple-500">
                                <div>
                                    <span class="text-xs font-bold text-stone-800 block">ARCHIVED</span>
                                    <span class="text-[11px] text-stone-400 block">Acara telah selesai</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-2">
                        <button 
                            type="submit"
                            x-show="!isSubmitting"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl bg-[#C25E2E] hover:bg-[#A84B20] text-white text-xs font-bold shadow-sm shadow-[#C25E2E]/25 transition"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Konfigurasi & Perbarui Status</span>
                        </button>

                        <button 
                            type="button"
                            disabled
                            x-show="isSubmitting"
                            x-cloak
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl bg-stone-400 text-white text-xs font-bold cursor-not-allowed shadow-none"
                        >
                            <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Menyimpan Pengaturan...</span>
                        </button>
                    </div>
                </form>
            </x-admin.card>
        </div>
    </div>
</x-admin.layout>
