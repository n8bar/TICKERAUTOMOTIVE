window._currentDevice = 'desktop';
window.Parameters = window.Parameters || {
    HomeUrl: 'https://ticker.cybercreek.us//',
    AccountUUID: 'd6db2509c30e4bc491a749f9d847b091',
    SystemID: 'US_DIRECT_PRODUCTION',
    SiteAlias: 'fd5deb14',
    SiteType: atob('RFVEQU9ORQ=='),
    PublicationDate: 'Tue Aug 06 22:43:17 UTC 2024',
    ExternalUid: 'f6d46137-5904-4987-a641-8f89ecbde6ad',
    IsSiteMultilingual: false,
    InitialPostAlias: '',
    InitialDynamicItem: '',
    DynamicPageInfo: {
        isDynamicPage: false,
        base64JsonRowData: 'null',
    },
    InitialPageAlias: 'home',
    InitialPageUuid: '5d5b50be94b849d797043eb59c1dedee',
    InitialPageId: '1157489650',
    InitialEncodedPageAlias: 'aG9tZQ==',
    InitialHeaderUuid: 'a1cbd265e78a471ebea519d81d517fc9',
    CurrentPageUrl: '',
    IsCurrentHomePage: true,
    AllowAjax: false,
    AfterAjaxCommand: null,
    HomeLinkText: 'Back To Home',
    UseGalleryModule: false,
    CurrentThemeName: 'Layout Theme',
    ThemeVersion: '46510',
    DefaultPageAlias: '',
    RemoveDID: true,
    WidgetStyleID: null,
    IsHeaderFixed: false,
    IsHeaderSkinny: false,
    IsBfs: true,
    StorePageAlias: 'null',
    StorePagesUrls: 'e30=',
    IsNewStore: 'false',
    StorePath: '',
    StoreId: 'null',
    StoreVersion: 0,
    StoreBaseUrl: '',
    StoreCleanUrl: true,
    StoreDisableScrolling: true,
    IsStoreSuspended: false,
    HasCustomDomain: true,
    SimpleSite: false,
    showCookieNotification: false,
    cookiesNotificationMarkup: 'null',
    translatedPageUrl: '',
    isFastMigrationSite: false,
    sidebarPosition: 'NA',
    currentLanguage: 'en',
    currentLocale: 'en',
    NavItems: '{}',
    errors: {
        general: 'There was an error connecting to the page.<br/> Make sure you are not offline.',
        password: 'Incorrect name/password combination',
        tryAgain: 'Try again'
    },
    NavigationAreaParams: {
        ShowBackToHomeOnInnerPages: true,
        NavbarSize: -1,
        NavbarLiveHomePage: 'http://ticker.cybercreek.us//',
        BlockContainerSelector: '.dmBody',
        NavbarSelector: '#dmNav:has(a)',
        SubNavbarSelector: '#subnav_main'
    },
    hasCustomCode: true,
    planID: '7',
    customTemplateId: 'null',
    siteTemplateId: 'null',
    productId: 'DM_DIRECT',
    disableTracking: false,
    pageType: 'FROM_SCRATCH',
    isRuntimeServer: true,
    isInEditor: false,
    hasNativeStore: false,
    defaultLang: 'en',
    hamburgerMigration: null
};

window.Parameters.LayoutID = {};
window.Parameters.LayoutID[window._currentDevice] = 6;
window.Parameters.LayoutVariationID = {};
window.Parameters.LayoutVariationID[window._currentDevice] = 5;

function toHash(str) {
    var hash = 5381, i = str.length;
    while (i) {
        hash = hash * 33 ^ str.charCodeAt(--i);
    }
    return hash >>> 0;
}

