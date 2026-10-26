         import * as XLSX from 'xlsx';

/**
 * Alpine.js component for Buku Tamu & RSVP Management
 */
export default function guestbook(config = {}) {
    return {
        activeTab: 'rsvp', // 'rsvp' or 'messages'
        invitationId: config.invitationId || null,

        // Filters for RSVP
        rsvpSearch: '',
        rsvpStatus: 'all',
        isRsvpLoading: false,
        isExportingRsvp: false,

        // Filters for Guestbook Messages
        msgSearch: '',
        msgStatus: 'all',
        isMsgLoading: false,
        isExportingMsg: false,

        /**
         * Switch tab
         */
        switchTab(tab) {
            this.activeTab = tab;
        },

        /**
         * Switch selected invitation via dropdown
         */
        switchInvitation(newId) {
            if (!newId) return;
            window.location.href = `${window.location.pathname}?invitation_id=${newId}`;
        },

        /**
         * Live search & filter for RSVPs
         */
        async filterRsvp() {
            this.isRsvpLoading = true;
            try {
                const params = new URLSearchParams();
                if (this.invitationId) params.append('invitation_id', this.invitationId);
                if (this.rsvpSearch) params.append('rsvp_search', this.rsvpSearch);
                if (this.rsvpStatus && this.rsvpStatus !== 'all') params.append('rsvp_status', this.rsvpStatus);
                params.append('partial', 'rsvp');

                const response = await fetch(`${window.location.pathname}?${params.toString()}`, {
                    headers: {
                        'X-Alpine-Request': 'true',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Gagal memuat data RSVP');
                const html = await response.text();

                const container = document.getElementById('rsvp-table-container');
                if (container) {
                    container.innerHTML = html;
                }
            } catch (err) {
                console.error(err);
                if (window.showToast) window.showToast('Gagal memfilter data RSVP', 'error');
            } finally {
                this.isRsvpLoading = false;
            }
        },

        /**
         * Live search & filter for Guest Messages
         */
        async filterMessages() {
            this.isMsgLoading = true;
            try {
                const params = new URLSearchParams();
                if (this.invitationId) params.append('invitation_id', this.invitationId);
                if (this.msgSearch) params.append('msg_search', this.msgSearch);
                if (this.msgStatus && this.msgStatus !== 'all') params.append('msg_status', this.msgStatus);
                params.append('partial', 'messages');

                const response = await fetch(`${window.location.pathname}?${params.toString()}`, {
                    headers: {
                        'X-Alpine-Request': 'true',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Gagal memuat ucapan tamu');
                const html = await response.text();

                const container = document.getElementById('messages-list-container');
                if (container) {
                    container.innerHTML = html;
                }
            } catch (err) {
                console.error(err);
                if (window.showToast) window.showToast('Gagal memfilter ucapan tamu', 'error');
            } finally {
                this.isMsgLoading = false;
            }
        },

        /**
         * Toggle Message Approval (Tampilkan / Sembunyikan Ucapan)
         */
        async toggleApproval(messageId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            try {
                const response = await fetch(`/admin/guestbook/messages/${messageId}/toggle-approval`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Gagal mengubah status moderasi ucapan');

                const result = await response.json();
                if (window.showToast) {
                    window.showToast(result.message || 'Status moderasi berhasil diubah', 'success');
                }

                // Refresh pesan
                this.filterMessages();
            } catch (err) {
                console.error(err);
                if (window.showToast) window.showToast('Terjadi kesalahan saat memoderasi pesan', 'error');
            }
        },

        /**
         * Confirm & Delete Message with SweetAlert2
         */
        async confirmDeleteMessage(messageId, guestName) {
            if (!window.showConfirmDialog) {
                if (confirm(`Hapus ucapan dari "${guestName}"?`)) {
                    this.submitDeleteForm(`/admin/guestbook/messages/${messageId}`);
                }
                return;
            }

            const result = await window.showConfirmDialog({
                title: 'Hapus Ucapan Doa Restu?',
                text: `Ucapan dari "${guestName}" akan dihapus permanen dari buku tamu.`,
                confirmButtonText: 'Ya, Hapus Ucapan',
                cancelButtonText: 'Batal',
            });

            if (result.isConfirmed) {
                this.submitDeleteForm(`/admin/guestbook/messages/${messageId}`);
            }
        },

        /**
         * Confirm & Delete RSVP with SweetAlert2
         */
        async confirmDeleteRsvp(rsvpId, guestName) {
            if (!window.showConfirmDialog) {
                if (confirm(`Hapus konfirmasi RSVP dari "${guestName}"?`)) {
                    this.submitDeleteForm(`/admin/guestbook/rsvps/${rsvpId}`);
                }
                return;
            }

            const result = await window.showConfirmDialog({
                title: 'Hapus Konfirmasi RSVP?',
                text: `Data konfirmasi kehadiran "${guestName}" akan dihapus.`,
                confirmButtonText: 'Ya, Hapus RSVP',
                cancelButtonText: 'Batal',
            });

            if (result.isConfirmed) {
                this.submitDeleteForm(`/admin/guestbook/rsvps/${rsvpId}`);
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
        },

        /**
         * Export RSVP Data as Excel (.xlsx)
         */
        async exportRsvpExcel() {
            if (!this.invitationId) return;

            this.isExportingRsvp = true;
            try {
                const response = await fetch(`/admin/guestbook/export-rsvp-data?invitation_id=${this.invitationId}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (!response.ok) throw new Error('Gagal mengambil data RSVP');
                const data = await response.json();

                if (!data.rsvps || data.rsvps.length === 0) {
                    if (window.showToast) window.showToast('Belum ada data RSVP untuk diunduh', 'warning');
                    return;
                }

                const headers = ['No', 'Nama Tamu', 'No WhatsApp', 'Kota / Alamat', 'Status Kehadiran', 'Jumlah Hadir (Pax)', 'Catatan / Pesan', 'Waktu Konfirmasi'];
                const rows = [headers];

                data.rsvps.forEach(r => {
                    rows.push([
                        r.no,
                        r.guest_name,
                        r.phone,
                        r.address,
                        r.status,
                        r.guest_count,
                        r.note,
                        r.created_at
                    ]);
                });

                const ws = XLSX.utils.aoa_to_sheet(rows);
                ws['!cols'] = [
                    { wch: 6 },
                    { wch: 28 },
                    { wch: 18 },
                    { wch: 24 },
                    { wch: 18 },
                    { wch: 18 },
                    { wch: 45 },
                    { wch: 20 },
                ];

                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Rekap RSVP');
                XLSX.writeFile(wb, `Rekap_RSVP_${data.invitation_slug || 'wedding'}.xlsx`);

                if (window.showToast) window.showToast(`Berhasil mengunduh ${data.rsvps.length} data RSVP!`, 'success');
            } catch (err) {
                console.error(err);
                if (window.showToast) window.showToast('Gagal mengunduh file Excel RSVP', 'error');
            } finally {
                this.isExportingRsvp = false;
            }
        },

        /**
         * Export Guest Messages as Excel (.xlsx)
         */
        async exportMessagesExcel() {
            if (!this.invitationId) return;

            this.isExportingMsg = true;
            try {
                const response = await fetch(`/admin/guestbook/export-messages-data?invitation_id=${this.invitationId}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (!response.ok) throw new Error('Gagal mengambil data ucapan');
                const data = await response.json();

                if (!data.messages || data.messages.length === 0) {
                    if (window.showToast) window.showToast('Belum ada ucapan doa untuk diunduh', 'warning');
                    return;
                }

                const headers = ['No', 'Nama Tamu', 'No WhatsApp', 'Ucapan & Doa Restu', 'Status Publikasi', 'Waktu Kirim'];
                const rows = [headers];

                data.messages.forEach(m => {
                    rows.push([
                        m.no,
                        m.guest_name,
                        m.phone,
                        m.message,
                        m.status,
                        m.created_at
                    ]);
                });

                const ws = XLSX.utils.aoa_to_sheet(rows);
                ws['!cols'] = [
                    { wch: 6 },
                    { wch: 28 },
                    { wch: 18 },
                    { wch: 60 },
                    { wch: 16 },
                    { wch: 20 },
                ];

                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Buku Tamu');
                XLSX.writeFile(wb, `Buku_Tamu_Ucapan_${data.invitation_slug || 'wedding'}.xlsx`);

                if (window.showToast) window.showToast(`Berhasil mengunduh ${data.messages.length} ucapan tamu!`, 'success');
            } catch (err) {
                console.error(err);
                if (window.showToast) window.showToast('Gagal mengunduh file Excel ucapan', 'error');
            } finally {
                this.isExportingMsg = false;
            }
        }
    };
}
