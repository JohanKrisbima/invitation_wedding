/**
 * Alpine.js component for managing dynamic wedding events (Akad, Resepsi, etc.)
 */
export default function eventsManager(initialEvents = []) {
    return {
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
         * Remove an event card by index
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

        isSubmitting: false,

        /**
         * Handle form submission to prevent double clicks
         */
        handleSubmit() {
            this.isSubmitting = true;
            setTimeout(() => {
                this.isSubmitting = false;
            }, 20000);
        }
    };
}
