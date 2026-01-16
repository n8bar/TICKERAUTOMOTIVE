window._currentDevice = 'desktop';
window.Parameters = window.Parameters || {
    HomeUrl: 'https://www.tickerautomotive.com/',
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
    InitialPageAlias: 'image-credits',
    InitialPageUuid: 'f6b275e66c184676ab9d388097b6db94',
    InitialPageId: '1157175303',
    InitialEncodedPageAlias: 'aW1hZ2UtY3JlZGl0cw==',
    InitialHeaderUuid: 'a1cbd265e78a471ebea519d81d517fc9',
    CurrentPageUrl: '',
    IsCurrentHomePage: false,
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
    NotificationSubDomain: 'tickerautomotive',
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
        NavbarLiveHomePage: 'https://www.tickerautomotive.com/',
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
    pageType: 'POPUP',
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
        hash = hash * 33 ^ str.charCodeAt(--i)
    }
    return hash >>> 0
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
    }
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
      throw e
    }
  }
  window.loadCSS = window.loadCSS || loadCSS;
})();

/* usage: window.getDeferred(<deferred name>).resolve() or window.getDeferred(<deferred name>).promise.then(...)*/
function Def() {
    this.promise = new Promise((function (a, b) {
        this.resolve = a, this.reject = b
    }).bind(this))
}

const defs = {};
window.getDeferred = function (a) {
    return null == defs[a] && (defs[a] = new Def), defs[a]
}
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
window.currentLanguage = "ENGLISH"
window.isSitePreview = false;

