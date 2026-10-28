/**
 * Floral Elegance — Interactive Engine (Batik-Botanical Edition)
 * Standard: Clean Vanilla JS, Modular, Zero External CDN, Asynchronous Backend Integration
 */

let rsvpPagination = null;
let guestbookPagination = null;
let galleryPagination = null;

document.addEventListener('DOMContentLoaded', () => {
    initSplitCover();
    initHeroParallax();
    initPetalsCanvas();
    initLetterReveal();
    initOvalTilt();
    initStoryAccordion();
    initMovingCountdown();
    initBottomNavObserver();
    initAudioController();
    initGalleryLightbox();
    initClipboardCopy();
    initRsvpForm();
    initGuestbookForm();
    initInteractiveFlowers();
    initStreamPaginations();
});

/**
 * 1. Split-Screen Cover Doors ("Pintu Terbelah")
 */
function initSplitCover() {
    const coverWrapper = document.getElementById('invitation-cover-doors');
    const openBtn = document.getElementById('btn-open-invitation');
    const audio = document.getElementById('bg-music-player');
    const bottomNav = document.getElementById('fe-bottom-navbar');

    if (!coverWrapper || !openBtn) return;

    openBtn.addEventListener('click', () => {
        // Pintu terbelah ke kiri dan kanan
        coverWrapper.classList.add('fe-doors-opened');

        // Buka audio musik
        if (audio) {
            audio.play().then(() => {
                updateAudioUI(true);
            }).catch(() => {
                updateAudioUI(false);
            });
        }

        // Jalankan efek kemunculan huruf nama di hero
        triggerLetterReveal();

        // Tampilkan floating bottom nav
        if (bottomNav) {
            bottomNav.classList.remove('fe-nav-hidden');
            bottomNav.classList.add('fe-nav-visible');
        }

        // Setelah transisi pintu selesai (1.4s), sembunyikan container cover sepenuhnya
        setTimeout(() => {
            coverWrapper.classList.add('fe-doors-hidden');
            document.body.classList.remove('overflow-hidden');
        }, 1400);
    });
}

/**
 * 2. Letter-by-Letter Hero Reveal
 */
function initLetterReveal() {
    // Siapkan teks agar bisa dianimasikan huruf per huruf
    const targets = document.querySelectorAll('.fe-reveal-text');
    targets.forEach(target => {
        const text = target.textContent.trim();
        target.innerHTML = '';
        const chars = text.split('');
        chars.forEach((char, index) => {
            const span = document.createElement('span');
            span.textContent = char === ' ' ? '\u00A0' : char;
            span.className = 'fe-letter-reveal';
            span.style.animationDelay = `${(index * 0.05) + 0.2}s`;
            target.appendChild(span);
        });
    });
}

function triggerLetterReveal() {
    const letters = document.querySelectorAll('.fe-letter-reveal');
    letters.forEach(letter => {
        letter.style.animationPlayState = 'running';
    });
}

/**
 * 3. Left Hero Desktop Parallax on Mouse Move
 */
function initHeroParallax() {
    const hero = document.getElementById('left-hero-container');
    const bgLayer = document.getElementById('left-hero-bg');
    const contentLayer = document.getElementById('left-hero-content');

    if (!hero || !bgLayer || window.innerWidth < 900) return;

    hero.addEventListener('mousemove', (e) => {
        const rect = hero.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width - 0.5;
        const y = (e.clientY - rect.top) / rect.height - 0.5;

        bgLayer.style.transform = `scale(1.08) translate(${x * 16}px, ${y * 16}px)`;
        if (contentLayer) {
            contentLayer.style.transform = `translate(${x * -10}px, ${y * -10}px)`;
        }
    });

    hero.addEventListener('mouseleave', () => {
        bgLayer.style.transform = 'scale(1.04) translate(0, 0)';
        if (contentLayer) {
            contentLayer.style.transform = 'translate(0, 0)';
        }
    });
}

/**
 * 4. Falling Flower Petals Canvas
 */
