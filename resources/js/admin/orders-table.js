/**
 * Alpine.js component for server-side orders table (debounced search, filter, pagination)
 */
export default function ordersTable() {
    return {
        search: "",
        status: "all",
        payment: "all",
        isLoading: false,

        /**
         * Fetch a specific page or filter result from the server via AJAX
         */
        async fetchPage(url) {
            // 1. PENGAMAN: Jika URL kosong (misal tombol 'Previous' di halaman 1), batalkan fungsi.
            if (!url) return;

            // 2. NYALAKAN LOADING: Spinner dan efek blur kaca otomatis muncul di tabel.
            this.isLoading = true;

            try {
                // 3. MINTA DATA KE SERVER LARAVEL (Fetch API):
                const response = await fetch(url, {
                    headers: {
                        // Header ini memberi tanda ke Laravel: "Saya minta lewat AJAX ya!"
                        "X-Alpine-Request": "true",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });

                // 4. CEK KONEKSI: Jika server error (misal 500 atau 404), lempar ke blok 'catch'.
                if (!response.ok) {
                    throw new Error("Gagal mengambil data dari server");
                }

                // 5. TAMPILKAN KE LAYAR: Ambil HTML baru (termasuk baris tabel dan pagination) dan masukkan ke div container.
                const html = await response.text();

                // 6. TUKAR ISI TABEL LAMA DENGAN TABEL BARU:
                const container = document.getElementById(
                    "orders-table-container",
                );
                if (container) {
                    container.innerHTML = html;
                }
            } catch (err) {
                console.error("Error saat memperbarui tabel:", err);
                if (window.showToast) {
                    window.showToast("Gagal memuat data pesanan", "error");
                }
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Triggered on search input or dropdown filter change
         */
        filterOrders() {
            const params = new URLSearchParams();
            if (this.search) params.append("search", this.search);
            if (this.status && this.status !== "all")
                params.append("status", this.status);
            if (this.payment && this.payment !== "all")
                params.append("payment", this.payment);
            params.append("ajax", "1");

            const targetUrl = `${window.location.pathname}?${params.toString()}`;
            this.fetchPage(targetUrl);
        },

        /**
         * Reset all filters
         */
        resetFilters() {
            this.search = "";
            this.status = "all";
            this.payment = "all";
            this.filterOrders();
        },

        /**
         * Confirm payment validation & create invitation for an order
         */
        async confirmCreateInvitation(url, customerName, orderNumber) {
            if (!window.showConfirmDialog) {
                if (confirm(`Validasi pembayaran dan buat undangan untuk ${customerName} (${orderNumber})?`)) {
                    this.submitForm(url);
                }
                return;
            }

            const result = await window.showConfirmDialog({
                title: 'Validasi & Buat Undangan?',
                text: `Pesanan ${orderNumber} atas nama "${customerName}" akan divalidasi LUNAS (PAID) dan statusnya berubah menjadi IN PROGRESS. Undangan digital baru akan langsung dibuatkan.`,
                confirmButtonText: 'Ya, Validasi & Buat Undangan',
                cancelButtonText: 'Batal',
            });

            if (result.isConfirmed) {
                this.submitForm(url);
            }
        },

        /**
         * Submit a POST request safely with CSRF token
         */
        submitForm(url) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (csrfToken) {
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken;
                form.appendChild(tokenInput);
            }

            document.body.appendChild(form);
            form.submit();
        },
    };
}
