/**
 * Alpine.js component for Admin Templates Catalog Management
 */
export default function templatesManager() {
    return {
        viewMode: localStorage.getItem('wedding_templates_view_mode') || 'grid',
        showCreateModal: false,
        showEditModal: false,
        showPreviewModal: false,

        // Create form state
        createForm: {
            name: '',
            slug: '',
            category: 'Rustic',
            price: '150000',
            description: '',
            is_active: true,
        },
        createThumbnailPreview: null,
        autoSlug: true,

        // Edit form state
        editForm: {
            id: null,
            name: '',
            slug: '',
            category: '',
            price: '',
            description: '',
            is_active: true,
            thumbnail_url: null,
            action_url: '',
        },
        editThumbnailPreview: null,

        // Preview state
        previewData: {
            id: null,
            name: '',
            slug: '',
            category: '',
            price: 0,
            formatted_price: 'Rp 0',
            description: '',
            thumbnail_url: null,
            is_active: true,
            invitations_count: 0,
            orders_count: 0,
            created_at: '',
        },

        init() {
            // Watch viewMode and persist
            this.$watch('viewMode', (val) => {
                localStorage.setItem('wedding_templates_view_mode', val);
            });
        },

        setViewMode(mode) {
            this.viewMode = mode;
        },

        /**
         * Auto-generate slug from name during create
         */
        onNameInput() {
            if (this.autoSlug) {
                this.createForm.slug = this.slugify(this.createForm.name);
            }
        },

        onSlugManualInput() {
            this.autoSlug = false;
        },

        slugify(text) {
            return text
                .toString()
                .toLowerCase()
                .trim()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        },

        /**
         * Live thumbnail preview for create form
         */
        handleCreateThumbnail(e) {
            const file = e.target.files[0];
            if (file) {
                this.createThumbnailPreview = URL.createObjectURL(file);
            } else {
                this.createThumbnailPreview = null;
            }
        },

        /**
         * Live thumbnail preview for edit form
         */
        handleEditThumbnail(e) {
            const file = e.target.files[0];
            if (file) {
                this.editThumbnailPreview = URL.createObjectURL(file);
            } else {
                this.editThumbnailPreview = null;
            }
        },

        /**
         * Open modal for editing template
         */
        openEdit(template) {
            this.editForm = {
                id: template.id,
                name: template.name,
                slug: template.slug,
                category: template.category,
                price: template.price,
                description: template.description || '',
                is_active: Boolean(template.is_active),
                thumbnail_url: template.thumbnail_url,
                action_url: `/admin/templates/${template.id}`,
            };
            this.editThumbnailPreview = null;
            this.showEditModal = true;
        },

        /**
         * Open modal to preview template details
         */
        openPreview(template) {
            this.previewData = {
                id: template.id,
                name: template.name,
                slug: template.slug,
                category: template.category,
                price: template.price,
                formatted_price: new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0,
                }).format(template.price),
                description: template.description || 'Belum ada deskripsi untuk template ini.',
                thumbnail_url: template.thumbnail_url,
                is_active: Boolean(template.is_active),
                invitations_count: template.invitations_count || 0,
                orders_count: template.orders_count || 0,
                created_at: template.created_at,
            };
            this.showPreviewModal = true;
        },

        /**
         * Toggle active status via AJAX
         */
        async toggleStatus(url, templateId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    if (window.showToast) {
                        window.showToast(data.message, 'success');
                    }
                    // Reload or refresh state
                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                } else {
                    throw new Error(data.message || 'Gagal mengubah status template');
                }
            } catch (err) {
                console.error(err);
                if (window.showToast) {
                    window.showToast(err.message || 'Terjadi kesalahan sistem', 'error');
                }
            }
        },

        /**
         * Confirm and delete template
         */
        async confirmDelete(name, deleteUrl, canDelete) {
            if (!canDelete) {
                if (window.Swal) {
                    window.Swal.fire({
                        icon: 'warning',
                        title: 'Template Sedang Digunakan!',
                        text: `Template "${name}" tidak dapat dihapus karena masih digunakan oleh pesanan atau undangan pernikahan aktif.`,
                        confirmButtonColor: '#C25E2E',
                        confirmButtonText: 'Saya Mengerti',
                        customClass: {
                            popup: 'rounded-3xl shadow-2xl backdrop-blur-xl bg-white/95 border border-stone-200 text-stone-800 font-sans',
                            confirmButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm',
                        },
                    });
                }
                return;
            }

            if (window.showConfirmDialog) {
                const result = await window.showConfirmDialog({
                    title: `Hapus Template "${name}"?`,
                    text: 'Template yang dihapus tidak dapat dipulihkan kembali dari katalog.',
                    confirmButtonText: 'Ya, Hapus Template',
                    cancelButtonText: 'Batal',
                });

                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;

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
            }
        },
    };
}