function initPetalsCanvas() {
    const canvas = document.getElementById('fe-petals-canvas');
    if (!canvas) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        canvas.style.display = 'none';
        return;
    }

    const ctx = canvas.getContext('2d');
    let width = canvas.width = window.innerWidth;
    let height = canvas.height = window.innerHeight;

    window.addEventListener('resize', () => {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    });

    const petalCount = window.innerWidth < 768 ? 18 : 32;
    const petals = [];
    const colors = ['#f4d6cd', '#d9a5a0', '#e8d5be', '#fdf0e6'];

    for (let i = 0; i < petalCount; i++) {
        petals.push({
            x: Math.random() * width,
            y: Math.random() * height - height,
            size: Math.random() * 8 + 6,
            speedY: Math.random() * 1.2 + 0.6,
            speedX: Math.random() * 0.8 - 0.4,
            rotation: Math.random() * 360,
            rotationSpeed: (Math.random() - 0.5) * 1.5,
            color: colors[Math.floor(Math.random() * colors.length)],
            opacity: Math.random() * 0.5 + 0.35,
            oscillation: Math.random() * Math.PI * 2,
            oscillationSpeed: Math.random() * 0.02 + 0.01,
        });
    }

    function render() {
        ctx.clearRect(0, 0, width, height);

        petals.forEach(p => {
            p.y += p.speedY;
            p.oscillation += p.oscillationSpeed;
            p.x += Math.sin(p.oscillation) * 0.7 + p.speedX;
            p.rotation += p.rotationSpeed;

            // Jika kelopak jatuh melampaui layar bawah, reset ke atas
            if (p.y > height + 20) {
                p.y = -20;
                p.x = Math.random() * width;
            }

            ctx.save();
            ctx.translate(p.x, p.y);
            ctx.rotate((p.rotation * Math.PI) / 180);
            ctx.globalAlpha = p.opacity;
            ctx.fillStyle = p.color;

            // Gambar bentuk kelopak bunga oval lengkung
            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.bezierCurveTo(p.size / 2, -p.size / 2, p.size, 0, p.size / 2, p.size);
            ctx.bezierCurveTo(0, p.size / 2, -p.size / 2, 0, 0, 0);
            ctx.fill();
            ctx.restore();
        });

        requestAnimationFrame(render);
    }

    render();
}

/**
 * 5. 3D Tilt Oval Photo Frames
 */
