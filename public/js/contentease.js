/**
 * ContentEase — Main JavaScript
 * Muhamad Sahal Nurjamil | XI RPL B
 */

document.addEventListener('DOMContentLoaded', function () {

    // ═══════════════════════════════════
    // SIDEBAR TOGGLE
    // ═══════════════════════════════════
    const sidebar     = document.getElementById('sidebar');
    const mainWrapper = document.getElementById('mainWrapper');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const topbarMenuBtn = document.getElementById('topbarMenuBtn');

    // Desktop: collapse/expand
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('collapsed');
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        });
    }

    // Topbar burger button — buka sidebar di mobile DAN desktop saat collapsed
    if (topbarMenuBtn) {
        topbarMenuBtn.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                // Mobile: toggle drawer
                sidebar.classList.toggle('mobile-open');
            } else {
                // Desktop: expand sidebar jika sedang collapsed
                if (sidebar.classList.contains('collapsed')) {
                    sidebar.classList.remove('collapsed');
                    mainWrapper.classList.remove('collapsed');
                    localStorage.setItem('sidebarCollapsed', 'false');
                } else {
                    sidebar.classList.add('collapsed');
                    mainWrapper.classList.add('collapsed');
                    localStorage.setItem('sidebarCollapsed', 'true');
                }
            }
        });
    }

    // Restore sidebar state dari localStorage
    const savedCollapsed = localStorage.getItem('sidebarCollapsed');
    if (savedCollapsed === 'true' && window.innerWidth > 768) {
        sidebar.classList.add('collapsed');
        mainWrapper.classList.add('collapsed');
    }

    // Tutup sidebar mobile jika klik di luar
    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && !topbarMenuBtn.contains(e.target)) {
                sidebar.classList.remove('mobile-open');
            }
        }
    });


    // ═══════════════════════════════════
    // REALTIME CLOCK (TOPBAR)
    // ═══════════════════════════════════
    const timeEl = document.getElementById('topbarTime');
    if (timeEl) {
        function updateClock() {
            const now = new Date();
            const opts = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            timeEl.textContent = now.toLocaleTimeString('id-ID', opts);
        }
        updateClock();
        setInterval(updateClock, 1000);
    }


    // ═══════════════════════════════════
    // FLASH ALERT — Auto dismiss
    // ═══════════════════════════════════
    const flashAlert = document.getElementById('flashAlert');
    if (flashAlert) {
        setTimeout(() => {
            flashAlert.style.transition = 'opacity 0.4s, transform 0.4s';
            flashAlert.style.opacity = '0';
            flashAlert.style.transform = 'translateY(-6px)';
            setTimeout(() => flashAlert.remove(), 400);
        }, 4000);
    }


    // ═══════════════════════════════════
    // STATUS DROPDOWN (Quick Update)
    // ═══════════════════════════════════
    // Tutup semua dropdown jika klik di luar
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.status-dropdown-wrap')) {
            document.querySelectorAll('.status-dropdown.open').forEach(d => {
                d.classList.remove('open');
                d.previousElementSibling?.classList.remove('open');
            });
        }
    });


    // ═══════════════════════════════════
    // TABLE ROW ANIMATION
    // ═══════════════════════════════════
    const rows = document.querySelectorAll('.table-row');
    rows.forEach((row, i) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(8px)';
        row.style.transition = `opacity 0.3s ${i * 0.04}s ease, transform 0.3s ${i * 0.04}s ease`;
        requestAnimationFrame(() => {
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        });
    });


    // ═══════════════════════════════════
    // FORM — Submit loading state
    // ═══════════════════════════════════
    const contentForm = document.getElementById('contentForm');
    if (contentForm) {
        contentForm.addEventListener('submit', function () {
            const submitBtn = this.querySelector('.btn-submit');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 0.7s linear infinite">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                    Menyimpan...
                `;
            }
        });
    }

});


// ═══════════════════════════════════
// STATUS DROPDOWN TOGGLE (global fn)
// ═══════════════════════════════════
function toggleStatusDropdown(btn) {
    const wrap = btn.closest('.status-dropdown-wrap');
    const dropdown = wrap.querySelector('.status-dropdown');
    const isOpen = dropdown.classList.contains('open');

    // Tutup semua dulu
    document.querySelectorAll('.status-dropdown.open').forEach(d => {
        d.classList.remove('open');
        d.previousElementSibling?.classList.remove('open');
    });

    // Toggle yang diklik
    if (!isOpen) {
        dropdown.classList.add('open');
        btn.classList.add('open');
    }
}


// ═══════════════════════════════════
// DELETE MODAL
// ═══════════════════════════════════
function openDeleteModal(id, judul) {
    const modal    = document.getElementById('deleteModal');
    const titleEl  = document.getElementById('deleteItemTitle');
    const form     = document.getElementById('deleteForm');

    titleEl.textContent = judul;
    form.action = `/konten/${id}`;

    modal.classList.add('open');

    // Close on overlay click
    modal.addEventListener('click', function handler(e) {
        if (e.target === modal) {
            closeDeleteModal();
            modal.removeEventListener('click', handler);
        }
    });
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('open');
}

// Close modal on ESC key
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeDeleteModal();
});


// CSS spin animation (inject once)
const style = document.createElement('style');
style.textContent = `@keyframes spin { to { transform: rotate(360deg); } }`;
document.head.appendChild(style);