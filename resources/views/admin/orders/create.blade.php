<x-admin.layout title="Buat Pesanan Baru">
    <div 
        x-data="{
            selectedTemplateId: {{ old('template_id', $templates->first()->id ?? 1) }},
            totalPrice: {{ old('total_price', $templates->first()->price ?? 150000) }},
            paymentStatus: '{{ old('payment_status', 'unpaid') }}',
            orderStatus: '{{ old('order_status', 'pending') }}',
            autoCreate: {{ old('auto_create_invitation') ? 'true' : 'false' }},
            isSubmitting: false,
            
            updatePayment(status) {
                this.paymentStatus = status;
                if (status === 'paid') {
                    this.orderStatus = 'in_progress';
                    this.autoCreate = true;
                } else {
                    this.orderStatus = 'pending';
                    this.autoCreate = false;
                }
            }
        }"
        class="max-w-4xl mx-auto space-y-6"
    >
        <!-- Breadcrumbs & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-stone-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-stone-800 transition">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('admin.orders.index') }}" class="hover:text-stone-800 transition">Pesanan</a>
                    <span>/</span>
                    <span class="text-stone-800 font-medium">Buat Pesanan Baru</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight font-serif">
                    Buat Pesanan Baru
                </h1>
                <p class="text-xs sm:text-sm text-stone-500 mt-0.5">
                    Input pesanan customer yang memesan melalui WhatsApp atau offline secara manual.
                </p>
            </div>

            <!-- Tombol Kembali -->
            <a 
                href="{{ route('admin.orders.index') }}" 
                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white hover:bg-stone-50 text-stone-700 border border-stone-200/80 text-xs font-semibold shadow-xs transition"
            >
                <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Daftar Pesanan</span>
            </a>
        </div>

        <!-- Form Card -->
        <x-admin.card title="Data Pemesanan Undangan Digital" subtitle="Isi lengkap data pemesan dan pilih template desain yang diinginkan customer.">
            <form 
                method="POST" 
                action="{{ route('admin.orders.store') }}" 
                @submit="isSubmitting = true" 
                class="space-y-6"
            >
                @csrf

                <!-- BAGIAN 1: PILIHAN TEMPLATE DESAIN -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-800">
                            1. Pilih Desain Template Katalog *
                        </label>
                        <span class="text-[11px] text-stone-400">Harga otomatis menyesuaikan</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        @foreach ($templates as $tmpl)
                            <div 
                                @click="selectedTemplateId = {{ $tmpl->id }}; totalPrice = {{ $tmpl->price }};"
                                :class="selectedTemplateId === {{ $tmpl->id }} ? 'border-[#C25E2E] bg-amber-50/50 shadow-md ring-1 ring-[#C25E2E]' : 'border-stone-200 bg-white hover:border-stone-300'"
                                class="relative flex flex-col justify-between p-4 rounded-3xl border cursor-pointer transition-all duration-200"
                            >
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div>
                                        <span class="text-xs font-bold text-stone-900 block leading-tight">{{ $tmpl->name }}</span>
                                        <span class="text-[10px] font-medium text-stone-400 block mt-0.5">Kategori: {{ $tmpl->category }}</span>
                                    </div>
                                    <input 
                                        type="radio" 
                                        name="template_id" 
                                        value="{{ $tmpl->id }}" 
                                        :checked="selectedTemplateId === {{ $tmpl->id }}"
                                        class="text-[#C25E2E] focus:ring-[#C25E2E] mt-0.5"
                                        required
                                    >
                                </div>

                                <div class="pt-2 border-t border-stone-100 flex items-center justify-between">
                                    <span class="text-xs font-extrabold text-[#C25E2E]">
                                        Rp {{ number_format($tmpl->price, 0, ',', '.') }}
                                    </span>
                                    <span 
                                        x-show="selectedTemplateId === {{ $tmpl->id }}" 
                                        class="text-[10px] font-bold text-[#C25E2E] flex items-center gap-1"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Dipilih
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('template_id')
                        <p class="text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- BAGIAN 2: DATA PEMESAN / MEMPELAI -->
                <div class="space-y-4 pt-4 border-t border-stone-200/60">
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-800">
                        2. Identitas Pemesan
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Lengkap Pemesan -->
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1.5">Nama Pemesan / Salah Satu Pengantin *</label>
                            <input 
                                type="text" 
                                name="customer_name" 
                                value="{{ old('customer_name') }}" 
                                required 
                                placeholder="Contoh: Dimas Aditya"
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                            >
                            @error('customer_name')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No WhatsApp -->
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1.5">No. WhatsApp Pemesan *</label>
                            <input 
                                type="text" 
                                name="customer_phone" 
                                value="{{ old('customer_phone') }}" 
                                required 
                                placeholder="Contoh: 081234567890"
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                            >
                            @error('customer_phone')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Acara / Pernikahan -->
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1.5">Tanggal Acara / Pernikahan *</label>
                            <input 
                                type="date" 
                                name="wedding_date" 
                                value="{{ old('wedding_date', now()->addMonths(2)->format('Y-m-d')) }}" 
                                required 
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                            >
                            @error('wedding_date')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kota / Alamat Domisili -->
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1.5">Kota / Alamat Domisili (Opsional)</label>
                            <input 
                                type="text" 
                                name="customer_address" 
                                value="{{ old('customer_address') }}" 
                                placeholder="Contoh: Surabaya, Jawa Timur"
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                            >
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 3: PEMBAYARAN & STATUS ORDER -->
                <div class="space-y-4 pt-4 border-t border-stone-200/60">
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-800">
                        3. Status Pembayaran & Order
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Total Harga -->
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1.5">Total Harga (Rp) *</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400 text-xs font-bold">
                                    Rp
                                </span>
                                <input 
                                    type="number" 
                                    name="total_price" 
                                    x-model="totalPrice" 
                                    required 
                                    min="0"
                                    class="w-full pl-10 pr-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs font-bold text-stone-900 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                                >
                            </div>
                            <span class="text-[10px] text-stone-400 mt-1 block">Bisa disesuaikan jika ada diskon.</span>
                        </div>

                        <!-- Status Pembayaran -->
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1.5">Status Pembayaran *</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label 
                                    @click="updatePayment('unpaid')" 
                                    :class="paymentStatus === 'unpaid' ? 'border-amber-400 bg-amber-50/70 text-amber-900' : 'border-stone-200 bg-white text-stone-600'"
                                    class="flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-2xl border cursor-pointer text-xs font-bold transition"
                                >
                                    <input type="radio" name="payment_status" value="unpaid" :checked="paymentStatus === 'unpaid'" class="sr-only">
                                    <span>UNPAID</span>
                                </label>

                                <label 
                                    @click="updatePayment('paid')" 
                                    :class="paymentStatus === 'paid' ? 'border-emerald-400 bg-emerald-50/70 text-emerald-900' : 'border-stone-200 bg-white text-stone-600'"
                                    class="flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-2xl border cursor-pointer text-xs font-bold transition"
                                >
                                    <input type="radio" name="payment_status" value="paid" :checked="paymentStatus === 'paid'" class="sr-only">
                                    <span>PAID (Lunas)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Status Order -->
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1.5">Status Order *</label>
                            <select 
                                name="order_status" 
                                x-model="orderStatus" 
                                required
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs font-semibold text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                            >
                                <option value="pending">PENDING (Menunggu)</option>
                                <option value="in_progress">IN PROGRESS (Dikerjakan)</option>
                                <option value="completed">COMPLETED (Selesai)</option>
                                <option value="cancelled">CANCELLED (Dibatalkan)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pilihan Cepat: Langsung Buatkan Undangan Digital -->
                    <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200/70 flex items-start gap-3">
                        <input 
                            type="checkbox" 
                            name="auto_create_invitation" 
                            id="auto_create_invitation"
                            value="1" 
                            x-model="autoCreate"
                            class="mt-1 w-4 h-4 rounded text-[#C25E2E] focus:ring-[#C25E2E] border-stone-300"
                        >
                        <label for="auto_create_invitation" class="cursor-pointer">
                            <span class="text-xs font-bold text-stone-900 block">Langsung Buatkan Draft Undangan Digital Mempelai</span>
                            <span class="text-[11px] text-stone-500 block leading-relaxed mt-0.5">
                                Jika dicentang, setelah pesanan disimpan sistem akan otomatis membuatkan record undangan dan langsung mengarahkan Anda ke halaman edit untuk mengisi foto & profil mempelai.
                            </span>
                        </label>
                    </div>
                </div>

                <!-- BAGIAN 4: CATATAN TAMBAHAN -->
                <div class="space-y-2 pt-4 border-t border-stone-200/60">
                    <label class="block text-xs font-semibold text-stone-700">Catatan Khusus dari Pemesan (Opsional)</label>
                    <textarea 
                        name="notes" 
                        rows="3" 
                        placeholder="Contoh: Pemesan meminta warna tema nuansa sage green dan lagu latar pop akustik..."
                        class="w-full px-4 py-2.5 rounded-2xl border border-stone-200 bg-white text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C25E2E]/30 focus:border-[#C25E2E] transition"
                    >{{ old('notes') }}</textarea>
                </div>

                <!-- ACTION CONTROLS -->
                <div class="flex items-center justify-between pt-4 border-t border-stone-200/60">
                    <a 
                        href="{{ route('admin.orders.index') }}" 
                        class="px-5 py-2.5 rounded-2xl border border-stone-200 hover:bg-stone-50 text-stone-600 text-xs font-semibold transition"
                    >
                        Batal
                    </a>

                    <div class="flex items-center gap-3">
                        <!-- Submit Normal Button -->
                        <button 
                            type="submit" 
                            x-show="!isSubmitting"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl bg-[#C25E2E] hover:bg-[#A84B20] text-white text-xs font-bold shadow-sm shadow-[#C25E2E]/25 transition duration-150"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Pesanan Baru</span>
                        </button>

                        <!-- Submitting State Spinner Button -->
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
                            <span>Menyimpan Pesanan...</span>
                        </button>
                    </div>
                </div>
            </form>
        </x-admin.card>
    </div>
</x-admin.layout>