function initOvalTilt() {
    const cards = document.querySelectorAll('.fe-oval-tilt');

    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            const rotateX = -(y / (rect.height / 2)) * 10;
            const rotateY = (x / (rect.width / 2)) * 10;

            card.style.transform = `perspective(600px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.03)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(600px) rotateX(0deg) rotateY(0deg) scale(1)';
        });
    });
}

/**
 * 6. Love Story Accordion
 */
function initStoryAccordion() {
    const headers = document.querySelectorAll('.fe-accordion-header');

    headers.forEach(header => {
        header.addEventListener('click', () => {
            const item = header.closest('.fe-accordion-item');
            if (!item) return;

            const wasActive = item.classList.contains('fe-accordion-active');

            // Tutup semua item lain
            document.querySelectorAll('.fe-accordion-item').forEach(other => {
                other.classList.remove('fe-accordion-active');
            });

            // Toggle item yang diklik
            if (!wasActive) {
                item.classList.add('fe-accordion-active');
            }
        });
    });
}

/**
 * 7. Live Countdown with Animated Digits
 */
function initMovingCountdown() {
    const container = document.getElementById('countdown-container');
    if (!container) return;

    const targetDateStr = container.getAttribute('data-wedding-date');
    if (!targetDateStr) return;

    // Handle Indonesian Western Time (WIB / UTC+07:00) consistently
    let targetTime;
    if (targetDateStr.includes('+') || targetDateStr.endsWith('Z')) {
        targetTime = new Date(targetDateStr).getTime();
    } else {
        const cleanStr = targetDateStr.replace(' ', 'T');
        targetTime = new Date(`${cleanStr}+07:00`).getTime();
    }

    if (isNaN(targetTime)) return;

    const daysEl = document.getElementById('fe-count-days');
    const hoursEl = document.getElementById('fe-count-hours');
    const minutesEl = document.getElementById('fe-count-minutes');
    const secondsEl = document.getElementById('fe-count-seconds');

    let prevSec = '';

    function update() {
        const now = new Date().getTime();
        const difference = targetTime - now;

        if (difference <= 0) {
            if (daysEl && daysEl.textContent !== '00') daysEl.textContent = '00';
            if (hoursEl && hoursEl.textContent !== '00') hoursEl.textContent = '00';
            if (minutesEl && minutesEl.textContent !== '00') minutesEl.textContent = '00';
            if (secondsEl && secondsEl.textContent !== '00') secondsEl.textContent = '00';
            return;
        }

        const days = String(Math.floor(difference / (1000 * 60 * 60 * 24))).padStart(2, '0');
        const hours = String(Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
        const minutes = String(Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
        const seconds = String(Math.floor((difference % (1000 * 60)) / 1000)).padStart(2, '0');

        if (daysEl && daysEl.textContent !== days) daysEl.textContent = days;
        if (hoursEl && hoursEl.textContent !== hours) hoursEl.textContent = hours;
        if (minutesEl && minutesEl.textContent !== minutes) minutesEl.textContent = minutes;

        if (secondsEl && secondsEl.textContent !== seconds) {
            secondsEl.textContent = seconds;
            secondsEl.classList.remove('fe-digit-change');
            void secondsEl.offsetWidth; // Trigger reflow
            secondsEl.classList.add('fe-digit-change');
        }
    }

    update();
    setInterval(update, 1000);
}

/**
 * 8. Bottom Icon Navigation & Active Observer
 */
function initBottomNavObserver() {
    const sections = document.querySelectorAll('section[id]');
    const navItems = document.querySelectorAll('.fe-nav-item');
    const rightPanel = document.getElementById('right-scroll-panel');

    if (!sections.length || !navItems.length) return;

    // Smooth click handler
    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = item.getAttribute('data-target');
            const targetSection = document.getElementById(targetId);

            if (targetSection) {
                if (window.innerWidth >= 900 && rightPanel) {
                    targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    targetSection.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });

    // Observer untuk menyorot ikon aktif
    const observerRoot = window.innerWidth >= 900 ? rightPanel : null;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                navItems.forEach(item => {
                    if (item.getAttribute('data-target') === id) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            }
        });
    }, {
        root: observerRoot,
        threshold: 0.35,
    });

    sections.forEach(sec => observer.observe(sec));
}

/**
 * 9. Audio Player Toggle
 */
function initAudioController() {
    const audio = document.getElementById('bg-music-player');
    const toggleBtn = document.getElementById('btn-audio-toggle');

    if (!audio || !toggleBtn) return;

    toggleBtn.addEventListener('click', () => {
        if (audio.paused) {
            audio.play().then(() => {
                updateAudioUI(true);
            }).catch(err => console.log('Audio playback error:', err));
        } else {
            audio.pause();
            updateAudioUI(false);
        }
    });
}

function updateAudioUI(isPlaying) {
    const toggleBtn = document.getElementById('btn-audio-toggle');
    const playIcon = document.getElementById('audio-play-icon');
    const pauseIcon = document.getElementById('audio-pause-icon');

    if (!toggleBtn) return;

    if (isPlaying) {
        if (playIcon) playIcon.classList.add('hidden');
        if (pauseIcon) pauseIcon.classList.remove('hidden');
        toggleBtn.classList.add('animate-pulse');
        toggleBtn.setAttribute('title', 'Jeda Musik');
    } else {
        if (playIcon) playIcon.classList.remove('hidden');
        if (pauseIcon) pauseIcon.classList.add('hidden');
        toggleBtn.classList.remove('animate-pulse');
        toggleBtn.setAttribute('title', 'Putar Musik');
    }
}

/**
 * 10. Photo Lightbox Modal
 */
function initGalleryLightbox() {
    const modal = document.getElementById('gallery-lightbox-modal');
    const modalImg = document.getElementById('lightbox-image');
    const modalCaption = document.getElementById('lightbox-caption');
    const closeBtn = document.getElementById('btn-close-lightbox');

    if (!modal || !modalImg) return;

    document.querySelectorAll('.fe-gallery-item').forEach(item => {
        item.addEventListener('click', () => {
            const fullUrl = item.getAttribute('data-full-img');
            const caption = item.getAttribute('data-caption') || '';

            modalImg.src = fullUrl;
            if (modalCaption) modalCaption.textContent = caption;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        });
    });

    const closeModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
        modalImg.src = '';
    };

    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
}

/**
 * 11. Clipboard Copy with Toast
 */
function initClipboardCopy() {
    document.querySelectorAll('.fe-btn-copy').forEach(btn => {
        btn.addEventListener('click', async () => {
            const textToCopy = btn.getAttribute('data-copy-text');
            if (!textToCopy) return;

            try {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    await navigator.clipboard.writeText(textToCopy);
                } else {
                    // Fallback
                    const temp = document.createElement('textarea');
                    temp.value = textToCopy;
                    document.body.appendChild(temp);
                    temp.select();
                    document.execCommand('copy');
                    document.body.removeChild(temp);
                }

                const label = btn.querySelector('.copy-label');
                const originalText = label ? label.textContent : '';

                if (label) label.textContent = 'Tersalin!';
                btn.classList.add('bg-[#6f8260]', 'text-white');

                showToast('Nomor rekening berhasil disalin!');

                setTimeout(() => {
                    if (label) label.textContent = originalText;
                    btn.classList.remove('bg-[#6f8260]', 'text-white');
                }, 2000);
            } catch (err) {
                showToast('Gagal menyalin nomor secara otomatis.', 'error');
            }
        });
    });
}

/**
 * 12. Asynchronous AJAX RSVP Confirmation
 */
function initRsvpForm() {
    const form = document.getElementById('rsvp-form');
    if (!form) return;

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnHtml = submitBtn ? submitBtn.innerHTML : 'Kirim Konfirmasi';

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        const actionUrl = form.getAttribute('action');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan Konfirmasi...</span>
            `;
        }

        try {
            const response = await fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const result = await response.json();

            if (response.ok && result.success) {
                showToast(result.message || 'Konfirmasi kehadiran Anda telah tercatat!');

                const statusNotice = document.getElementById('rsvp-success-notice');
                if (statusNotice) {
                    statusNotice.classList.remove('hidden');
                    statusNotice.textContent = 'Konfirmasi Anda telah tersimpan dengan aman di sistem. Terima kasih!';
                }

                // Dynamically update the RSVP stream list
                const rsvpStreamList = document.getElementById('rsvp-stream-list');
                const emptyNotice = document.getElementById('empty-rsvp-notice');
                if (emptyNotice) emptyNotice.classList.add('hidden');

                if (rsvpStreamList && result.data) {
                    const guestNameLower = (result.data.guest_name || '').toLowerCase();
                    // Remove existing card for this guest if updating
                    const existingCard = rsvpStreamList.querySelector(`[data-guest-name="${CSS.escape(guestNameLower)}"]`);
                    if (existingCard) {
                        existingCard.remove();
                    }

                    const initialLetter = (result.data.guest_name || 'T').charAt(0).toUpperCase();
                    let badgeHtml = '';
                    if (result.data.status === 'attending') {
                        badgeHtml = `
                            <span class="inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Hadir (${result.data.guest_count || 1})
                            </span>
                        `;
                    } else if (result.data.status === 'tentative') {
                        badgeHtml = `
                            <span class="inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Ragu-ragu
                            </span>
                        `;
                    } else {
                        badgeHtml = `
                            <span class="inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full bg-stone-100 border border-stone-200 text-stone-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-stone-400"></span>
                                Tidak Hadir
                            </span>
                        `;
                    }

                    const noteHtml = result.data.note ? `
                        <p class="text-xs text-stone-600 font-cormorant leading-relaxed italic truncate">
                            &ldquo;${escapeHtml(result.data.note)}&rdquo;
                        </p>
                    ` : `
                        <span class="text-[10px] text-stone-400 font-cormorant">Telah konfirmasi</span>
                    `;

                    const newCard = document.createElement('div');
                    newCard.className = 'p-3 rounded-xl bg-white/95 border border-[#6e1c27]/15 shadow-2xs transition transform duration-300';
                    newCard.setAttribute('data-guest-name', guestNameLower);
                    newCard.setAttribute('data-attendance-status', result.data.status || 'attending');
                    newCard.innerHTML = `
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-6 h-6 rounded-full bg-[#6e1c27]/10 text-[#6e1c27] font-bold text-[10px] flex items-center justify-center font-playfair shrink-0">
                                    ${escapeHtml(initialLetter)}
                                </div>
                                <span class="font-bold text-xs text-stone-900 font-playfair truncate">${escapeHtml(result.data.guest_name)}</span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                ${badgeHtml}
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-2 pl-8">
                            ${noteHtml}
                            <span class="text-[9px] text-stone-400 font-medium shrink-0">Baru saja</span>
                        </div>
                    `;

                    rsvpStreamList.prepend(newCard);

                    // Update attending counter
                    const counterEl = document.getElementById('rsvp-attending-counter');
                    if (counterEl) {
                        const count = rsvpStreamList.querySelectorAll('[data-attendance-status="attending"]').length;
                        counterEl.textContent = `${count} Tamu Hadir`;
                    }

                    if (rsvpPagination) {
                        rsvpPagination.refresh(true);
                    }
                }
            } else {
                const errorMsg = result.message || (result.errors ? Object.values(result.errors)[0][0] : 'Gagal menyimpan konfirmasi.');
                showToast(errorMsg, 'error');
            }
        } catch (error) {
            showToast('Terjadi gangguan jaringan. Silakan coba lagi.', 'error');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        }
    });
}

