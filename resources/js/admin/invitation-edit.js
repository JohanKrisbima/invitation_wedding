/**
 * Alpine.js Page Controller for Invitation Edit Page
 * Location: resources/views/admin/invitations/edit.blade.php
 *
 * Responsibilities:
 * 1. Multi-Tab Navigation (couple, events, gallery, publication)
 * 2. Dynamic Event Cards Management (Akad, Resepsi, add/remove row)
 * 3. Client-Side Photo Size Validation (strict max 5 MB with SweetAlert)
 * 4. Submit Loading State & Double-Click Prevention
 */
export default function invitationEdit(config = {}) {
    const initialEvents = config.initialEvents || [];
    const maxPhotoSizeMb = config.maxPhotoSizeMb || 5;

    return {
        // Tab State
        activeTab: config.initialTab || 'couple',

        setTab(tab) {
            this.activeTab = tab;
        },

        // Events List (Akad Nikah, Resepsi, etc.)
        events: initialEvents && initialEvents.length > 0
            ? initialEvents.map(e => ({
                id: e.id || null,
                event_type: e.event_type || '',
                start_at: e.start_at ? e.start_at.replace(' ', 'T').substring(0, 16) : '',
                end_at: e.end_at ? e.end_at.replace(' ', 'T').substring(0, 16) : '',
                venue_name: e.venue_name || '',
                address: e.address || '',
                maps_url: e.maps_url || '',
            }))
            : [
                {
                    id: null,
                    event_type: 'Akad Nikah',
                    start_at: '',
                    end_at: '',
                    venue_name: '',
                    address: '',
                    maps_url: '',
                },
                {
                    id: null,
                    event_type: 'Resepsi Pernikahan',
                    start_at: '',
                    end_at: '',
                    venue_name: '',
                    address: '',
                    maps_url: '',
                }
            ],

        /**
         * Add a new blank event card
         */
        addEvent() {
            this.events.push({
                id: null,
                event_type: '',
                start_at: '',
                end_at: '',
                venue_name: '',
                address: '',
                maps_url: '',
            });
        },

        /**
         * Remove an event card by index (minimum 1 event required)
         */
        removeEvent(index) {
            if (this.events.length <= 1) {
                if (window.showToast) {
                    window.showToast('Minimal harus memiliki satu jadwal acara.', 'warning');
                }
                return;
            }
            this.events.splice(index, 1);
        },

        // Form Submit & Double-Click Prevention State
        isSubmitting: false,
        submittingTab: null,

        /**
         * Triggered when any form in the tabs is submitted
         */
        handleSubmit(tabName = '') {
            this.isSubmitting = true;
            this.submittingTab = tabName;

            // Failsafe timeout in case of network stall
            setTimeout(() => {
                this.isSubmitting = false;
                this.submittingTab = null;
            }, 25000);
        },

        /**
         * Validate file size instantly on file input change
         */
        validateFileSize(event, maxMb = maxPhotoSizeMb) {
            const files = event.target.files;
            if (!files || files.length === 0) return true;

            const maxBytes = maxMb * 1024 * 1024;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.size > maxBytes) {
                    const fileSizeMb = (file.size / (1024 * 1024)).toFixed(2);

                    if (window.Swal) {
                        window.Swal.fire({
                            icon: 'error',
                            title: 'Ukuran Foto Terlalu Besar!',
                            text: `Maaf, foto "${file.name}" berukuran ${fileSizeMb} MB (melebihi batas maksimal ${maxMb} MB). Silakan pilih foto dengan ukuran di bawah ${maxMb} MB.`,
                            confirmButtonColor: '#C25E2E',
                            confirmButtonText: 'Tutup & Pilih Ulang',
                            customClass: {
                                popup: 'rounded-3xl shadow-2xl backdrop-blur-xl bg-white/95 border border-stone-200 text-stone-800 font-sans',
                                confirmButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm',
                            },
                        });
                    } else if (window.showToast) {
                        window.showToast(`Maaf, foto "${file.name}" (${fileSizeMb} MB) melebihi batas ${maxMb} MB!`, 'error');
                    } else {
                        alert(`Maaf, foto "${file.name}" (${fileSizeMb} MB) melebihi batas maksimal ${maxMb} MB.`);
                    }

                    // Reset input so invalid file is rejected
                    event.target.value = '';
                    return false;
                }
            }

            return true;
        }
    };
}
