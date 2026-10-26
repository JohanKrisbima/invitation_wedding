/**
 * Alpine.js component for server-side invitations table (debounced live search, filter, pagination)
 */
export default function invitationsTable() {
    return {
        search: "",
        status: "all",
        template: "all",
        isLoading: false,

        /**
         * Fetch a specific page or filter result from the server via AJAX
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

                const container = document.getElementById(
                    "invitations-table-container",
                );
                if (container) {
                    container.innerHTML = html;
                }
            } catch (err) {
                console.error("Error saat memperbarui tabel undangan:", err);
                if (window.showToast) {
                    window.showToast("Gagal memuat data undangan", "error");
                }
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Triggered on search input or dropdown filter change
         */
        filterInvitations() {
            const params = new URLSearchParams();
            if (this.search) params.append("search", this.search);
            if (this.status && this.status !== "all")
                params.append("status", this.status);
            if (this.template && this.template !== "all")
                params.append("template", this.template);
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
            this.template = "all";
            this.filterInvitations();
        },

        /**
         * Copy public invitation link to clipboard
         */
        copyLink(slug) {
            const url = `${window.location.origin}/${slug}`;
            navigator.clipboard.writeText(url).then(() => {
                if (window.showToast) {
                    window.showToast("Tautan undangan berhasil disalin!", "success");
                }
            }).catch(() => {
                prompt("Salin tautan ini:", url);
            });
        }
    };
}