/**
 * 13. Asynchronous AJAX Guestbook Submission
 */
function initGuestbookForm() {
    const form = document.getElementById('guestbook-form');
    if (!form) return;

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnHtml = submitBtn ? submitBtn.innerHTML : 'Kirim Doa Restu';
    const messageInput = form.querySelector('textarea[name="message"]');
    const streamContainer = document.getElementById('messages-stream-list');
    const emptyNotice = document.getElementById('empty-messages-notice');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        const actionUrl = form.getAttribute('action');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Mengirim Doa...</span>
            `;
        }

        try {
            const response = await fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const result = await response.json();

            if (response.ok && result.success) {
                showToast(result.message || 'Doa restu Anda berhasil terkirim!');

                if (messageInput) messageInput.value = '';

                if (emptyNotice) emptyNotice.classList.add('hidden');

                if (streamContainer && result.data) {
                    const newCard = document.createElement('div');
                    newCard.className = 'p-4 rounded-2xl bg-white/90 border border-[#6e1c27]/20 shadow-xs mb-3 transform transition-all duration-300';
                    newCard.innerHTML = `
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-[#6e1c27]/10 text-[#6e1c27] font-bold text-xs flex items-center justify-center font-playfair">
                                    ${escapeHtml(result.data.guest_name.charAt(0).toUpperCase())}
                                </div>
                                <span class="font-bold text-xs sm:text-sm text-stone-800">${escapeHtml(result.data.guest_name)}</span>
                            </div>
                            <span class="text-[10px] text-stone-400 font-medium">Baru saja</span>
                        </div>
                        <p class="text-xs text-stone-600 leading-relaxed pl-10.5">${escapeHtml(result.data.message)}</p>
                    `;
                    streamContainer.prepend(newCard);

                    if (guestbookPagination) {
                        guestbookPagination.refresh(true);
                    }
                }
            } else {
                const errorMsg = result.message || (result.errors ? Object.values(result.errors)[0][0] : 'Gagal mengirim ucapan.');
                showToast(errorMsg, 'error');
            }
        } catch (error) {
            showToast('Terjadi gangguan jaringan saat mengirim doa.', 'error');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        }
    });
}

/**
 * 14. Toast Feedback Notification
 */
function showToast(message, type = 'success') {
    let container = document.getElementById('fe-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'fe-toast-container';
        container.className = 'fixed bottom-24 right-5 z-50 flex flex-col gap-2 max-w-sm pointer-events-none';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const isError = type === 'error';
    const bgClass = isError ? 'bg-rose-50 border-rose-300 text-rose-800' : 'bg-[#fdf0e6] border-[#6e1c27]/40 text-[#6e1c27]';
    const iconColor = isError ? 'text-rose-500' : 'text-[#6e1c27]';

    toast.className = `p-4 rounded-2xl border ${bgClass} shadow-xl text-xs font-semibold flex items-center gap-3 pointer-events-auto fe-toast-show backdrop-blur-md`;
    toast.innerHTML = `
        <svg class="w-4 h-4 shrink-0 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            ${isError 
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            }
        </svg>
        <span>${escapeHtml(message)}</span>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

function escapeHtml(string) {
    if (!string) return '';
    return String(string)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

/**
 * 15. Realistic Botanical Flowers Interaction Engine
 */
function initInteractiveFlowers() {
    const flowers = document.querySelectorAll('.fe-realistic-floral-corner');
    if (!flowers.length) return;

    // Palette for floating petals spawned on touch/click
    const petalColors = [
        'linear-gradient(135deg, #7a1f2b, #5a111a)', // Deep Wine
        'linear-gradient(135deg, #992f3c, #7a1f2b)', // Crimson Rose
        'linear-gradient(135deg, #d9a5a0, #ba4856)', // Blush Peony
        'linear-gradient(135deg, #f4d6cd, #ecc480)', // Soft Cream Rose
        'linear-gradient(135deg, #e8d5be, #b9843a)', // Champagne Gold
        'linear-gradient(135deg, #7a926c, #556948)', // Sage Leaf
    ];

    // A. Tap / Click interaction with bouncing spring and petal burst
    flowers.forEach((flower) => {
        flower.addEventListener('click', (e) => {
            triggerFlowerTap(flower, e);
        });

        // Touch support for mobile devices
        flower.addEventListener(
            'touchstart',
            (e) => {
                if (e.touches && e.touches[0]) {
                    const touch = e.touches[0];
                    triggerFlowerTap(flower, {
                        clientX: touch.clientX,
                        clientY: touch.clientY,
                    });
                }
            },
            { passive: true }
        );
    });

    function triggerFlowerTap(flower, event) {
        // Trigger bounce animation
        flower.classList.remove('fe-flower-tapped');
        void flower.offsetWidth; // Force Reflow
        flower.classList.add('fe-flower-tapped');

        // Get origin coordinates
        const rect = flower.getBoundingClientRect();
        const originX = event?.clientX || rect.left + rect.width / 2;
        const originY = event?.clientY || rect.top + rect.height / 2;

        // Spawn a gentle burst of 6 to 9 floating petals
        const petalCount = 7 + Math.floor(Math.random() * 3);
        for (let i = 0; i < petalCount; i++) {
            createBurstPetal(originX, originY);
        }
    }

    function createBurstPetal(x, y) {
        const petal = document.createElement('div');
        petal.className = 'fe-burst-petal';

        const size = Math.random() * 8 + 10; // 10px to 18px
        const color = petalColors[Math.floor(Math.random() * petalColors.length)];

        petal.style.width = `${size}px`;
        petal.style.height = `${size * 1.35}px`;
        petal.style.background = color;
        petal.style.left = `${x}px`;
        petal.style.top = `${y}px`;
        petal.style.opacity = '1';

        document.body.appendChild(petal);

        // Physics parameters
        const angle = Math.random() * Math.PI * 2;
        const initialSpeed = Math.random() * 3.5 + 2;
        let vx = Math.cos(angle) * initialSpeed;
        let vy = Math.sin(angle) * initialSpeed - 2.5; // Initial upward bounce
        const gravity = 0.12;
        const drag = 0.98;
        let currentX = x;
        let currentY = y;
        let rot = Math.random() * 360;
        const rotSpeed = (Math.random() - 0.5) * 8;
        let opacity = 1;
        const decay = Math.random() * 0.015 + 0.012;

        function animatePetal() {
            currentX += vx;
            currentY += vy;
            vy += gravity;
            vx *= drag;
            vy *= drag;
            rot += rotSpeed;
            opacity -= decay;

            petal.style.transform = `translate(${currentX - x}px, ${currentY - y}px) rotate(${rot}deg) scale(${Math.max(0.2, opacity)})`;
            petal.style.opacity = `${Math.max(0, opacity)}`;

            if (opacity > 0 && currentY < window.innerHeight + 100) {
                requestAnimationFrame(animatePetal);
            } else {
                petal.remove();
            }
        }

        requestAnimationFrame(animatePetal);
    }

    // B. Scroll Entrance Bloom Unfurl (IntersectionObserver)
    const cards = document.querySelectorAll('.fe-page-card');
    cards.forEach((card) => {
        const cardFlowers = card.querySelectorAll('.fe-realistic-floral-corner');
        cardFlowers.forEach((f) => f.classList.add('fe-floral-unfurl'));
    });

    const flowerObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const cardFlowers = entry.target.querySelectorAll('.fe-realistic-floral-corner');
                    cardFlowers.forEach((f, idx) => {
                        setTimeout(() => {
                            f.classList.add('fe-floral-unfurled');
                        }, idx * 120);
                    });
                }
            });
        },
        {
            threshold: 0.12,
        }
    );

    cards.forEach((card) => flowerObserver.observe(card));

    // C. Subtle Card Mouse Move Parallax on Desktop
    if (window.innerWidth >= 900) {
        cards.forEach((card) => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width - 0.5;
                const y = (e.clientY - rect.top) / rect.height - 0.5;
                const flowers = card.querySelectorAll('.fe-realistic-floral-corner');
                flowers.forEach((f) => {
                    f.style.transform = `translate(${x * -6}px, ${y * -6}px)`;
                });
            });

            card.addEventListener('mouseleave', () => {
                const flowers = card.querySelectorAll('.fe-realistic-floral-corner');
                flowers.forEach((f) => {
                    f.style.transform = '';
                });
            });
        });
    }
}

