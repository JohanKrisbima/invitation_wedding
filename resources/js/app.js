import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// Expose globally so external JS files (e.g., template scripts, admin scripts) can use them
window.Alpine = Alpine;
window.Swal = Swal;

/**
 * Toast Notification Helper (Glassmorphic / Boutique Warm Theme)
 */
window.showToast = (message, icon = 'success') => {
    return Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            popup: 'rounded-2xl shadow-xl backdrop-blur-md bg-stone-900/90 text-stone-100 border border-white/10 text-sm font-sans',
        },
    });
};

/**
 * Confirm Dialog Helper (e.g. for delete or critical actions)
 */
window.showConfirmDialog = ({ title = 'Apakah Anda yakin?', text = 'Tindakan ini tidak dapat dibatalkan.', confirmButtonText = 'Ya, Lanjutkan', cancelButtonText = 'Batal' }) => {
    return Swal.fire({
        title,
        text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#C25E2E',
        cancelButtonColor: '#78716C',
        confirmButtonText,
        cancelButtonText,
        customClass: {
            popup: 'rounded-3xl shadow-2xl backdrop-blur-xl bg-white/95 border border-stone-200 text-stone-800 font-sans',
            confirmButton: 'rounded-xl px-4 py-2 font-medium shadow-sm',
            cancelButton: 'rounded-xl px-4 py-2 font-medium',
        },
    });
};

/**
 * Auto-detect Laravel Flash Messages from hidden DOM attributes (Zero inline script)
 */
document.addEventListener('DOMContentLoaded', () => {
    const flashElement = document.getElementById('app-flash-message');
    if (flashElement) {
        const type = flashElement.dataset.type || 'info';
        const message = flashElement.dataset.message || '';
        if (message) {
            if (type === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan!',
                    text: message,
                    confirmButtonColor: '#C25E2E',
                    confirmButtonText: 'Tutup & Periksa Kembali',
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl backdrop-blur-xl bg-white/95 border border-stone-200 text-stone-800 font-sans',
                        confirmButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm',
                    },
                });
            } else {
                window.showToast(message, type);
            }
        }
    }
});

import ordersTable from './admin/orders-table';
import eventsManager from './admin/events-manager';
import formSubmit from './admin/form-submit';

// Register Alpine Components
Alpine.data('ordersTable', ordersTable);
Alpine.data('eventsManager', eventsManager);
Alpine.data('formSubmit', formSubmit);

// Start Alpine
Alpine.start();
