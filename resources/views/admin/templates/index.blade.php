<x-admin.layout title="Katalog Desain Template">
    <div 
        x-data="templatesManager()" 
        class="space-y-6"
    >
        <!-- Top Breadcrumbs & Title Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <!-- Breadcrumbs -->
                <div class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-800 transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-stone-800 font-medium">Katalog Templates</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight font-serif">
                    Katalog Desain Template
                </h1>
                <p class="text-xs sm:text-sm text-stone-500 mt-0.5">
                    Kelola koleksi tema undangan digital, pengaturan harga sewa/beli, kategori gaya, dan ketersediaan publik.
                </p>
            </div>

            <!-- Top Action Button -->
            <div class="flex items-center gap-3">
                <button 
                    type="button" 
                    @click="showCreateModal = true"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#C25E2E] to-[#A84B20] text-white text-xs font-semibold shadow-md shadow-[#C25E2E]/25 hover:shadow-lg hover:shadow-[#C25E2E]/35 hover:-translate-y-0.5 transition-all duration-200"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Template</span>
                </button>
            </div>
        </div>

        <!-- Metric Stat Cards Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            <!-- 1. Total Template -->
            <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-stone-500">Total Template</span>
                    <span class="w-2 h-2 rounded-full bg-stone-400"></span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-stone-900 font-serif">{{ $totalTemplates }}</span>
                    <span class="text-[11px] text-stone-400">desain katalog</span>
                </div>
            </div>

            <!-- 2. Template Aktif -->
            <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-emerald-700">Template Aktif</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-emerald-900 font-serif">{{ $activeTemplates }}</span>
                    <span class="text-[11px] text-emerald-600">siap dipilih user</span>
                </div>
            </div>

            <!-- 3. Kategori Desain -->
            <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-amber-700">Kategori Desain</span>
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-amber-900 font-serif">{{ $totalCategories }}</span>
                    <span class="text-[11px] text-amber-600">ragam tema</span>
                </div>
            </div>

            <!-- 4. Template Terpopuler -->
            <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-[#C25E2E]">Terpopuler</span>
                    <span class="w-2 h-2 rounded-full bg-[#C25E2E]"></span>
                </div>
                <div class="mt-2">
                    <div class="text-sm font-bold text-stone-900 truncate font-serif">
                        {{ $mostPopular->name ?? 'Belum ada data' }}
                    </div>
                    <div class="text-[11px] text-[#C25E2E] mt-0.5 font-medium">
                        {{ $mostPopular->invitations_count ?? 0 }} undangan aktif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter, Pencarian & Toggle View Controls -->
        <div class="p-4 rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Form Filter Server-side -->
            <form method="GET" action="{{ route('admin.templates.index') }}" class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <!-- Search Input -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari nama template, slug, atau kategori..." 
                        class="w-full pl-10 pr-4 py-2 bg-stone-50/80 border border-stone-200/80 rounded-2xl text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/20 focus:border-[#C25E2E] transition"
                    />
                </div>

                <!-- Category Filter -->
                <div class="w-full sm:w-44">
                    <select 
                        name="category" 
                        onchange="this.form.submit()"
                        class="w-full px-3.5 py-2 bg-stone-50/80 border border-stone-200/80 rounded-2xl text-xs text-stone-700 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/20 focus:border-[#C25E2E] transition"
                    >
                        <option value="all">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="w-full sm:w-40">
                    <select 
                        name="status" 
                        onchange="this.form.submit()"
                        class="w-full px-3.5 py-2 bg-stone-50/80 border border-stone-200/80 rounded-2xl text-xs text-stone-700 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/20 focus:border-[#C25E2E] transition"
                    >
                        <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hanya Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Hanya Nonaktif</option>
                    </select>
                </div>

                <!-- Submit / Reset button -->
                <div class="flex items-center gap-2">
                    <button 
                        type="submit" 
                        class="px-4 py-2 rounded-2xl bg-stone-800 text-white text-xs font-semibold hover:bg-stone-900 transition shadow-xs"
                    >
                        Cari
                    </button>
                    @if (request()->hasAny(['search', 'category', 'status']))
                        <a 
                            href="{{ route('admin.templates.index') }}" 
                            class="px-3 py-2 rounded-2xl bg-stone-100 hover:bg-stone-200 text-stone-600 text-xs font-medium transition"
                            title="Reset Filter"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <!-- View Mode Switcher (Grid vs Table) -->
            <div class="inline-flex items-center p-1 bg-stone-100/80 rounded-2xl border border-stone-200/60 shrink-0 self-end md:self-auto">
                <button 
                    type="button" 
                    @click="setViewMode('grid')"
                    :class="viewMode === 'grid' ? 'bg-white text-stone-900 shadow-xs font-bold' : 'text-stone-500 hover:text-stone-800'"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs transition duration-150"
                    title="Tampilan Grid Card"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span>Grid</span>
                </button>
                <button 
                    type="button" 
                    @click="setViewMode('table')"
                    :class="viewMode === 'table' ? 'bg-white text-stone-900 shadow-xs font-bold' : 'text-stone-500 hover:text-stone-800'"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs transition duration-150"
                    title="Tampilan Tabel Detail"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span>Tabel</span>
                </button>
            </div>
        </div>

        <!-- CONTENT AREA: GRID VIEW -->
        <div x-show="viewMode === 'grid'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            @if ($templates->isEmpty())
                <div class="p-12 text-center rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs">
                    <div class="w-16 h-16 mx-auto rounded-3xl bg-stone-100 text-stone-400 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-stone-800 mb-1">Tidak Ada Template Ditemukan</h3>
                    <p class="text-xs text-stone-500 max-w-sm mx-auto mb-5">
                        Tidak ada template yang cocok dengan filter atau kata kunci pencarian Anda. Coba reset filter atau buat template baru.
                    </p>
                    <button 
                        type="button" 
                        @click="showCreateModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-[#C25E2E] text-white text-xs font-semibold shadow-sm hover:bg-[#A84B20] transition"
                    >
                        Tambah Template Baru
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach ($templates as $template)
                        <div class="group relative rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs hover:shadow-xl hover:border-stone-300 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                            <!-- Top Thumbnail / Visual Mockup Header -->
                            <div class="relative w-full aspect-[4/3] bg-gradient-to-br from-stone-100 via-amber-50/40 to-stone-100 overflow-hidden">
                                @if ($template->thumbnail_url)
                                    <img 
                                        src="{{ $template->thumbnail_url }}" 
                                        alt="{{ $template->name }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    />
                                @else
                                    <!-- Elegant Curated Architectural SVG Mockup -->
                                    <div class="w-full h-full p-4 flex flex-col justify-between relative select-none">
                                        <!-- Decorative Background Patterns -->
                                        <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-[#C25E2E]/10 blur-xl pointer-events-none"></div>
                                        <div class="absolute -left-6 -top-6 w-32 h-32 rounded-full bg-amber-400/10 blur-xl pointer-events-none"></div>

                                        <!-- Mockup Top Bar -->
                                        <div class="flex items-center justify-between z-10">
                                            <span class="px-2 py-0.5 rounded-md bg-white/80 backdrop-blur-sm text-[10px] font-mono text-stone-500 border border-stone-200/40">
                                                {{ $template->slug }}
                                            </span>
                                        </div>

                                        <!-- Mockup Center Typography -->
                                        <div class="text-center my-auto z-10 px-3">
                                            <div class="text-[10px] uppercase tracking-widest text-[#C25E2E] font-semibold mb-1">
                                                Wedding Invitation
                                            </div>
                                            <h4 class="text-lg font-extrabold text-stone-800 font-serif leading-tight">
                                                {{ $template->name }}
                                            </h4>
                                            <p class="text-[10px] text-stone-400 italic mt-1 font-serif">
                                                The Wedding of Romeo & Juliet
                                            </p>
                                        </div>

                                        <!-- Mockup Bottom Watermark -->
                                        <div class="flex items-center justify-center gap-1.5 text-[9px] text-stone-400 font-medium z-10">
                                            <span>Digital Template Preview</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Floating Category Pill (Top-Left) -->
                                <div class="absolute top-3 left-3 z-20">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-md text-[10px] font-bold text-stone-700 shadow-xs border border-white/60">
                                        {{ $template->category }}
                                    </span>
                                </div>

                                <!-- Floating Status Pill (Top-Right) -->
                                <div class="absolute top-3 right-3 z-20">
                                    @if ($template->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-500/90 backdrop-blur-md text-white text-[10px] font-bold shadow-xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                            <span>Aktif</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-stone-700/80 backdrop-blur-md text-stone-200 text-[10px] font-medium shadow-xs">
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Body: Details & Statistics -->
                            <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <!-- Title & Price -->
                                    <div class="flex items-start justify-between gap-2 mb-1.5">
                                        <h3 class="text-base font-extrabold text-stone-900 font-serif group-hover:text-[#C25E2E] transition-colors leading-snug">
                                            {{ $template->name }}
                                        </h3>
                                        <div class="text-right shrink-0">
                                            <div class="text-xs font-bold text-[#C25E2E]">
                                                Rp {{ number_format($template->price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <p class="text-xs text-stone-500 line-clamp-2 mb-3">
                                        {{ $template->description ?: 'Desain tema undangan elegan dengan sentuhan tipografi artistik dan animasi interaktif modern.' }}
                                    </p>
                                </div>

                                <!-- Metric Statistics Bar -->
                                <div class="pt-3 border-t border-stone-100 flex items-center justify-between text-[11px] text-stone-500 font-medium">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $template->invitations_count }} Undangan</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <span>{{ $template->orders_count }} Pesanan</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Actions Footer -->
                            <div class="px-4 py-3 bg-stone-50/70 border-t border-stone-100 flex items-center justify-between gap-2">
                                <!-- Status Toggle Switch -->
                                <button 
                                    type="button" 
                                    @click="toggleStatus('{{ route('admin.templates.toggle-status', $template->id) }}', {{ $template->id }})"
                                    class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-stone-600 hover:text-stone-900 transition"
                                    title="Klik untuk ubah status aktif/nonaktif"
                                >
                                    <div class="w-7 h-4 rounded-full p-0.5 transition-colors {{ $template->is_active ? 'bg-emerald-500' : 'bg-stone-300' }}">
                                        <div class="w-3 h-3 rounded-full bg-white transition-transform {{ $template->is_active ? 'translate-x-3' : 'translate-x-0' }}"></div>
                                    </div>
                                    <span>{{ $template->is_active ? 'Aktif' : 'Off' }}</span>
                                </button>

                                <!-- Action Buttons Group -->
                                <div class="flex items-center gap-1.5">
                                    <!-- Preview Button -->
                                    <button 
                                        type="button" 
                                        @click="openPreview({{ json_encode($template) }})"
                                        class="p-2 rounded-xl text-stone-500 hover:text-stone-800 hover:bg-stone-200/60 transition"
                                        title="Lihat Detail & Pratinjau"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    <!-- Edit Button -->
                                    <button 
                                        type="button" 
                                        @click="openEdit({{ json_encode($template) }})"
                                        class="p-2 rounded-xl text-stone-500 hover:text-[#C25E2E] hover:bg-amber-100/60 transition"
                                        title="Edit Template"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    <!-- Delete Button -->
                                    <button 
                                        type="button" 
                                        @click="confirmDelete('{{ $template->name }}', '{{ route('admin.templates.destroy', $template->id) }}', {{ $template->canBeDeleted() ? 'true' : 'false' }})"
                                        class="p-2 rounded-xl text-stone-400 hover:text-rose-600 hover:bg-rose-50 transition"
                                        title="Hapus Template"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- CONTENT AREA: TABLE VIEW -->
        <div x-show="viewMode === 'table'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="rounded-3xl bg-white/80 backdrop-blur-md border border-stone-200/60 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-stone-200/70 bg-stone-50/80 text-stone-500 uppercase tracking-wider font-semibold text-[10px]">
                                <th class="py-3.5 px-4">Template</th>
                                <th class="py-3.5 px-4">Kategori</th>
                                <th class="py-3.5 px-4">Harga Sewa</th>
                                <th class="py-3.5 px-4 text-center">Penggunaan</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 font-medium">
                            @forelse ($templates as $template)
                                <tr class="hover:bg-stone-50/50 transition">
                                    <!-- Template & Thumbnail Info -->
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-2xl bg-stone-100 border border-stone-200/60 overflow-hidden shrink-0 flex items-center justify-center text-stone-400">
                                                @if ($template->thumbnail_url)
                                                    <img src="{{ $template->thumbnail_url }}" alt="{{ $template->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-6 h-6 text-[#C25E2E]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-bold text-stone-900 font-serif text-sm">
                                                    {{ $template->name }}
                                                </div>
                                                <div class="text-[11px] font-mono text-stone-400">
                                                    {{ $template->slug }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Kategori -->
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-stone-100 text-stone-700 text-[11px] font-semibold border border-stone-200/40">
                                            {{ $template->category }}
                                        </span>
                                    </td>

                                    <!-- Harga -->
                                    <td class="py-3.5 px-4">
                                        <span class="font-bold text-stone-800">
                                            Rp {{ number_format($template->price, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <!-- Penggunaan -->
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="inline-flex items-center gap-2 text-stone-600">
                                            <span class="font-semibold text-stone-900">{{ $template->invitations_count }}</span>
                                            <span class="text-stone-400 text-[10px]">Undangan</span>
                                            <span class="text-stone-300">•</span>
                                            <span class="font-semibold text-stone-900">{{ $template->orders_count }}</span>
                                            <span class="text-stone-400 text-[10px]">Order</span>
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="py-3.5 px-4 text-center">
                                        <button 
                                            type="button" 
                                            @click="toggleStatus('{{ route('admin.templates.toggle-status', $template->id) }}', {{ $template->id }})"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $template->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-stone-100 text-stone-500 border border-stone-200' }}"
                                            title="Klik untuk ubah status"
                                        >
                                            <span class="w-1.5 h-1.5 rounded-full {{ $template->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-stone-400' }}"></span>
                                            <span>{{ $template->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                        </button>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="inline-flex items-center gap-1">
                                            <button 
                                                type="button" 
                                                @click="openPreview({{ json_encode($template) }})"
                                                class="p-1.5 rounded-lg text-stone-500 hover:text-stone-900 hover:bg-stone-100 transition"
                                                title="Lihat Pratinjau"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button 
                                                type="button" 
                                                @click="openEdit({{ json_encode($template) }})"
                                                class="p-1.5 rounded-lg text-stone-500 hover:text-[#C25E2E] hover:bg-amber-100/60 transition"
                                                title="Edit"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <button 
                                                type="button" 
                                                @click="confirmDelete('{{ $template->name }}', '{{ route('admin.templates.destroy', $template->id) }}', {{ $template->canBeDeleted() ? 'true' : 'false' }})"
                                                class="p-1.5 rounded-lg text-stone-400 hover:text-rose-600 hover:bg-rose-50 transition"
                                                title="Hapus"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-stone-400">
                                        Tidak ada template yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination Links -->
        <div class="mt-6">
            {{ $templates->links() }}
        </div>

        <!-- ============================================================= -->
        <!-- MODAL 1: TAMBAH TEMPLATE BARU -->
        <!-- ============================================================= -->
        <div 
            x-show="showCreateModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            <!-- Backdrop -->
            <div 
                x-show="showCreateModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="showCreateModal = false"
                class="fixed inset-0 bg-stone-900/50 backdrop-blur-xs transition-opacity"
            ></div>

            <!-- Modal Dialog Content -->
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="showCreateModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-stone-200"
                >
                    <!-- Form Header -->
                    <div class="px-6 py-5 border-b border-stone-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-stone-900 font-serif">
                                Tambah Template Desain Baru
                            </h3>
                            <p class="text-xs text-stone-500 mt-0.5">
                                Daftarkan tema undangan digital baru ke katalog publik.
                            </p>
                        </div>
                        <button 
                            type="button" 
                            @click="showCreateModal = false"
                            class="text-stone-400 hover:text-stone-600 p-1.5 rounded-xl hover:bg-stone-100 transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Form Body -->
                    <form method="POST" action="{{ route('admin.templates.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="p-6 space-y-4 text-xs">
                            <!-- Nama Template & Slug -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold text-stone-700 mb-1.5">
                                        Nama Template <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="name" 
                                        x-model="createForm.name"
                                        @input="onNameInput"
                                        required 
                                        placeholder="Contoh: Royal Botanical" 
                                        class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-2xl text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/20 focus:border-[#C25E2E] transition"
                                    />
                                </div>
                                <div>
                                    <label class="block font-semibold text-stone-700 mb-1.5">
                                        Slug Template <span class="text-stone-400 font-normal">(Otomatis/Unik)</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="slug" 
                                        x-model="createForm.slug"
                                        @input="onSlugManualInput"
                                        placeholder="royal-botanical" 
                                        class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-2xl text-xs font-mono text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/20 focus:border-[#C25E2E] transition"
                                    />
                                </div>
                            </div>

                            <!-- Kategori & Harga Sewa -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold text-stone-700 mb-1.5">
                                        Kategori Desain <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="category" 
                                        x-model="createForm.category"
                                        list="categoryList"
                                        required 
                                        placeholder="Pilih atau ketik kategori..." 
                                        class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-2xl text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/20 focus:border-[#C25E2E] transition"
                                    />
                                    <datalist id="categoryList">
                                        <option value="Rustic">
                                        <option value="Modern">
                                        <option value="Floral">
                                        <option value="Classic">
                                        <option value="Minimalist">
                                        <option value="Traditional">
                                        <option value="Luxury">
                                    </datalist>
                                </div>
                                <div>
                                    <label class="block font-semibold text-stone-700 mb-1.5">
                                        Harga Katalog (IDR) <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-stone-400">Rp</span>
                                        <input 
                                            type="number" 
                                            name="price" 
                                            x-model="createForm.price"
                                            required 
                                            min="0" 
                                            step="1000"
                                            placeholder="150000" 
                                            class="w-full pl-10 pr-4 py-2.5 bg-stone-50 border border-stone-200 rounded-2xl text-xs font-semibold text-stone-900 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/20 focus:border-[#C25E2E] transition"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Thumbnail Upload Preview -->
                            <div>
                                <label class="block font-semibold text-stone-700 mb-1.5">
                                    Thumbnail Preview <span class="text-stone-400 font-normal">(Opsional, Rekomendasi 800x600 px)</span>
                                </label>
                                <div class="flex items-center gap-4">
                                    <div class="w-20 h-20 rounded-2xl bg-stone-100 border border-dashed border-stone-300 flex items-center justify-center overflow-hidden shrink-0">
                                        <template x-if="createThumbnailPreview">
                                            <img :src="createThumbnailPreview" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!createThumbnailPreview">
                                            <svg class="w-6 h-6 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </template>
                                    </div>
                                    <div class="flex-1">
                                        <input 
                                            type="file" 
                                            name="thumbnail" 
                                            accept="image/*"
                                            @change="handleCreateThumbnail"
                                            class="block w-full text-xs text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#C25E2E]/10 file:text-[#C25E2E] hover:file:bg-[#C25E2E]/20 transition"
                                        />
                                        <p class="text-[10px] text-stone-400 mt-1">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label class="block font-semibold text-stone-700 mb-1.5">
                                    Deskripsi Singkat / Fitur Desain
                                </label>
                                <textarea 
                                    name="description" 
                                    x-model="createForm.description"
                                    rows="3" 
                                    placeholder="Jelaskan karakteristik visual, kombinasi warna, atau keunikan layout template ini..."
                                    class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-2xl text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/20 focus:border-[#C25E2E] transition"
                                ></textarea>
                            </div>

                            <!-- Status Aktif Switch -->
                            <div class="pt-2 flex items-center gap-3">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="is_active" 
                                        value="1" 
                                        x-model="createForm.is_active"
                                        class="sr-only peer"
                                    >
                                    <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                </label>
                                <span class="font-semibold text-stone-800">
                                    Aktifkan template langsung di katalog pemesanan
                                </span>
                            </div>
                        </div>

                        <!-- Form Footer -->
                        <div class="px-6 py-4 bg-stone-50 border-t border-stone-100 flex items-center justify-end gap-3">
                            <button 
                                type="button" 
                                @click="showCreateModal = false"
                                class="px-4 py-2.5 rounded-2xl border border-stone-200 text-stone-600 hover:bg-stone-100 font-semibold transition"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                class="px-5 py-2.5 rounded-2xl bg-[#C25E2E] text-white font-semibold hover:bg-[#A84B20] transition shadow-sm"
                            >
                                Simpan Template
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- MODAL 2: EDIT TEMPLATE -->
        <!-- ============================================================= -->
        <div 
            x-show="showEditModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            <div 
                x-show="showEditModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="showEditModal = false"
                class="fixed inset-0 bg-stone-900/50 backdrop-blur-xs transition-opacity"
            ></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="showEditModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-stone-200"
                >
                    <div class="px-6 py-5 border-b border-stone-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-stone-900 font-serif">
                                Edit Template Desain
                            </h3>
                            <p class="text-xs text-stone-500 mt-0.5">
                                Perbarui informasi nama, kategori, harga, atau gambar pratinjau.
                            </p>
                        </div>
                        <button 
                            type="button" 
                            @click="showEditModal = false"
                            class="text-stone-400 hover:text-stone-600 p-1.5 rounded-xl hover:bg-stone-100 transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form method="POST" :action="editForm.action_url" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="p-6 space-y-4 text-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold text-stone-700 mb-1.5">
                                        Nama Template <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="name" 
                                        x-model="editForm.name"
                                        required 
                                        class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-2xl text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/20 focus:border-[#C25E2E] transition"
                                    />
                                </div>
                                <div>
                                    <label class="block font-semibold text-stone-700 mb-1.5">
                                        Slug Template <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="slug" 
                                        x-model="editForm.slug"
                                        required 
                                        class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-2xl text-xs font-mono text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/20 focus:border-[#C25E2E] transition"
                                    />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold text-stone-700 mb-1.5">
                                        Kategori Desain <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="category" 
                                        x-model="editForm.category"
                                        list="editCategoryList"
                                        required 
                                        class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-2xl text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/20 focus:border-[#C25E2E] transition"
                                    />
                                    <datalist id="editCategoryList">
                                        <option value="Rustic">
                                        <option value="Modern">
                                        <option value="Floral">
                                        <option value="Classic">
                                        <option value="Minimalist">
                                        <option value="Traditional">
                                        <option value="Luxury">
                                    </datalist>
                                </div>
                                <div>
                                    <label class="block font-semibold text-stone-700 mb-1.5">
                                        Harga Katalog (IDR) <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-stone-400">Rp</span>
                                        <input 
                                            type="number" 
                                            name="price" 
                                            x-model="editForm.price"
                                            required 
                                            min="0" 
                                            step="1000"
                                            class="w-full pl-10 pr-4 py-2.5 bg-stone-50 border border-stone-200 rounded-2xl text-xs font-semibold text-stone-900 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/20 focus:border-[#C25E2E] transition"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-stone-700 mb-1.5">
                                    Thumbnail Preview
                                </label>
                                <div class="flex items-center gap-4">
                                    <div class="w-20 h-20 rounded-2xl bg-stone-100 border border-dashed border-stone-300 flex items-center justify-center overflow-hidden shrink-0">
                                        <template x-if="editThumbnailPreview">
                                            <img :src="editThumbnailPreview" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!editThumbnailPreview && editForm.thumbnail_url">
                                            <img :src="editForm.thumbnail_url" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!editThumbnailPreview && !editForm.thumbnail_url">
                                            <svg class="w-6 h-6 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </template>
                                    </div>
                                    <div class="flex-1">
                                        <input 
                                            type="file" 
                                            name="thumbnail" 
                                            accept="image/*"
                                            @change="handleEditThumbnail"
                                            class="block w-full text-xs text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#C25E2E]/10 file:text-[#C25E2E] hover:file:bg-[#C25E2E]/20 transition"
                                        />
                                        <p class="text-[10px] text-stone-400 mt-1">Kosongkan jika tidak ingin mengganti gambar thumbnail.</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-stone-700 mb-1.5">
                                    Deskripsi Singkat / Fitur Desain
                                </label>
                                <textarea 
                                    name="description" 
                                    x-model="editForm.description"
                                    rows="3" 
                                    class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-2xl text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/20 focus:border-[#C25E2E] transition"
                                ></textarea>
                            </div>

                            <div class="pt-2 flex items-center gap-3">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="is_active" 
                                        value="1" 
                                        x-model="editForm.is_active"
                                        class="sr-only peer"
                                    >
                                    <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                </label>
                                <span class="font-semibold text-stone-800">
                                    Status Template Aktif
                                </span>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-stone-50 border-t border-stone-100 flex items-center justify-end gap-3">
                            <button 
                                type="button" 
                                @click="showEditModal = false"
                                class="px-4 py-2.5 rounded-2xl border border-stone-200 text-stone-600 hover:bg-stone-100 font-semibold transition"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                class="px-5 py-2.5 rounded-2xl bg-[#C25E2E] text-white font-semibold hover:bg-[#A84B20] transition shadow-sm"
                            >
                                Perbarui Template
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- MODAL 3: QUICK PREVIEW TEMPLATE -->
        <!-- ============================================================= -->
        <div 
            x-show="showPreviewModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            <div 
                x-show="showPreviewModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="showPreviewModal = false"
                class="fixed inset-0 bg-stone-900/50 backdrop-blur-xs transition-opacity"
            ></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    x-show="showPreviewModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-stone-200"
                >
                    <!-- Header -->
                    <div class="px-6 py-5 border-b border-stone-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-full bg-[#C25E2E]/10 text-[#C25E2E] text-xs font-bold" x-text="previewData.category"></span>
                            <span class="text-xs font-mono text-stone-400" x-text="previewData.slug"></span>
                        </div>
                        <button 
                            type="button" 
                            @click="showPreviewModal = false"
                            class="text-stone-400 hover:text-stone-600 p-1.5 rounded-xl hover:bg-stone-100 transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 space-y-5">
                        <!-- Big Visual Thumbnail -->
                        <div class="w-full aspect-[16/9] rounded-2xl bg-gradient-to-br from-stone-100 to-amber-50/50 border border-stone-200/60 overflow-hidden flex items-center justify-center relative">
                            <template x-if="previewData.thumbnail_url">
                                <img :src="previewData.thumbnail_url" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!previewData.thumbnail_url">
                                <div class="text-center p-6 select-none">
                                    <div class="text-xs uppercase tracking-widest text-[#C25E2E] font-bold mb-2">Live Theme Mockup</div>
                                    <h2 class="text-3xl font-extrabold text-stone-800 font-serif" x-text="previewData.name"></h2>
                                    <p class="text-xs text-stone-500 italic font-serif mt-1">Boutique Aesthetic Wedding Design</p>
                                </div>
                            </template>
                        </div>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                            <div class="p-3 rounded-2xl bg-stone-50 border border-stone-100">
                                <div class="text-[10px] text-stone-400 uppercase font-semibold">Harga Katalog</div>
                                <div class="text-sm font-bold text-[#C25E2E] mt-0.5" x-text="previewData.formatted_price"></div>
                            </div>
                            <div class="p-3 rounded-2xl bg-stone-50 border border-stone-100">
                                <div class="text-[10px] text-stone-400 uppercase font-semibold">Status</div>
                                <div class="text-xs font-bold mt-1" :class="previewData.is_active ? 'text-emerald-700' : 'text-stone-500'" x-text="previewData.is_active ? 'Aktif' : 'Nonaktif'"></div>
                            </div>
                            <div class="p-3 rounded-2xl bg-stone-50 border border-stone-100">
                                <div class="text-[10px] text-stone-400 uppercase font-semibold">Total Undangan</div>
                                <div class="text-sm font-bold text-stone-900 mt-0.5" x-text="previewData.invitations_count"></div>
                            </div>
                            <div class="p-3 rounded-2xl bg-stone-50 border border-stone-100">
                                <div class="text-[10px] text-stone-400 uppercase font-semibold">Total Pesanan</div>
                                <div class="text-sm font-bold text-stone-900 mt-0.5" x-text="previewData.orders_count"></div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <h4 class="text-xs font-bold text-stone-800 uppercase tracking-wider mb-1.5">Deskripsi Tema</h4>
                            <p class="text-xs text-stone-600 leading-relaxed bg-stone-50/70 p-4 rounded-2xl border border-stone-100" x-text="previewData.description"></p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-stone-50 border-t border-stone-100 flex items-center justify-end gap-3">
                        <button 
                            type="button" 
                            @click="showPreviewModal = false"
                            class="px-5 py-2.5 rounded-2xl bg-stone-800 text-white text-xs font-semibold hover:bg-stone-900 transition"
                        >
                            Tutup Pratinjau
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