(function (global) {
    //const cacheKey = global.cacheKey;
    const isOffline = 'onLine' in navigator && navigator.onLine === false;
    const hasServiceWorkerSupport = 'serviceWorker' in navigator;
    if (isOffline) {
        console.log('offline mode');
    }
    if (!hasServiceWorkerSupport) {
        console.log('service worker is not supported');
    }
    if (hasServiceWorkerSupport && !isOffline) {
        window.addEventListener('load', function () {
            const serviceWorkerPath = 'f30f4.txt?v=3';
            navigator.serviceWorker
                .register(serviceWorkerPath, { scope: './' })
                .then(
                    function (registration) {
                        // Registration was successful
                        console.log(
                            'ServiceWorker registration successful with scope: ',
                            registration.scope
                        );
                    },
                    function (err) {
                        // registration failed :(
                        console.log('ServiceWorker registration failed: ', err);
                    }
                )
                .catch(function (err) {
                    console.log(err);
                });
        });

        // helper function to refresh the page
        var refreshPage = (function () {
            var refreshing;
            return function () {
                if (refreshing) return;
                // prevent multiple refreshes
                var refreshkey = 'refreshed' + location.href;
                var prevRefresh = localStorage.getItem(refreshkey);
                if (prevRefresh) {
                    localStorage.removeItem(refreshkey);
                    if (Date.now() - prevRefresh < 30000) {
                        return; // dont go into a refresh loop
                    }
                }
                refreshing = true;
                localStorage.setItem(refreshkey, Date.now());
                console.log('refereshing page');
                window.location.reload();
            };
        })();

        function messageServiceWorker(data) {
            return new Promise(function (resolve, reject) {
                if (navigator.serviceWorker.controller) {
                    var worker = navigator.serviceWorker.controller;
                    var messageChannel = new MessageChannel();
                    messageChannel.port1.onmessage = replyHandler;
                    worker.postMessage(data, [messageChannel.port2]);
                    function replyHandler(event) {
                        resolve(event.data);
                    }
                } else {
                    resolve();
                }
            });
        }
    }
})(window);

window.SystemID = 'US_DIRECT_PRODUCTION';

if (!window.dmAPI) {
    window.dmAPI = {
        registerExternalRuntimeComponent: function () {
        },
        getCurrentDeviceType: function () {
            return window._currentDevice;
        },
        runOnReady: (ns, fn) => {
            const safeFn = dmAPI.toSafeFn(fn);
            ns = ns || 'global_' + Math.random().toString(36).slice(2, 11);
            const eventName = 'afterAjax.' + ns;

            if (document.readyState === 'complete') {
                $.DM.events.off(eventName).on(eventName, safeFn);
                setTimeout(function () {
                    safeFn({
                        isAjax: false,
                    });
                }, 0);
            } else {
                window?.waitForDeferred?.('dmAjax', () => {
                    $.DM.events.off(eventName).on(eventName, safeFn);
                    safeFn({
                        isAjax: false,
                    });
                });
            }
        },
        toSafeFn: (fn) => {
            if (fn?.safe) {
                return fn;
            }
            const safeFn = function (...args) {
                try {
                    return fn?.apply(null, args);
                } catch (e) {
                    console.log('function failed ' + e.message);
                }
            };
            safeFn.safe = true;
            return safeFn;
        }
    };
}

if (!window.requestIdleCallback) {
    window.requestIdleCallback = function (fn) {
        setTimeout(fn, 0);
    };
}

/**
 * There are a few <link> tags with CSS resource in them that are preloaded in the page
 * in each of those there is a "onload" handler which invokes the loadCSS callback
 * defined here.
 * We are monitoring 3 main CSS files - the runtime, the global and the page.
 * When each load we check to see if we can append them all in a batch. If threre
 * is no page css (which may happen on inner pages) then we do not wait for it
 */
