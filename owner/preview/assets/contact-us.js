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

document.addEventListener('DOMContentLoaded', () => {
    setupAnalytics();
});
