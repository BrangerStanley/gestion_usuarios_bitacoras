document.addEventListener('submit', function (event) {
    const form = event.target;
    if (form instanceof HTMLFormElement && form.dataset.confirm) {
        if (!window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const menuButton = document.getElementById('menuToggle');
    const closeButton = document.getElementById('sidebarClose');
    const backdrop = document.getElementById('sidebarBackdrop');
    const sidebar = document.getElementById('sidebar');
    const equipoTrigger = document.querySelector('[data-equipo-open]');
    const equipoModal = document.getElementById('equipoModal');
    const equipoClose = equipoModal ? equipoModal.querySelector('[data-equipo-close]') : null;
    let equipoLastFocused = null;

    function setMenuOpen(open) {
        if (!menuButton || !sidebar) {
            return;
        }
        document.body.classList.toggle('iaa-sidebar-open', open);
        sidebar.classList.toggle('is-open', open);
        menuButton.setAttribute('aria-expanded', open ? 'true' : 'false');
        menuButton.setAttribute('aria-label', open ? 'Cerrar navegación' : 'Abrir navegación');
    }

    if (menuButton && sidebar && backdrop) {
        menuButton.addEventListener('click', function () {
            setMenuOpen(!sidebar.classList.contains('is-open'));
        });

        if (closeButton) {
            closeButton.addEventListener('click', function () {
                setMenuOpen(false);
            });
        }

        backdrop.addEventListener('click', function () {
            setMenuOpen(false);
        });
    }

    function setEquipoModalOpen(open) {
        if (!equipoModal) {
            return;
        }
        equipoModal.classList.toggle('is-open', open);
        equipoModal.setAttribute('aria-hidden', open ? 'false' : 'true');
        document.body.classList.toggle('equipo-modal-open', open);
        if (open) {
            equipoLastFocused = document.activeElement;
            if (equipoClose) {
                equipoClose.focus();
            }
        } else if (equipoLastFocused instanceof HTMLElement) {
            equipoLastFocused.focus();
            equipoLastFocused = null;
        }
    }

    if (equipoTrigger && equipoModal) {
        equipoTrigger.addEventListener('click', function () {
            setEquipoModalOpen(true);
        });

        equipoModal.addEventListener('click', function (event) {
            if (event.target === equipoModal || (event.target instanceof Element && event.target.closest('[data-equipo-close]'))) {
                setEquipoModalOpen(false);
            }
        });

        equipoModal.addEventListener('keydown', function (event) {
            if (event.key !== 'Tab') {
                return;
            }
            const focusable = equipoModal.querySelectorAll('button:not([disabled]), [href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])');
            if (focusable.length === 0) {
                return;
            }
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            setMenuOpen(false);
            if (equipoModal && equipoModal.classList.contains('is-open')) {
                setEquipoModalOpen(false);
            }
        }
    });
});
