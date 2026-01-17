const CLIENT_ID = 'f6d46137-5904-4987-a641-8f89ecbde6ad';
const SITE_NAME = 'fd5deb14';
const LOCATION_ID = '562b146d-2b79-469a-879b-0583b6e1b5b6';
const ZENOGRE_API = 'https://zapi.kukui.com/api/v1';
const MY_GARAGE_URL = 'https://mygarage.kukui.com/MyGarageLoader.js?id=';

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
        toggle.addEventListener('click', () => {
            const parent = toggle.closest('.has-submenu');
            if (!parent) {
                return;
            }
            parent.classList.toggle('is-open');
        });
    });
}

function setStars(rating) {
    const stars = document.querySelectorAll('[data-review-summary] .star');
    stars.forEach((star, index) => {
        const starNumber = index + 1;
        if (rating >= starNumber - 0.5) {
            star.classList.add('is-filled');
        } else {
            star.classList.remove('is-filled');
        }
    });
}

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

    try {
        const data = await fetchJson(`${ZENOGRE_API}/clients/${CLIENT_ID}/nap/${SITE_NAME}`);
        target.textContent = (data.napLines || []).join(' • ') || 'Ticker Automotive';
    } catch (error) {
        target.textContent = 'Ticker Automotive';
    }
}

async function loadReviewSummary() {
    const ratingEl = document.querySelector('[data-review-rating]');
    if (!ratingEl) {
        return;
    }

    try {
        const data = await fetchJson(`${ZENOGRE_API}/clients/${CLIENT_ID}/reviews/statistics`);
        const stats = data.reviewsStatistics;
        const rating = stats?.averageRating || 0;
        ratingEl.textContent = rating ? rating.toFixed(1) : '0.0';
        setStars(rating);
    } catch (error) {
        ratingEl.textContent = '0.0';
        setStars(0);
    }
}

async function loadMyGarage() {
    try {
        const data = await fetchJson(`${ZENOGRE_API}/mygarage/${CLIENT_ID}/clientId`);
        if (!data.myGarageId) {
            return;
        }

        const loader = document.createElement('script');
        loader.id = 'myGarageLoader';
        loader.src = `${MY_GARAGE_URL}${data.myGarageId}`;
        loader.defer = true;
        const target = document.getElementById('myGarage');
        if (target) {
            target.appendChild(loader);
        } else {
            document.body.appendChild(loader);
        }

        const ctaButton = document.querySelector('[data-mygarage-cta]');
        if (ctaButton && target) {
            ctaButton.addEventListener('click', () => {
                target.click();
            });
        }
    } catch (error) {
        const ctaButton = document.querySelector('[data-mygarage-cta]');
        if (ctaButton) {
            ctaButton.style.display = 'none';
        }
        const topButton = document.getElementById('myGarage');
        if (topButton) {
            topButton.style.display = 'none';
        }
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

document.addEventListener('DOMContentLoaded', () => {
    setupNavigation();
    setupAnalytics();
    loadBusinessHours();
    loadNapLines();
    loadReviewSummary();
    loadMyGarage();
});
