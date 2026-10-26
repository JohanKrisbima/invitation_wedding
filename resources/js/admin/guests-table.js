import * as XLSX from 'xlsx';

/**
 * Alpine.js component for server-side guests management (debounced live search, filter, modals, chunked Excel import, WhatsApp share)
 */
export default function guestsTable(config = {}) {
    return {
        search: "",
        rsvp: "all",
        sent: "all",
        invitationId: config.invitationId || null,
        isLoading: false,

        // Modal States
        showAddModal: false,
        showImportModal: false,
        showEditModal: false,
        importTab: 'excel', // default to 'excel', or 'paste'

        // Excel Chunked Import States (Anti RTO)
        excelFileName: '',
        excelRows: [],
        isParsingExcel: false,
        isUploadingChunks: false,
        uploadProgress: 0,
        progressStatus: '',
        chunkSize: 50, // 50 baris per batch agar sangat cepat & aman dari timeout
        totalImportedCount: 0,

        // Export State
        isExporting: false,

        // Edit Guest Data
        editGuest: {
            id: null,
            name: '',
            phone: '',
            address: '',
            actionUrl: ''
        },

        /**
         * Fetch page or filtered results from server via AJAX
         */
        async fetchPage(url) {
            if (!url) return;

            this.isLoading = true;

            try {
                const response = await fetch(url, {
                    headers: {
                        "X-Alpine-Request": "true",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });

                if (!response.ok) {
                    throw new Error("Gagal mengambil data dari server");
                }

                const html = await response.text();

                const container = document.getElementById("guests-table-container");
                if (container) {
                    container.innerHTML = html;
                }
            } catch (err) {
                console.error("Error saat memperbarui tabel tamu:", err);
                if (window.showToast) {
                    window.showToast("Gagal memuat data tamu", "error");
                }
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Triggered on search input or dropdown filter change
         */
        filterGuests() {
            const params = new URLSearchParams();
            if (this.invitationId) params.append("invitation_id", this.invitationId);
            if (this.search) params.append("search", this.search);
            if (this.rsvp && this.rsvp !== "all") params.append("rsvp", this.rsvp);
            if (this.sent && this.sent !== "all") params.append("sent", this.sent);
            params.append("ajax", "1");

            const targetUrl = `${window.location.pathname}?${params.toString()}`;
            this.fetchPage(targetUrl);
        },

        /**
         * Switch invitation via dropdown
         */
        switchInvitation(newId) {
            if (!newId) return;
            window.location.href = `${window.location.pathname}?invitation_id=${newId}`;
        },

        /**
         * Handle Excel file selection (.xlsx, .xls) and parse rows
         */
        handleExcelFile(event) {
            const file = event.target.files[0];
            if (!file) return;

            const validExtensions = ['.xlsx', '.xls'];
            const fileExt = '.' + file.name.split('.').pop().toLowerCase();
            if (!validExtensions.includes(fileExt)) {
                if (window.showToast) {
                    window.showToast("Format file tidak didukung. Harap pilih file .xlsx atau .xls", "error");
                }
                event.target.value = '';
                return;
            }

            this.excelFileName = file.name;
            this.isParsingExcel = true;
            this.excelRows = [];
            this.uploadProgress = 0;
            this.progressStatus = 'Membaca dan memvalidasi file Excel...';

            const reader = new FileReader();
            reader.onload = (e) => {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });

                    if (!workbook.SheetNames.length) {
                        throw new Error("File Excel tidak memiliki lembar kerja (sheet).");
                    }

                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    const rawRows = XLSX.utils.sheet_to_json(worksheet, { header: 1, defval: '' });

                    if (!rawRows || rawRows.length === 0) {
                        throw new Error("File Excel kosong.");
                    }

                    // Identifikasi baris header (Nama, WA/Phone, Alamat)
                    let headerIndex = -1;
                    let nameCol = 0;
                    let phoneCol = 1;
                    let addressCol = 2;

                    for (let r = 0; r < Math.min(rawRows.length, 5); r++) {
                        const row = rawRows[r];
                        if (!Array.isArray(row)) continue;

                        const nameIdx = row.findIndex(c => /nama|name/i.test(String(c)));
                        if (nameIdx !== -1) {
                            headerIndex = r;
                            nameCol = nameIdx;
                            const phoneIdx = row.findIndex(c => /wa|whatsapp|telepon|telp|hp|phone|kontak/i.test(String(c)));
                            if (phoneIdx !== -1) phoneCol = phoneIdx;
                            const addrIdx = row.findIndex(c => /alamat|kota|address|city|domisili/i.test(String(c)));
                            if (addrIdx !== -1) addressCol = addrIdx;
                            break;
                        }
                    }

                    const parsed = [];
                    const startRow = headerIndex !== -1 ? headerIndex + 1 : 0;

                    for (let r = startRow; r < rawRows.length; r++) {
                        const row = rawRows[r];
                        if (!Array.isArray(row)) continue;

                        const name = String(row[nameCol] || '').trim();
                        if (!name) continue;

                        let phone = String(row[phoneCol] || '').trim();
                        // Bersihkan spasi atau tanda baca berlebih di nomor telepon
                        if (phone) {
                            phone = phone.replace(/[^0-9+]/g, '');
                        }

                        const address = String(row[addressCol] || '').trim();

                        parsed.push({
                            name: name,
                            phone: phone || null,
                            address: address || null
                        });
                    }

                    this.excelRows = parsed;

                    if (this.excelRows.length === 0) {
                        if (window.showToast) {
                            window.showToast("Tidak ada data nama tamu yang valid ditemukan.", "warning");
                        }
                    } else {
                        if (window.showToast) {
                            window.showToast(`Berhasil membaca ${this.excelRows.length} data tamu dari Excel.`, "success");
                        }
                    }
                } catch (err) {
                    console.error("Gagal membaca file Excel:", err);
                    if (window.showToast) {
                        window.showToast(err.message || "Gagal memproses file Excel.", "error");
                    }
                    this.resetExcelImport();
                } finally {
                    this.isParsingExcel = false;
                }
            };

            reader.onerror = () => {
                this.isParsingExcel = false;
                if (window.showToast) {
                    window.showToast("Gagal membaca file dari komputer.", "error");
                }
            };

            reader.readAsArrayBuffer(file);
        },

        /**
         * Reset file Excel terpilih
         */
        resetExcelImport() {
            this.excelFileName = '';
            this.excelRows = [];
            this.uploadProgress = 0;
            this.progressStatus = '';
            this.isParsingExcel = false;
            this.isUploadingChunks = false;
            const fileInput = document.getElementById('excel_guest_file');
            if (fileInput) fileInput.value = '';
        },

        /**
         * Download Excel template (.xlsx) secara instan tanpa perlu request server
         */
        downloadExcelTemplate() {
            try {
                const templateData = [
                    ['Nama Tamu *', 'Nomor WhatsApp (Opsional)', 'Alamat / Kota (Opsional)'],
                    ['Bpk. Joko Widodo & Ibu Iriana', '081234567890', 'Solo, Jawa Tengah'],
                    ['Sdr. Dimas Pratama', '087811228899', 'Surabaya'],
                    ['Ibu Siti Rahmawati & Rekan', '081398765432', 'Jakarta Selatan'],
                    ['Keluarga Besar H. Ridwan', '', 'Yogyakarta']
                ];

                const ws = XLSX.utils.aoa_to_sheet(templateData);

                // Lebar kolom rapi
                ws['!cols'] = [
                    { wch: 35 },
                    { wch: 25 },
                    { wch: 30 }
                ];

                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Daftar Tamu');
                XLSX.writeFile(wb, 'format-import-tamu.xlsx');

                if (window.showToast) {
                    window.showToast("Format Excel (.xlsx) berhasil diunduh!", "success");
                }
            } catch (err) {
                console.error("Error generating Excel template:", err);
                // Fallback to server route if any
                window.location.href = '/admin/guests/template-download';
            }
        },

        /**
         * Export all guests and their personalized invitation links as Excel (.xlsx)
         * Memudahkan admin membagikan daftar link tamu ke pengantin/user.
         */
        async exportGuestsExcel() {
            if (!this.invitationId) {
                if (window.showToast) {
                    window.showToast("Silakan pilih undangan terlebih dahulu.", "warning");
                }
                return;
            }

            this.isExporting = true;

            try {
                const response = await fetch(`/admin/guests/export-data?invitation_id=${this.invitationId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error("Gagal mengambil data tamu dari server");
                }

                const resData = await response.json();
                if (!resData.success || !resData.guests) {
                    throw new Error(resData.message || "Data tamu tidak ditemukan");
                }

                if (resData.guests.length === 0) {
                    if (window.showToast) {
                        window.showToast("Belum ada data tamu pada undangan ini untuk diunduh.", "warning");
                    }
                    return;
                }

                // Susun data tabel Excel
                const headers = [
                    'No',
                    'Nama Tamu',
                    'Kota / Alamat',
                    'Nomor WhatsApp',
                    'Link Khusus Undangan',
                    'Format Pesan WhatsApp Siap Kirim',
                    'Status Kirim WA',
                    'Konfirmasi RSVP',
                    'Jumlah Hadir (Pax)'
                ];

                const excelData = [headers];

                resData.guests.forEach((g) => {
                    excelData.push([
                        g.no,
                        g.name,
                        g.address,
                        g.phone,
                        g.invitation_url,
                        g.wa_message,
                        g.sent_status,
                        g.rsvp_status,
                        g.guest_count
                    ]);
                });

                const ws = XLSX.utils.aoa_to_sheet(excelData);

                // Atur lebar kolom yang nyaman dan rapi dibaca pengantin
                ws['!cols'] = [
                    { wch: 6 },  // No
                    { wch: 32 }, // Nama Tamu
                    { wch: 24 }, // Alamat / Kota
                    { wch: 18 }, // No WhatsApp
                    { wch: 45 }, // Link Undangan
                    { wch: 65 }, // Pesan WA
                    { wch: 16 }, // Status Kirim
                    { wch: 18 }, // Konfirmasi RSVP
                    { wch: 16 }, // Pax
                ];

                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Daftar Tamu & Link');

                const fileName = `Daftar_Tamu_Undangan_${resData.invitation_slug || 'wedding'}.xlsx`;
                XLSX.writeFile(wb, fileName);

                if (window.showToast) {
                    window.showToast(`Berhasil mengunduh ${resData.guests.length} data tamu beserta link!`, "success");
                }
            } catch (err) {
                console.error("Gagal export file Excel:", err);
                if (window.showToast) {
                    window.showToast("Gagal mengunduh Excel di browser, mencoba download direct...", "info");
                }
                // Fallback direct download
                window.location.href = `/admin/guests/export?invitation_id=${this.invitationId}`;
            } finally {
                this.isExporting = false;
            }
        },

        /**
         * Eksekusi chunked upload anti RTO ke backend
         */
        async startChunkedExcelImport() {
            if (!this.excelRows.length) {
                if (window.showToast) {
                    window.showToast("Pilih file Excel yang memiliki data tamu terlebih dahulu.", "warning");
                }
                return;
            }

            if (!this.invitationId) {
                if (window.showToast) {
                    window.showToast("Undangan belum dipilih.", "error");
                }
                return;
            }

            this.isUploadingChunks = true;
            this.uploadProgress = 0;
            this.totalImportedCount = 0;

            const totalRows = this.excelRows.length;
            const totalChunks = Math.ceil(totalRows / this.chunkSize);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            try {
                for (let i = 0; i < totalChunks; i++) {
                    const chunk = this.excelRows.slice(i * this.chunkSize, (i + 1) * this.chunkSize);
                    const startIdx = i * this.chunkSize + 1;
                    const endIdx = Math.min((i + 1) * this.chunkSize, totalRows);

                    this.progressStatus = `Memproses batch ${i + 1} dari ${totalChunks} (baris ${startIdx} - ${endIdx} dari ${totalRows})...`;

                    const response = await fetch('/admin/guests/import-chunk', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            invitation_id: this.invitationId,
                            rows: chunk
                        })
                    });

                    if (!response.ok) {
                        const errData = await response.json().catch(() => ({}));
                        throw new Error(errData.message || `Gagal memproses batch ke-${i + 1}.`);
                    }

                    const result = await response.json();
                    this.totalImportedCount += (result.imported || chunk.length);

                    // Update persentase progress
                    this.uploadProgress = Math.round(((i + 1) / totalChunks) * 100);
                }

                this.progressStatus = `Selesai! Berhasil mengimpor ${this.totalImportedCount} data tamu.`;

                if (window.Swal) {
                    await window.Swal.fire({
                        icon: 'success',
                        title: 'Import Excel Berhasil!',
                        text: `Sebanyak ${this.totalImportedCount} data tamu berhasil ditambahkan tanpa kendala timeout (RTO).`,
                        confirmButtonColor: '#C25E2E',
                        confirmButtonText: 'Bagus, Lihat Data',
                        customClass: {
                            popup: 'rounded-3xl shadow-2xl backdrop-blur-xl bg-white/95 border border-stone-200 text-stone-800 font-sans',
                            confirmButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm',
                        }
                    });
                }

                this.showImportModal = false;
                this.resetExcelImport();

                // Reload halaman agar metric ringkasan dan tabel terupdate penuh
                window.location.reload();
            } catch (err) {
                console.error("Error chunked upload:", err);
                if (window.Swal) {
                    window.Swal.fire({
                        icon: 'error',
                        title: 'Import Terhenti!',
                        text: err.message || 'Terjadi kesalahan saat memproses data Excel.',
                        confirmButtonColor: '#C25E2E',
                        confirmButtonText: 'Tutup',
                        customClass: {
                            popup: 'rounded-3xl shadow-2xl backdrop-blur-xl bg-white/95 border border-stone-200 text-stone-800 font-sans',
                            confirmButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm',
                        }
                    });
                } else if (window.showToast) {
                    window.showToast(err.message || 'Terjadi kesalahan saat import.', 'error');
                }
            } finally {
                this.isUploadingChunks = false;
            }
        },

        /**
         * Open Edit Guest Modal
         */
        openEdit(guest) {
            this.editGuest.id = guest.id;
            this.editGuest.name = guest.name;
            this.editGuest.phone = guest.phone || '';
            this.editGuest.address = guest.address || '';
            this.editGuest.actionUrl = `/admin/guests/${guest.id}`;
            this.showEditModal = true;
        },

        /**
         * Copy guest's personal invitation URL to clipboard
         */
        copyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                if (window.showToast) {
                    window.showToast("Tautan personal tamu berhasil disalin!", "success");
                }
            }).catch(() => {
                prompt("Salin tautan ini:", url);
            });
        },

        /**
         * Send polite wedding invitation via WhatsApp with custom greeting, address, & personal URL
         */
        sendWhatsApp(phone, guestName, coupleNames, invitationUrl, address = '') {
            let cleanPhone = (phone || '').replace(/[^0-9]/g, '');
            if (cleanPhone.startsWith('0')) {
                cleanPhone = '62' + cleanPhone.substring(1);
            }

            const greetingLocation = address && address.trim() !== '' ? `di ${address}` : 'di Tempat';

            const message = 
`Kepada Yth.
*${guestName}*
${greetingLocation}

Assalamu'alaikum Wr. Wb. / Salam Sejahtera,

Tanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri momen bahagia pernikahan kami:

✨ *${coupleNames}* ✨

Informasi lengkap mengenai jadwal acara dan lokasi dapat diakses melalui tautan undangan digital berikut:
👉 ${invitationUrl}

Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.

Terima kasih.
Wassalamu'alaikum Wr. Wb.`;

            const waUrl = cleanPhone 
                ? `https://api.whatsapp.com/send?phone=${cleanPhone}&text=${encodeURIComponent(message)}`
                : `https://api.whatsapp.com/send?text=${encodeURIComponent(message)}`;

            window.open(waUrl, '_blank');
        },

        /**
         * Confirm guest deletion with SweetAlert2
         */
        async confirmDelete(guestName, formAction) {
            if (!window.showConfirmDialog) {
                if (confirm(`Hapus tamu "${guestName}" dari daftar?`)) {
                    this.submitDeleteForm(formAction);
                }
                return;
            }

            const result = await window.showConfirmDialog({
                title: 'Hapus Tamu Undangan?',
                text: `Nama "${guestName}" akan dihapus dari daftar tamu undangan ini.`,
                confirmButtonText: 'Ya, Hapus Tamu',
                cancelButtonText: 'Batal',
            });

            if (result.isConfirmed) {
                this.submitDeleteForm(formAction);
            }
        },

        submitDeleteForm(action) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = action;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (csrfToken) {
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken;
                form.appendChild(tokenInput);
            }

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }
    };
}
