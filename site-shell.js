(() => {
    function setupNavigation() {
        const navToggle = document.querySelector('.nav-toggle');
        const nav = document.querySelector('.site-nav');

        if (navToggle && nav) {
            navToggle.addEventListener('click', () => {
                const isOpen = nav.classList.toggle('is-open');
                navToggle.setAttribute('aria-expanded', String(isOpen));
            });
        }

        document.querySelectorAll('.submenu-toggle').forEach((toggle) => {
            toggle.setAttribute('aria-expanded', 'false');
            toggle.addEventListener('click', () => {
                const parent = toggle.closest('.has-submenu');
                if (!parent) {
                    return;
                }
                const isOpen = parent.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', String(isOpen));
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupNavigation);
    } else {
        setupNavigation();
    }
})();