/**
 * 15. Modular Stream & List Client Pagination
 */
function setupStreamPagination(listId, paginationId, pageSize = 3) {
    const listEl = document.getElementById(listId);
    const pagEl = document.getElementById(paginationId);
    if (!listEl || !pagEl) return null;

    const prevBtn = pagEl.querySelector('[data-page-action="prev"]');
    const nextBtn = pagEl.querySelector('[data-page-action="next"]');
    const infoEl = pagEl.querySelector('[data-page-info]');

    let currentPage = 1;

    function render() {
        const items = Array.from(listEl.children).filter((child) => {
            return !child.id || !child.id.includes('empty');
        });

        const totalItems = items.length;
        const totalPages = Math.max(1, Math.ceil(totalItems / pageSize));

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        if (totalItems <= pageSize) {
            pagEl.classList.add('hidden');
            pagEl.classList.remove('flex');
            items.forEach((item) => item.classList.remove('hidden'));
            return;
        }

        pagEl.classList.remove('hidden');
        pagEl.classList.add('flex');

        const startIndex = (currentPage - 1) * pageSize;
        const endIndex = startIndex + pageSize;

        items.forEach((item, index) => {
            if (index >= startIndex && index < endIndex) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });

        if (infoEl) {
            infoEl.innerHTML = `Halaman <strong class="text-stone-800 font-bold">${currentPage}</strong> dari <strong class="text-stone-800 font-bold">${totalPages}</strong>`;
        }

        if (prevBtn) {
            prevBtn.disabled = (currentPage === 1);
        }

        if (nextBtn) {
            nextBtn.disabled = (currentPage === totalPages);
        }
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                render();
                listEl.scrollTop = 0;
            }
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            const items = Array.from(listEl.children).filter((child) => !child.id || !child.id.includes('empty'));
            const totalPages = Math.ceil(items.length / pageSize);
            if (currentPage < totalPages) {
                currentPage++;
                render();
                listEl.scrollTop = 0;
            }
        });
    }

    render();

    return {
        refresh(resetToFirst = false) {
            if (resetToFirst) currentPage = 1;
            render();
        },
        goToPage(page) {
            currentPage = page;
            render();
        }
    };
}

function initStreamPaginations() {
    // 3 items per page for RSVP & Guestbook, 4 items for photo gallery
    rsvpPagination = setupStreamPagination('rsvp-stream-list', 'rsvp-pagination', 3);
    guestbookPagination = setupStreamPagination('messages-stream-list', 'guestbook-pagination', 3);
    galleryPagination = setupStreamPagination('gallery-grid', 'gallery-pagination', 4);
}

