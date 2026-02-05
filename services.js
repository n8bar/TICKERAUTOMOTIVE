const CLIENT_ID = 'f6d46137-5904-4987-a641-8f89ecbde6ad';
const SITE_NAME = 'fd5deb14';
const LOCATION_ID = '562b146d-2b79-469a-879b-0583b6e1b5b6';
const ZENOGRE_API = 'https://zapi.kukui.com/api/v1';

function formatTime(value) {
    const [hours, minutes] = value.split(':').map((part) => Number(part));
    const normalizedHours = hours % 12 || 12;
    const suffix = hours < 12 ? 'AM' : 'PM';
    return `${normalizedHours}:${String(minutes).padStart(2, '0')} ${suffix}`;
}

async function fetchJson(url) {
    const response = await fetch(url, {
        headers: {
            'zw-client': CLIENT_ID,
        },
    });

    if (!response.ok) {
        throw new Error(`Request failed: ${response.status}`);
    }

    return response.json();
}

async function loadBusinessHours() {
    const targets = document.querySelectorAll('[data-business-hours]');
    if (!targets.length) {
        return;
    }

    const hasServerValue = Array.from(targets).some((target) => target.textContent.trim().length > 0);
    if (hasServerValue) {
        return;
    }

    try {
        const data = await fetchJson(`${ZENOGRE_API}/websites/${SITE_NAME}/businesshours?getContentLibraryData=false&locationId=${LOCATION_ID}`);
        const hours = (data.businessHours || []).map((entry) => {
            const times = (entry.workTime || []).map((range) => {
                const [start, end] = range.split('-');
                return `${formatTime(start)} - ${formatTime(end)}`;
            });
            return `${entry.days} ${times.join(' and ')}`;
        }).join(' ');

        targets.forEach((target) => {
            target.textContent = hours || 'Call for hours';
        });
    } catch (error) {
        targets.forEach((target) => {
            target.textContent = 'Call for hours';
        });
    }
}

async function loadNapLines() {
    const target = document.querySelector('[data-nap-lines]');
    if (!target) {
        return;
    }

    if (target.textContent.trim().length > 0) {
        return;
    }

    try {
        const data = await fetchJson(`${ZENOGRE_API}/clients/${CLIENT_ID}/nap/${SITE_NAME}`);
        target.textContent = (data.napLines || []).join(' • ') || 'Ticker Automotive';
    } catch (error) {
        target.textContent = 'Ticker Automotive';
    }
}

function setupAnalytics() {
    window.dataLayer = window.dataLayer || [];
    function gtag() {
        window.dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'G-ME7X83EBJH');
    gtag('config', 'AW-10784944369');
    gtag('event', 'conversion', { 'send_to': 'AW-10784944369/FmqFCPuKjvkCEPHh1JYo' });
}

function setupServiceDirectory() {
    const items = window.serviceItems;
    if (!Array.isArray(items) || items.length === 0) {
        return;
    }

    const buttons = Array.from(document.querySelectorAll('.service-link[data-service-index]'));
    const detailCard = document.querySelector('.service-detail-card');
    if (!buttons.length || !detailCard) {
        return;
    }

    const title = detailCard.querySelector('.service-detail-title');
    const copy = detailCard.querySelector('.service-detail-copy');
    const media = detailCard.querySelector('.service-detail-media');
    const image = media ? media.querySelector('img') : null;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const ensureDetailVisible = () => {
        const padding = 16;
        const rect = detailCard.getBoundingClientRect();
        const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
        const bottomLimit = viewportHeight - padding;
        if (rect.bottom > bottomLimit) {
            const delta = rect.bottom - bottomLimit;
            window.scrollBy({
                top: delta,
                behavior: prefersReducedMotion.matches ? 'auto' : 'smooth',
            });
        }
    };

    const scheduleEnsureVisible = () => {
        requestAnimationFrame(() => {
            ensureDetailVisible();
            setTimeout(ensureDetailVisible, 160);
        });
    };

    const updateUrl = (slug) => {
        const url = new URL(window.location.href);
        if (slug) {
            url.searchParams.set('service', slug);
        } else {
            url.searchParams.delete('service');
        }
        history.replaceState({}, '', url.toString());
    };

    const setActive = (index) => {
        const item = items[index];
        if (!item) {
            return;
        }

        detailCard.hidden = false;
        buttons.forEach((button) => {
            const isActive = Number(button.dataset.serviceIndex) === index;
            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-pressed', String(isActive));
        });

        if (title) {
            title.textContent = item.title || '';
        }
        if (copy) {
            copy.textContent = item.content || '';
        }

        if (media && image && item.image) {
            image.src = item.image;
            image.alt = `${item.title} service`;
            media.hidden = false;
            detailCard.classList.add('has-image');
            if (!image.complete) {
                image.addEventListener('load', scheduleEnsureVisible, { once: true });
            }
        } else if (media && image) {
            image.removeAttribute('src');
            image.alt = '';
            media.hidden = true;
            detailCard.classList.remove('has-image');
        }

        updateUrl(item.slug || '');
        scheduleEnsureVisible();
    };

    const clearActive = () => {
        buttons.forEach((button) => {
            button.classList.remove('is-active');
            button.setAttribute('aria-pressed', 'false');
        });
        if (title) {
            title.textContent = '';
        }
        if (copy) {
            copy.textContent = '';
        }
        if (media && image) {
            image.removeAttribute('src');
            image.alt = '';
            media.hidden = true;
        }
        detailCard.classList.remove('has-image');
        detailCard.hidden = true;
        updateUrl('');
    };

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const index = Number(button.dataset.serviceIndex);
            const isActive = button.classList.contains('is-active');
            if (isActive) {
                clearActive();
                return;
            }
            setActive(index);
        });
    });

    const params = new URLSearchParams(window.location.search);
    const rawSlug = params.get('service');
    if (rawSlug) {
        const slug = rawSlug.trim().toLowerCase();
        const aliasMap = {
            'wheel-alignment': 'alignments',
            'automotive-ac-service-and-repair': 'air-conditioning',
        };
        const targetSlug = aliasMap[slug] || slug;
        const targetIndex = items.findIndex((item) => (item.slug || '').toLowerCase() === targetSlug);
        if (targetIndex !== -1) {
            setActive(targetIndex);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    setupAnalytics();
    setupServiceDirectory();
});
