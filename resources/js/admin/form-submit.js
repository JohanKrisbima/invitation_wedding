/**
 * Alpine.js component for Form Submission & File Size Validation
 * Prevents double-clicking by hiding submit button and showing loading state.
 * Validates file sizes before uploading to avoid server timeouts.
 */
export default function formSubmit(defaultMaxMb = 5) {
    return {
        isSubmitting: false,

        /**
         * Triggered when file input changes
         * Validates file size instantly on the client side
         */
        validateFileSize(event, maxMb = defaultMaxMb) {
            const files = event.target.files;
            if (!files || files.length === 0) return;

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

                    // Reset input so invalid file is not submitted
                    event.target.value = '';
                    return false;
                }
            }

            return true;
        },

        /**
         * Triggered on form submit
         * Hides submit button to prevent double-clicking
         */
        handleSubmit() {
            this.isSubmitting = true;

            // Failsafe reset after 20 seconds in case network stalls
            setTimeout(() => {
                this.isSubmitting = false;
            }, 20000);
        }
    };
}
