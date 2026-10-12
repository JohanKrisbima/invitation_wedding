# Wedding Invitation Project - Agent Guidelines & Coding Standards

Dokumen ini adalah aturan tetap (*Project Guidelines & Rules*) yang wajib ditaati oleh AI Agent dalam mengembangkan proyek Web Undangan Pernikahan (*Wedding Invitation*).

---

## 1. Arsitektur Asset & Clean Code (No Inline CSS & Script)
- **DILARANG menggunakan inline `<style>` dan inline `<script>` di dalam file Blade.**
- Seluruh kode CSS dan JavaScript harus berada di file terpisah (`resources/css/` dan `resources/js/`).
- **Pola Per-Template Undangan:**
  - Setiap template undangan memiliki pasangan file Blade, CSS, dan JS tersendiri:
    - View: `resources/views/templates/{template_name}/index.blade.php`
    - CSS: `resources/css/templates/{template_name}.css`
    - JS: `resources/js/templates/{template_name}.js`
  - Daftarkan entry point file tersebut ke dalam `vite.config.js` dan panggil via `@vite(['resources/css/...', 'resources/js/...'])`.
- Manfaatkan **Reusable Blade Components** (`resources/views/components/`) untuk elemen berulang (card, modal, button, badge, table, form input).

---

## 2. Standar UI/UX: Glassmorphism & Anti-AI Slop
- **Minimalisir "AI Slop"**: Hindari desain generik yang malas (seperti gradient ungu-biru murahan, card monoton yang kaku, atau komponen default yang tidak berjiwa).
- **Aestetika Premium & Glassmorphism**:
  - Gunakan efek kaca (*frosted glassmorphism*) yang halus dan berkelas:
    - Background semi-transparan yang seimbang: `backdrop-blur-md bg-white/60` atau `bg-stone-900/40`
    - Border kaca yang lembut: `border border-white/20` atau `border-white/10`
    - Bayangan halus dan natural: `shadow-sm` hingga `shadow-xl` dengan nuansa hangat.
  - Palet warna konsisten: *Warm earth-tone*, *cream/terracotta*, *champagne gold*, *sage green*, atau *soft beige* sesuai tema undangan dan dasbor admin.
  - Tipografi elegan: Padukan font Serif untuk judul (estetika pernikahan klasik/modern) dan Sans-Serif bersih untuk isi data/bacaan.

---

## 3. Keamanan & Ketergantungan Lokal (Local Only, Strict NO CDN)
- **DILARANG memuat aset dari CDN eksternal** (seperti unpkg, cdnjs, cdn.jsdelivr.net, font eksternal non-lokal).
- Semua pustaka/library (JavaScript maupun PHP/CSS) **wajib diinstal secara lokal**:
  - Pustaka JS/CSS dipasang via **npm** (`npm install package_name`) dan dibundel melalui Vite.
  - Pustaka PHP dipasang via **Composer** (`composer require ...`).
  - Font dan Icon disimpan dan dibundel secara lokal (melalui asset lokal Vite atau plugin font lokal).
- Tujuannya agar aplikasi mandiri (*self-hosted*), aman, tidak rentan serangan *supply-chain*, dan tetap bisa berjalan cepat tanpa ketergantungan server luar.

---

## 4. Struktur Kode Bersih & Mudah Dimaintain (Maintainability)
- **Model & Relasi (Eloquent)**: Definisikan relasi secara eksplisit (`belongsTo`, `hasMany`, `hasOne`) dengan typehint yang jelas.
- **Controller Terstruktur**: Pisahkan Controller Admin (`app/Http/Controllers/Admin/`) dan Controller Publik (`app/Http/Controllers/Public/`).
- **Form Request & Validasi**: Gunakan Form Request khusus untuk validasi data pemesanan, data mempelai, dan import Excel.
- **Service / Helper**: Logika kompleks seperti generator pesan WhatsApp, kalkulator status, atau kompresi foto dipisahkan ke dalam Service/Action class khusus.

---

## 5. Sistem Multi-Template Undangan
- Template undangan bersifat modular:
  - Tabel basis data `templates` mencatat daftar katalog (nama, slug, thumbnail, kategori, harga, status aktif).
  - Controller Publik merender view dinamis berdasarkan slug template:
    `return view("templates.{$invitation->template->slug}.index", compact(...));`
  - Struktur data yang dikirim ke view harus konsisten di setiap template:
    - `$invitation`: Data dasar & konfigurasi (musik, cover, quote).
    - `$couple`: Profil pengantin pria & wanita beserta cerita.
    - `$events`: Jadwal akad, resepsi, peta lokasi.
    - `$galleryPhotos`: Galeri foto prewedding.
    - `$guestName`: Nama tamu yang diambil dari parameter URL `?to=...`.
- Setiap template bebas memiliki animasi interaktif (buka cover, background music, countdown, buku tamu) di dalam file JS per-template masing-masing.