(function () {
    let cssLinks = {};
    function loadCssLink(link) {
        link.onload = null;
        link.rel = "stylesheet";
        link.type = "text/css";
    }

    function checkCss() {
        const pageCssLink = document.querySelector("[id*='CssLink']");
        const widgetCssLink = document.querySelector("[id*='widgetCSS']");

        if (cssLinks && cssLinks.runtime && cssLinks.global && (!pageCssLink || cssLinks.page) && (!widgetCssLink || cssLinks.widget)) {
            const storedRuntimeCssLink = cssLinks.runtime;
            const storedPageCssLink = cssLinks.page;
            const storedGlobalCssLink = cssLinks.global;
            const storedWidgetCssLink = cssLinks.widget;

            storedGlobalCssLink.disabled = true;
            loadCssLink(storedGlobalCssLink);

            if (storedPageCssLink) {
                storedPageCssLink.disabled = true;
                loadCssLink(storedPageCssLink);
            }

            if(storedWidgetCssLink) {
                storedWidgetCssLink.disabled = true;
                loadCssLink(storedWidgetCssLink);
            }

            storedRuntimeCssLink.disabled = true;
            loadCssLink(storedRuntimeCssLink);

            requestAnimationFrame(() => {
                setTimeout(() => {
                    storedRuntimeCssLink.disabled = false;
                    storedGlobalCssLink.disabled = false;
                    if (storedPageCssLink) {
                        storedPageCssLink.disabled = false;
                    }
                    if (storedWidgetCssLink) {
                        storedWidgetCssLink.disabled = false;
                    }
                    // (SUP-4179) Clear the accumulated cssLinks only when we're
                    // sure that the document has finished loading and the document
                    // has been parsed.
                    if(document.readyState === 'interactive') {
                        cssLinks = null;
                    }
                }, 0);
            });
        }
    }

    function loadCSS(link) {
        try {
            var urlParams = new URLSearchParams(window.location.search);
            var noCSS = !!urlParams.get("nocss");
            var cssTimeout = urlParams.get("cssTimeout") || 0;

            if (noCSS) {
                return;
            }
            if (link.href && link.href.includes("d-css-runtime")) {
                cssLinks.runtime = link;
                checkCss();
            } else if (link.id === "siteGlobalCss") {
                cssLinks.global = link;
                checkCss();
            }

            else if (link.id && link.id.includes("CssLink")) {
                cssLinks.page = link;
                checkCss();
            } else if (link.id && link.id.includes("widgetCSS")) {
                cssLinks.widget = link;
                checkCss();
            }

            else {
                requestIdleCallback(function () {
                    window.setTimeout(function () {
                        loadCssLink(link);
                    }, parseInt(cssTimeout, 10));
                });
            }
        } catch (e) {
            throw e;
        }
    }
    window.loadCSS = loadCSS;
})();

/* usage: window.getDeferred(<deferred name>).resolve() or window.getDeferred(<deferred name>).promise.then(...)*/
function Def() {
    this.promise = new Promise((function (a, b) {
        this.resolve = a, this.reject = b;
    }).bind(this));
}

const defs = {};
window.getDeferred = function (a) {
    return null == defs[a] && (defs[a] = new Def), defs[a];
};
window.waitForDeferred = function (b, a, c) {
    let d = window?.getDeferred?.(b);
    d
        ? d.promise.then(a)
        : c && ["complete", "interactive"].includes(document.readyState)
            ? setTimeout(a, 1)
            : c
                ? document.addEventListener("DOMContentLoaded", a)
                : console.error(`Deferred  does not exist`);
};

var isWLR = true;

window.customWidgetsFunctions = {};
window.customWidgetsStrings = {};
window.collections = {};
window.currentLanguage = "ENGLISH";
window.isSitePreview = false;

var d_version = "production_5014";
var build = "2024-12-15T11_48_38";
window['v' + 'ersion'] = d_version;

function buildEditorParent() {
    window.isMultiScreen = true;
    window.editorParent = {};
    window.previewParent = {};
    window.assetsCacheQueryParam = "?version=2024-12-15T11_48_38";
    try {
        var _p = window.parent;
        if (_p && _p.document && _p.$ && _p.$.dmfw) {
            window.editorParent = _p;
        } else if (_p.isSitePreview) {
            window.previewParent = _p;
        }
    } catch (e) {

    }
}

buildEditorParent();