window.customWidgetsFunctions["90104a7c3be44924808b74dbea54ca2f~3"] = function (element, data, api) {
    null
};

    window.customWidgetsFunctions["03922a9dafa746a8a3090e9ef4cb7540~24"] = function (element, data, api) {
        let zenogreApiBaseUrl = 'https://zapi.kukui.com/api/v1';

class ZenogreReviewsSummaryWidget {
    constructor(options = {}) {

        var self = this;

        self.settings = {};

        //Controls
        self.root = $(options.root);
        self.data = data;
        self.reviewsUrlLink = $(self.root).find('[data-zen-element="reviewsUrlLink"]');
        self.averageRating = $(self.root).find('[data-zen-element="averageRating"]');
        self.settings.enableLink = options.enableLink;
        self.settings.reviewsPageUrl = options.reviewsPageUrl;
        self.settings.enableRating = options.enableRating;
        self.settings.enableCustomText = options.enableCustomText;
        self.settings.customText = typeof (options.customText) == "undefined" ? "" : options.customText;
        self.settings.keywordsElementToggle = options.keywordsElementToggle;
        self.keywords = !self.settings.keywordsElementToggle? $(self.root).find('[data-zen-element="keywords"]') : $(self.root).find('[data-zen-element="div-keywords"]');
        self.clientId = dmAPI.getSiteExternalId();

        if(self.clientId == null){
            self.clientId = '00000000-0000-0000-0000-000000000000';
        }

        //GET Reviews average score
        jQuery.ajax({
            method: "GET",
            url: `${zenogreApiBaseUrl}/clients/${self.clientId}/reviews/statistics`,
            headers: {
                'Content-Type': 'application/json',
                'zw-client' : self.clientId,
            },
            success: function (data) {
                if(data.reviewsStatistics.totalCount>1){
                    self.reviewsUrlLink[0].innerHTML = `${data.reviewsStatistics.totalCount} Reviews`;
                }
                else{
                    self.reviewsUrlLink[0].innerHTML = `${data.reviewsStatistics.totalCount} Review`;
                }

                if(self.settings.enableLink)
                {
                    if (self.data.inEditor) {
                        self.reviewsUrlLink[0].href = self.settings.reviewsPageUrl.raw_url;
                    }
                    else
                    {
                        self.reviewsUrlLink[0].href = self.settings.reviewsPageUrl.href;
                    }
                }

                if(self.settings.enableRating){
                    self.averageRating[0].innerHTML = data.reviewsStatistics.averageRating.toFixed(2);
                }
                else{
                    self.averageRating.hide();
                }

                let i;

                for(i = 0; i <= data.reviewsStatistics.averageRating; i ++){
                    $(element).find(`[data-zen-element="star-${i}"]`).addClass('full');
                }

                if(i - data.reviewsStatistics.averageRating <= 0.25) {
                    $(element).find(`[data-zen-element="star-${i}"]`).addClass('full');
                }
                else if(i - data.reviewsStatistics.averageRating <= 0.75) {
                    $(element).find(`[data-zen-element="star-${i}"]`).addClass('half');
                }

                if(!self.settings.enableCustomText){
                    //GET Client name
                    jQuery.ajax({
                        method: "GET",
                        url: `${zenogreApiBaseUrl}/clients/${self.clientId}`,
                        headers: {
                            'Content-Type': 'application/json',
                            'zw-client' : self.clientId,
                        },
                        success: function (data) {
                            self.root.show();
                            self.keywords[0].innerHTML = `${data.name}`;
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            console.log("Error occured while getting the data.");
                        }
                    });
                }
                else{
                    self.root.show();
                    self.keywords[0].innerHTML = `${self.settings.customText}`;
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log("Error occured while getting the data.");
            }
        });
    }
}

new ZenogreReviewsSummaryWidget({
root: $(element).find('[data-zen-component="summary"]')[0],
enableLink: data.config.enableReviewsLink,
reviewsPageUrl: data.config.reviewsPageUrl_input,
enableRating : data.config.enableAverageRating,
enableCustomText : data.config.enableCustomText,
customText : data.config.customText,
keywordsElementToggle : data.config.keywordsElementToggle
});
    };

    window.customWidgetsFunctions["d3c1360ae6994cdca7b4017895fd2292~12"] = function (element, data, api) {
        let zenogreApiBaseUrl = 'https://zapi.kukui.com/api/v1';
let clientId = dmAPI.getSiteExternalId();

if(clientId == null){
    clientId = '00000000-0000-0000-0000-000000000000';
}

class ZenogreBusinessHoursWidget {
    constructor(options = {}) {

        var self = this;

        // Internal settings mapped from options object
        self.settings = {};

        // Component internal data state
        self.data = {};

        //Controls
        self.root = $(options.root);
        self.clientId = clientId; //'e68e4c8f-37f4-40a4-aa1f-71d1970e99c0';
        self.locationId = options.locationOptionsDropdown;
        self.siteName = dmAPI.getSiteName(); //'d59dc11f8c154390b550e373c5971b70';
        self.message = $(options.message);
        self.noDataMessage = $(options.noDataMessage);
        self.toggle12hFormat = options.toggle12hFormat;
        self.ddWorktimeSeparator = options.ddWorktimeSeparator;
        self.getContentLibraryData = options.getContentLibraryData;

        if(typeof(self.locationId) === 'undefined')
        {
            self.locationId = '00000000-0000-0000-0000-000000000000';
        }

        //GET Business hours
        jQuery.ajax({
            method: "GET",
            url: `${zenogreApiBaseUrl}/websites/${self.siteName}/businesshours?getContentLibraryData=${self.getContentLibraryData}&locationId=${self.locationId}`,
            headers: {"zw-client" : self.clientId},
            success: function (data) {
                self.displayBusinessHours(data.businessHours);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log("Error occurred while getting the data.");
                self.noDataMessage.show();
            },
            complete: function(data){
                self.message.hide();
            }
        });
    }

    // Methods
    displayBusinessHours(businessHours, divElement)
    {
        let self = this;
        let text;

        $(businessHours).each(function(index, el){
            if(index == 0)
            {
               text = `<div><span>${el.days}: </span><span>${self.displayWorktime(el.workTime)}</span></div>`
            }
            else
            {
                text += `<span>${self.ddWorktimeSeparator}</span><div><span>${el.days}: </span><span>${self.displayWorktime(el.workTime)}</span></div>`
            }

        });

        self.root.html(text);
    }

    displayWorktime(worktimes)
    {
        let self = this;
        let text;

        $(worktimes).each(function(index, el){

            let startTime = self.toggle12hFormat ? self.converWorktimeTo24HFormat(el.split('-')[0]) : el.split('-')[0];
            let endTime =  self.toggle12hFormat ? self.converWorktimeTo24HFormat(el.split('-')[1]) : el.split('-')[1];

            if(index == 0){
                text = `${startTime}-${endTime}`
            }
            else
            {
                text += ` and ${startTime}-${endTime}`
            }
        });

        return text;
    }

    converWorktimeTo24HFormat(worktime)
    {
        var H = +worktime.substr(0, 2);
        var h = (H % 12) || 12;
        var ampm = H < 12 ? "AM" : "PM";

        worktime = (h < 10 ? '0' + h : h) + worktime.substr(2, 3) + ampm;

        return worktime;
    }

}

new ZenogreBusinessHoursWidget({ root: $(element).find('[data-zen-component="business-hours"]')[0],
                                  message: $(element).find('[data-zen-element="message"]')[0],
                                  noDataMessage: $(element).find('[data-zen-element="no-data-message"]')[0],
                                  toggle12hFormat: data.config.toggle12hFormat,
                                  ddWorktimeSeparator: data.config.ddWorktimeSeparator,
                                  getContentLibraryData: data.config.getContentLibraryData,
                                  locationOptionsDropdown: data.config.locationOptionsDropdown });
    };

    window.customWidgetsFunctions["c1add28715d4417b84d409ac057e793b~10"] = function (element, data, api) {
        let zenogreBaseUrl = 'https://zapi.kukui.com/';
var zenogreApiBaseUrl =`${zenogreBaseUrl}/api/v1`;
var myGarageBaseUrl = 'https://mygarage.kukui.com/';
var clientId = dmAPI.getSiteExternalId();

if(clientId == null){
    clientId = '00000000-0000-0000-0000-000000000000';
  //  clientId = '4c62df68-c470-4f97-b44b-11a1e2300314'; // Cardinal Plaza Shell
   // clientId = '99999999-9999-9999-9999-999999999999'
    //clientId = 'c284a66e-82ea-43ba-990b-0facc378c12b'; //TODO sand
  //  clientId = '5d124dc9-eb37-4c0c-94a6-96a3a67f4a7f'; //TODO production Tihomir Zenongre Client
    //clientId = 'f34076d5-1c4a-480a-b95a-6363da5abea0'; //single
  //  clientId = 'eadaff4e-8a68-4e0a-ac96-934159e15f31'; //TODO mock server
}

class ZenogreMyGarageWidget{
    constructor(options = {}){
        let self = this;
        self.settings = {};

        self.data = options.data;
        self.element = options.element;
        self.api = options.api;
        self.root = options.root;

        self.Init();
    }

    Init(){
        let self = this;

        let myGarageClientId

        jQuery.ajax({
            method: "GET",
            url: `${zenogreApiBaseUrl}/mygarage/${clientId}/clientId`,
            headers: {
                        'Content-Type': 'application/json',
                        'zw-client' : clientId,
                    },
            success: function(dataPayload, status, xhr) {
                if(!document.getElementById('myGarageLoader')){
                    myGarageClientId = dataPayload.myGarageId;

                    let widgetBody=`<script type='text/javascript' id="myGarageLoader" src="${myGarageBaseUrl}/MyGarageLoader.js?id=${myGarageClientId}" defer /><//script>`;

                    $(element).find('[data-zen-component="myGarage"]').prepend(widgetBody);
                }
                $(self.root).show()
            },
            error: function (xhr, ajaxOptions, thrownError) {

                $(self.element).find('[data-zen-component="loading-failed"]').show();
                $(self.element).find('[data-zen-component="loading-failed"]').addClass('loading-failed');
                if(self.data.inEditor){
                    $(self.element).find('[data-zen-component="no-myGarage"]').show();
                    $(self.element).find('[data-zen-component="no-myGarage"]').addClass("no-mygarage hide-on-prod")
                }
            }
        });
    }
}

let displayMyGarageComponent = new ZenogreMyGarageWidget({
    data: data,
    element: element,
    api: api,
    root: $(element).find('[data-zen-component="myGarage"]')[0],
});

    };

    window.customWidgetsFunctions["79c1d8e211f04821af7a73f9f70bcc91~6"] = function (element, data, api) {
        let zenogreApiBaseUrl = 'https://zapi.kukui.com/api/v1';
let clientId = dmAPI.getSiteExternalId();

if(clientId == null){
    clientId = '00000000-0000-0000-0000-000000000000';
}

class ZenogreNAPLinesWidget {
    constructor(options = {}) {

        let self = this;

        // Internal settings mapped from options object
        self.settings = {};

        // Component internal data state
        self.data = {};

        //Controls
        self.root = $(options.root);
        self.clientId = clientId; //'e68e4c8f-37f4-40a4-aa1f-71d1970e99c0';
        self.siteName = dmAPI.getSiteName(); //'d59dc11f8c154390b550e373c5971b70';
        self.message = $(options.message);
        self.noDataMessage = $(options.noDataMessage);
        self.ddNAPLinesSeparator = options.ddNAPLinesSeparator;

        //GET NAP Lines hours
        jQuery.ajax({
            method: "GET",
            url: `${zenogreApiBaseUrl}/clients/${self.clientId}/nap/${self.siteName}`,
            headers: {"zw-client" : self.clientId},
            success: function (data) {;
                self.displayNAPLines(data.napLines);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log("Error occured while getting the data.");
                self.noDataMessage.show();
            },
            complete: function(data){
                self.message.hide();
            }
        });
    }

     // Methods
    displayNAPLines(napLines)
    {
        let self = this;
        let text;

        $(napLines).each(function(index, el){
            if(index == 0)
            {
               text = `<address class="nap" itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">${el}</address>`;
            }
            else
            {
               text += `<span>${self.ddNAPLinesSeparator}</span><address class="nap" itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">${el}</address>`;
            }

        });

        self.root.html(text);
    }

}

new ZenogreNAPLinesWidget({ root: $(element).find('[data-zen-component="nap-lines"]')[0],
                            message: $(element).find('[data-zen-element="message"]')[0],
                            noDataMessage: $(element).find('[data-zen-element="no-data-message"]')[0],
                            ddNAPLinesSeparator: data.config.ddNAPLinesSeparator});
    };

var d_version = "production_5007";
var build = "2024-12-11T13_48_34";
window['v' + 'ersion'] = d_version;

function buildEditorParent() {
    window.isMultiScreen = true;
    window.editorParent = {};
    window.previewParent = {};
    window.assetsCacheQueryParam = "?version=2024-12-11T13_48_34";
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
