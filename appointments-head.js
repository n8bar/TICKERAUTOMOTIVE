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
    InitialPageAlias: 'appointments',
    InitialPageUuid: '7dd7f68e55294ecb925300d11db4dce7',
    InitialPageId: '1157175310',
    InitialEncodedPageAlias: 'YXBwb2ludG1lbnRz',
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

    window.customWidgetsFunctions["65a5ac665f8841cabd47c3b37c56c523~135"] = function (element, data, api) {
        let zenogreBaseUrl = 'https://zapi.kukui.com/';
var zenogreApiBaseUrl =`${zenogreBaseUrl}/api/v1`;
var clientId = dmAPI.getSiteExternalId();

if(clientId == null){
    clientId = '00000000-0000-0000-0000-000000000000';
}

let siteId = data.siteId;
let zenogreTermsAndConditionsUrl = `${zenogreBaseUrl}/website/termsandconditions/${clientId}`;
let zenogrePrivacyPageUrl = `${zenogreBaseUrl}/website/privacy/${clientId}`;

let widgetSettings;
let settingsPanel;

class ZenogreAppointmentComponent{

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
        self.vehicleMake = $(self.element).find("[data-vehicle-select-make]");
        self.vehicleModel = $(self.element).find("[data-vehicle-select-model]");
        self.vehicleYear = $(self.element).find("[data-vehicle-select-year]");
        self.vehicleInput = $(self.element).find("[data-vehicle-text-area]");
        self.coupons = $(self.element).find("[data-specials]");
        self.couponWrapper = $(self.element).find('[data-zen-element="coupon"]');
        self.couponTitle = self.couponWrapper.find('[data-zen-element="coupon-title"]');
        self.couponContent = self.couponWrapper.find('[data-zen-element="coupon-content"]');
        self.appointmentHoursStep = self.data.config.step_input;
        self.startingDay = self.data.config.starting_day;
        self.successPageUrl = undefined;
        self.title = $(self.element).find('[data-zen-element="title"]');
        self.description = $(self.element).find('[data-zen-element="description"]');
        self.locationsDropdown = $(self.element).find('[data-locations]');
        self.selectedLocation = undefined;
        self.datePickers = [];
        self.isFullWidget = self.data.config.layout !== "shortWidget" ;
        self.ddHoursFormat = self.data.config.ddHoursFormat;
        self.timeEnabled = self.data.config.enableTime_input;
        self.customInputs = self.data.config.customInputsList;
        self.customInputsCheckboxOptions = self.data.config.multipleCheckboxOptions;
        self.customInputsSection = $(self.element).find('[data-zen-component="custom-fields-section"] ul')[0];
        self.optInEnabled = isNullOrUndefined(self.data.config.showOptIn) ? true : self.data.config.showOptIn;
        self.usePlaceholder = isNullOrUndefined(self.data.config.usePlaceholder) ? true : self.data.config.usePlaceholder;

        if(self.data.config.enableSuccessPageRedirect_input)
        {
            self.successPageUrl = self.data.config.successPageRedirectURL_input?.href;
            if(self.data.inEditor)
            {
                self.successPageUrl = self.data.config.successPageRedirectURL_input?.raw_url;
            }
        }

        if(self.customInputs && self.customInputs.length && self.customInputsSection){
            generateOptions(self.customInputs);
        }

        if(data.config.toggleReCaptcha){
            self.addReCaptcha = data.config.toggleReCaptcha;
            self.gReCaptchaSitekey = data.config.reCaptchaSiteKey;
            if(self.addReCaptcha && self.gReCaptchaSitekey && self.gReCaptchaSitekey.length > 0){
                if(typeof __google_recaptcha_client === "undefined"){
                     dmAPI.loadScript(`https://www.google.com/recaptcha/enterprise.js?render=${self.gReCaptchaSitekey}`);
                }
            }
        }

        self.SetupInputsValidation();
        self.SetupSubmitAppointment();
        self.SetupDatepickers();
        self.GetAndBindResonForVisit();

        jQuery.ajax({
            method: "GET",
            url: `${zenogreApiBaseUrl}/settings/${clientId}/appointmentwidgetsettings?page=${data.page}&elementid=${data.elementId}&widgetId=${data.widgetId}`,
            headers: {
                        'Content-Type': 'application/json',
                        'zw-client' : clientId,
                    },
            success: function(dataPayload, status, xhr) {
                widgetSettings = dataPayload;

                let notHiddenLocations = widgetSettings.locations.filter(function( location ) {
                  return location.hidden !== true;
                });

                if(widgetSettings.locations.length == 0 || notHiddenLocations.length == 0 && !data.inEditor){
                    $(self.element).find('[data-zen-component="loading-failed"]').show();
                    $(self.element).find('[data-zen-component="loading-failed"]').addClass('loading-failed');
                }
                else{
                    if(!notHiddenLocations.length){
                        $(self.element).prepend('<div data-zen-component="no-visible-locations" class="loading-failed" style="margin-bottom: 1rem;">All available locations are set as hidden. To set a location to be visible in the widget, please uncheck the "Hide Location from Appointment Form" checkbox in the Settings panel.</div>');
                    } else{
                        $(self.element).find('[data-zen-component="appointment"]').show();

                        self.BindLocations();

                        self.InitDeliveryOptionsDropdown();
                        self.SetupVehicleCheckbox();
                        self.optInEnabled && self.SetupOptIn();
                        self.GetAndBindSpecials();
                        self.SetupRequiredFields();
                    }

                    if(data.inEditor && settingsPanel === undefined){
                        settingsPanel = new ZenogreSettingsComponent({
                            root: self.element,
                            minutesStep: self.appointmentHoursStep
                        });
                    }
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $(self.element).find('[data-zen-component="loading-failed"]').show();
                $(self.element).find('[data-zen-component="loading-failed"]').addClass('loading-failed');
            }
        });
    }

    SetupRequiredFields(){
        let self = this;

        const $inputs = $(self.element).find("input, select, textarea");
        const usePlaceholder = self.usePlaceholder;

        $inputs.each((idx, el) => {
            const isRequired = $(el).attr('required');
            let parent = $(el).parent('.required');
            const placeholderText = $(el).attr('placeholder');

            if($(el).is('input[type=radio]')) {
                parent = $(el).parent().parent().find('.required');
            }

            if (usePlaceholder){
                if (typeof isRequired !== 'undefined' && typeof parent !== 'undefined' ) {
                    $(parent).addClass("withPlaceholder");

                    if (placeholderText && placeholderText.length > 0 && !placeholderText.contains(' (required)')){
                        const newPlaceholder = placeholderText + ' (required)';
                        $(el).attr("placeholder", newPlaceholder);
                    }

                    if($(el).is('select')) {
                        const firstOption = $(el).find('option:first-of-type');
                        if (firstOption){
                            const old = $(firstOption).text();
                            if(!old.contains(' (required)')){
                                const newText = old + ' (required)';
                                $(firstOption).text(newText);
                            }
                        }
                    }

                    if($(el).is('input[type=radio]')) {
                        const old = $(parent).text();
                        if (!old.contains(' (required)')){
                            const newText = old + ' (required)';
                            $(parent).text(newText);
                        }
                    }
                }
            } else {
                $(parent)?.removeClass("withPlaceholder");

                if (placeholderText && placeholderText.length > 0 && placeholderText.contains(' (required)')){
                    const newPlaceholder = placeholderText.replace(' (required)','');
                    $(el).attr("placeholder", newPlaceholder);
                }

                if($(el).is('select')) {
                    const firstOption = $(el).find("option:contains(' (required)')");
                    if (firstOption){
                        const old = $(firstOption).text();
                        const newText = old.replace(' (required)','');
                        $(firstOption).text(newText);
                    }
                }

                if($(el).is('input[type=radio]')) {
                    const old = $(parent).text();
                    const newText = old.replace(' (required)','');
                    $(parent).text(newText);
                }
            }

        });
    }

    SetupInputsValidation(){
        let self = this;

        var $inputs = $(self.element).find("input, select, textarea");

        $inputs.each(function(){
            if(typeof $(this).data('validation-text') !== 'undefined'){
                this.oninvalid = function(e) {
                    e.target.setCustomValidity("");
                    if (!e.target.validity.valid) {
                        e.target.setCustomValidity($(this).data('validation-text'));
                    }
                }
                this.oninput = function(e){
                    e.target.setCustomValidity("");
                }
            }
            this.onfocus = () => {
                this.style.setProperty('color', self.data.config.inputsFocusColor, 'important');
                this.style.setProperty('border-color', self.data.config.inputsBorderColor, 'important');
            }
            this.onblur = () => {
                this.removeAttribute("style");
            }
        });
    }

    SetupVehicleCheckbox(){
        let self = this;

        self.vehicleInput.not(".short-layout").hide();

        $(self.element).find("[data-vehicle-checkbox]").on("click",function(){
            self.vehicleMake.prop("selectedIndex", 0);
            var $checked = $(this).is(":checked");

            if ($checked) {
                self.vehicleMake.find('option:selected').text("Make");
                self.vehicleInput.show();
                self.vehicleInput.attr("required","");
                $(self.vehicleInput.parent('.required')).removeClass('hidden');
                $('.vehicle-info .select-wrapper').removeClass("required");
            }
            else {
                self.vehicleInput.hide();
                self.vehicleInput.removeAttr("required");
                $(self.vehicleInput.parent('.required')).addClass('hidden');
                $('.vehicle-info .select-wrapper').addClass("required");
            }
            var $vehicleInfo = $(self.element).find("[data-form-vehicle]").find('select');
            $vehicleInfo.each(function () {
                if ($checked) {
                    this.removeAttribute("required");
                    this.setAttribute("disabled", "");
                }
                else {
                    this.setAttribute("required", "");
                    this.removeAttribute("disabled");
                }
            });
            self.ResetModelAndYear();
            self.SetupRequiredFields();
        });
    }

    SetupOptIn(){
        let self = this;

        jQuery.ajax({
            method: "GET",
            url: `${zenogreApiBaseUrl}/clients/${clientId}`,
            headers: {
                'Content-Type': 'application/json',
                'zw-client' : clientId,
            },
            success: function (dataPayload) {
                if(dataPayload !== null && dataPayload.optin !== null && dataPayload.optin.textConnectEnabled){

                    let appendLocationId = "";
                    if(widgetSettings.locations.length > 1 && self.selectedLocation !== undefined){
                        appendLocationId = `?locationId=${self.selectedLocation.id}`;
                    }

                    $(self.element).find('[data-zen-element="terms-and-conditions-message"]')
                    .append(` <a href="${zenogreTermsAndConditionsUrl}${appendLocationId}" target="_blank" class="optin-link">terms and conditions</a>, and <a href="${zenogrePrivacyPageUrl}" target="_blank" rel="nofollow" class="optin-link">privacy policy</a> pages.`);
                    if(dataPayload.optin.title){
                        self.title.html(dataPayload.optin.title);
                    }
                    if(dataPayload.optin.message){
                        self.description.html(dataPayload.optin.message);
                    }
                    $(self.element).find('[data-zen-component="optin"]').show();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log("Error occurred while getting the data.");
            }
        });
    }

    SetupVehicleDropdowns(){
        let self = this;

        self.GetAndBindVehicleMakes();

        $(self.element).find("select").on("change",function(e){
            if(this.name==="vehicleMake"){
                $(this).removeClass("not-selected");
                e.stopImmediatePropagation();
                self.ResetModelAndYear();
                var $makeId = $(this).find('option:selected').val();
                self.GetAndBindVehicleModels($makeId);
            }
            if(this.name==="vehicleModel"){
                $(this).removeClass("not-selected");
                self.vehicleYear.html(`<option value="" selected disabled>Year</option>`)
                for (var i = new Date().getFullYear()+1; i >=1950 ; i--) {
                        self.vehicleYear.append(`<option value="${i}">${i}</option>`);
                    }
                $(self.element).find("[data-vehicle-select-year]").prop("disabled", false);
            }
            if(this.name==="vehicleYear"){
                $(this).removeClass("not-selected");
            }
            self.SetupRequiredFields();
        });
    }

    GetAndBindVehicleMakes(){
        let self = this;

        let vehicleMakesResult;

        var notListed = $(self.element).find("[data-vehicle-checkbox]")
        var checked = notListed.is(":checked");

        if(checked) {
            self.vehicleMake.html('<option value="">Make</option>');
        } else {
            self.vehicleMake.html(`<option value="" class="not-selected" selected disabled>Make</option>`);
        }

        jQuery.ajax({
            method: "GET",
            url: `${zenogreApiBaseUrl}/vehicles/makes?clientId=${clientId}&locationId=${self.selectedLocation.id}`,
            headers: {
                'Content-Type': 'application/json',
                'zw-client' : clientId,
            },
            success: function(dataPayload, status, xhr) {
                if(typeof(dataPayload) !== 'undefined'){
                     dataPayload.makes.forEach((make) => {
                        self.vehicleMake.append(`<option value="${make.id}">${make.make}</option>`);
                    });
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });

    }

    GetAndBindVehicleModels(makeId){
        let self = this;

        let vehicleModelsResultForMake;

        jQuery.ajax({
            method: "GET",
            url: `${zenogreApiBaseUrl}/vehicles/models/${makeId}/?clientId=${clientId}&locationId=${self.selectedLocation.id}`,
            headers: {
                        'Content-Type': 'application/json',
                        'zw-client' : clientId,
                    },
            success: function(dataPayload, status, xhr) {
                vehicleModelsResultForMake = dataPayload;

                if(vehicleModelsResultForMake !== undefined){
                    vehicleModelsResultForMake.models.forEach((model)=>{
                        self.vehicleModel.append(`<option value="${model.id}">${model.model}</option>`);
                    })
                    self.vehicleModel.prop("disabled", false);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });
    }

    ResetModelAndYear(){
        let self = this;

        var notListed = $(self.element).find("[data-vehicle-checkbox]")
        var checked = notListed.is(":checked");

        if (checked) {
            self.vehicleModel.html( '<option value="">Model</option>');
            self.vehicleYear.html('<option value="">Year</option>');
        } else {
            self.vehicleModel.html( `<option value="" selected disabled>Model</option>`);
            self.vehicleYear.html(`<option value="" selected disabled>Year</option>`);
        }

        if (!self.vehicleModel.get(0).hasAttribute("disabled")) {
            self.vehicleModel.attr("disabled", "");
        }

        if (!self.vehicleYear.get(0).hasAttribute("disabled")) {
            self.vehicleYear.attr("disabled", "");
        }
    }

    GetAndBindSpecials(){
        let self = this;

        jQuery.ajax({
            method: "GET",
            url: `${zenogreApiBaseUrl}/clients/${clientId}/specials`,
            headers: {
                'Content-Type': 'application/json',
                'zw-client' : clientId,
            },
            success: function(dataPayload, status, xhr) {

                if(dataPayload.specials !== undefined){
                    if(dataPayload.specials.length > 0){
                        let specialId = new URLSearchParams(window.location.search).get('special');

                        let isExisting = typeof(specialId) !== 'undefined' && specialId !== null;

                        dataPayload.specials.forEach((coupon) => {

                            if(isExisting && specialId === coupon.id){
                                self.coupons.append(`<option data-content="${coupon.text}" value="${coupon.id}" selected="selected">${coupon.title}</option>`);
                                specialChange(coupon.title, coupon.text);
                            }
                            else{
                                self.coupons.append(`<option data-content="${coupon.text}" value="${coupon.id}">${coupon.title}</option>`);
                            }
                        });
                    }
                    else {
                        $(self.element).find('[data-zen-component="specials-section"]').hide();
                    }
                }

                $(element).find("select").on("change",function(){
                    if(this.name==="coupons"){

                        const coupon = $(self.element).find(".coupon");
                        if(coupon.length){
                            $(coupon).remove();
                        }

                        if(self.coupons[0].selectedIndex !== 0){
                            $(this).removeClass("not-selected");

                            let specialTitle = $(this).find('option:selected').text();
                            let specialBody = $(this).find('option:selected').data("content");

                            specialChange(specialTitle, specialBody);
                        }
                        else{
                            $(this).addClass("not-selected");
                        }
                    }
                });

            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });

        function specialChange(title, body){
            var $coupon = $("<div></div>").addClass('coupon');
            var $title = $(`<div>${title}</div>`).addClass('coupon-title');
            var $content = $(`<div>${body}</div>`).addClass('coupon-content');
            $coupon.append($title, $content);
            $(self.element).find('.specials-wrapper').append($coupon);
        }
    }

    GetAndBindResonForVisit(){
        let self = this;

        let reason = new URLSearchParams(window.location.search).getAll('reason');

        if(reason !== undefined && reason !== ""){

            let concatenatedReason = '';
            for(let i=0; i < reason.length; i++){
                let separatorString = self.isFullWidget === true ? ` \n` : ` | `

                concatenatedReason += (i+1 !== reason.length) ? `${reason[i]}${separatorString}` : `${reason[i]}`;
            }

           $(self.element).find("[data-appointment-reason]").val(concatenatedReason);
        }
    }

    SetupDatepickers(){
        let self = this;

        if (typeof(TheDatepicker) !== "undefined") {
            initAppointmentDatePickers(jQuery(self.element).find("[data-zen-component='appointmentDatePicker']"));
        }
        else {
            dmAPI.loadScript('../zenogrecdn.kukui.com/libs/the-datepicker/0.7.1/the-datepicker.js', function(){ initAppointmentDatePickers(jQuery(self.element).find("[data-zen-component='appointmentDatePicker']")); } );
        }

        function initAppointmentDatePickers(datePickerComponents) {
            jQuery.each(datePickerComponents, function(index, datePickerComponent) {
                var $dateInput = jQuery(datePickerComponent).find("[data-zen-component='appointmentDatePicker.Date']");
                var $datePicker = new TheDatepicker.Datepicker($dateInput[0]);
                $datePicker.options.setInputFormat("m/d/Y");
                self.datePickers.push($datePicker);

                // Initial loading
                self.SetupDatePicker($datePicker);
            });
        }
    }

    SetupDatePicker(datePickerPayload) {
        let self = this;

        setupDatePicker(datePickerPayload);

        /* Setup date pickers */
        function calculateAvailableHours(date, startTime, endTime, step, hoursOffset){
            if (date == null || date == '') {
                return;
            }

            var regExTime = /([0-9]?[0-9]):([0-9][0-9])/;
            var startTimeArr = regExTime.exec(startTime);
            var endTimeArr = regExTime.exec(endTime);
            var currentDate = new Date(date);
            var todayDate = new Date();

            if(currentDate.getFullYear() == todayDate.getFullYear() &&
                 currentDate.getMonth() == todayDate.getMonth() &&
                 currentDate.getDate() == todayDate.getDate()){

                    currentDate = new Date(todayDate.getTime() + ((hoursOffset * 60) * 60000));
            }

            var originalStartTimeObject = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate(), startTimeArr[1], startTimeArr[2]);
            var startTimeObject = new Date(originalStartTimeObject.getTime() + ((hoursOffset * 60) * 60000));
            var endTimeObject = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate(), endTimeArr[1], endTimeArr[2]);
            var dataListArr = '';
            var currentValue = startTimeObject;

            if(currentValue > currentDate){
                dataListArr += '<option>' + startTimeObject.toLocaleTimeString('en-US', { hour12: use24h ? false : true, timeStyle: "short"}) + '</option>';
            }

            while(currentValue <= endTimeObject)
            {
            	currentValue = new Date(currentValue.getTime() + (step * 60000));

            	if(currentValue > currentDate && currentValue <= endTimeObject)
                {
                    dataListArr += '<option>' + currentValue.toLocaleTimeString('en-US', { hour12: use24h ? false : true, timeStyle: "short"}) + '</option>';
                }
            }
            return dataListArr;
        }

        function populateAvailableHours(date, step, list){

            appendDefaultValueToTimepicker(list);

            var $convertedDate = new Date(date);

            var $dayName = getDayName($convertedDate.getDay());

            var $workDays = $.grep(self.selectedLocation.appointmentTimes, function(item){
            	return item.day == $dayName;
            });
            var hours = "";

            for (let i = 0; i < $workDays.length; i++) {

                if(i==0){
                    hours = calculateAvailableHours(date, get24hfrom12h($workDays[i].openTime), get24hfrom12h($workDays[i].closeTime), step, self.selectedLocation.hoursOffset);
                }
                else{
                    hours = calculateAvailableHours(date, get24hfrom12h($workDays[i].openTime), get24hfrom12h($workDays[i].closeTime), step, 0);
                }

                $(list).append(`${hours}`);
            }

            return list;
        }

        function setupDatePicker(datePicker){

            let $timeList =  jQuery(self.element).find("[data-zen-component='appointmentDatePicker.TimeList']");

            if(self.timeEnabled)
            {
                $timeList.append(`<option value="" disabled selected>HH:MM</option>`);
            }

            let dateInput = datePicker.input;

            if(self.selectedLocation === undefined)
            {
                $(dateInput).prop("disabled", true);
                self.timeEnabled ? $timeList.prop("disabled", true) : '';
                return;
            }
            else
            {
                $(dateInput).prop("disabled", false);
                self.timeEnabled ? $timeList.prop("disabled", false) : '';
            }

            let minDate = calculateMinDate();
            let minDateFormatted = minDate.toLocaleDateString("en-ZA"); //using "en-ZA" for the string format
            let firstDay = getFirstDay();

            datePicker.options.setMinDate(minDateFormatted);
            datePicker.options.setFirstDayOfWeek(firstDay);
            datePicker.options.setDateAvailabilityResolver((date) => {
                //If added later on as extra check
                if (date < minDate){
                    var datetime = new Date(date.getFullYear(), date.getMonth(), date.getDate(), minDate.getHours(), minDate.getMinutes(), minDate.getSeconds(), minDate.getMilliseconds());
                    if (datetime < minDate) return false;
                }

                var $isDateAvailable = true;

                if(!Array.isArray(self.selectedLocation.disabledDays) || !self.selectedLocation.disabledDays.length){
                    $isDateAvailable = true;
                }
                else {
                    self.selectedLocation.disabledDays.forEach((disabledDateEntry) => {
                        let disableDate = new Date(disabledDateEntry);
                        let a = new Date(Date.UTC(disableDate.getFullYear(),disableDate.getMonth(), disableDate.getDate()));
                        let b = new Date(Date.UTC(date.getFullYear(),date.getMonth(), date.getDate()));
                        if(a.getTime() === b.getTime()) {
                           $isDateAvailable = false;
                           return;
                        }
                    });
                }

                if($isDateAvailable === true){
                    var $availableWeekDays = getAvailableWeekDays(self.selectedLocation);
                    $isDateAvailable = $availableWeekDays.includes(getDayName(date.getDay()));
                }

                return $isDateAvailable;
            });

            datePicker.render();

            if(self.timeEnabled)
            {

                $timeList.each(function(){
                    appendDefaultValueToTimepicker(this);
                    $(this).prop('disabled', true);

                });

                datePicker.options.onSelect(function (event, day, previousDay) {

                    var timeListDropdown = $(event.target).closest('[data-zen-component="appointmentDatePicker"]').find('[data-zen-component="appointmentDatePicker.TimeList"]');

                    $(timeListDropdown).prop('required', true);
                    $(timeListDropdown).addClass('required');

                    if(day !== null){
                        $timeList = populateAvailableHours(day.getDate(), self.appointmentHoursStep, timeListDropdown);

                        $(timeListDropdown).prop('disabled', false);
                    }
                    else{ // day will be null only when the selected value is cleared with the dedicated button
                        $timeList = populateAvailableHours(minDate, self.appointmentHoursStep, timeListDropdown);
                        $(timeListDropdown).prop('disabled', true);
                    }
                });
            }
        }

        function appendDefaultValueToTimepicker(list){
             $(list).empty();

            var target = window.event ? window.event.target : $(list).closest('.the-datepicker__deselect-button');
            let required = $(list).prop('required');

            if($(target).attr("class") === "the-datepicker__deselect-button"
                && $(list).attr('id') === "secondChoiceTimeList"
                && required === true)
            {
                 $(list).removeAttr('required');
                 required = false;
            }

            $(list).append(`<option value="" disabled selected>${self.usePlaceholder && required ? "HH:MM (required)" : "HH:MM"}</option>`);

        }

        function getAvailableWeekDays(selectedLocation){
            var $result = [];
            var $map = new Map();
            for (const item of self.selectedLocation.appointmentTimes) {
                if(!$map.has(item.day)){
                    $map.set(item.day, true);    // set any value to Map
                    $result.push(item.day);
                }
            }

            return $result;
        }

        function getDayName(dayNumber){
            var $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            var $dayName = $days[dayNumber];
            return $dayName;
        }

        function initAppointmentDatePickers(datePickerComponents) {
            jQuery.each(datePickerComponents, function(index, datePickerComponent) {

                var $dateInput = jQuery(datePickerComponent).find("[data-zen-component='appointmentDatePicker.Date']");
                var $datePicker = new TheDatepicker.Datepicker($dateInput[0]);
                $datePicker.options.setInputFormat("m/d/Y");
                self.datePickers.push($datePicker);

                // Initial loading
                setupDatePicker($datePicker);
            });
        }

        function calculateMinDate(){
            let $calculatedDaysOffset = (self.selectedLocation.daysOffset !== null ? self.selectedLocation.daysOffset : 0) * 86400000;
            let $calculatedHoursOffset = (self.selectedLocation.hoursOffset !== null ? (self.selectedLocation.hoursOffset * 60) : 0) * 60000;

            let $currentDateTime = new Date().getTime() + $calculatedDaysOffset;

            let $convertedCurrentDate = new Date($currentDateTime);
            let $dayName = getDayName($convertedCurrentDate.getDay());

            let $workDays = $.grep(self.selectedLocation.appointmentTimes, function(item){
            	return item.day == $dayName;
            });

            let regExTime = /([0-9]?[0-9]):([0-9][0-9])/;

            let currentDateHoursOffset = new Date(new Date().getTime() + $calculatedHoursOffset);

            let isWorkingTimeAvailable = true;

            for (let i = 0; i < $workDays.length; i++) {

                let endTime = self.timeEnabled ? get24hfrom12h($workDays[i].closeTime) : get24hfrom12h($workDays[i].openTime);
                let endTimeArr = regExTime.exec(endTime);

                let endTimeObject = new Date($convertedCurrentDate.getFullYear(), $convertedCurrentDate.getMonth(), $convertedCurrentDate.getDate(), endTimeArr[1], endTimeArr[2]);

                if (currentDateHoursOffset.getFullYear() == endTimeObject.getFullYear() &&
                currentDateHoursOffset.getMonth() == endTimeObject.getMonth() &&
                currentDateHoursOffset.getDate() == endTimeObject.getDate()){

                isWorkingTimeAvailable = currentDateHoursOffset.getTime() < endTimeObject.getTime();
                }
            }

            if(!isWorkingTimeAvailable)
            {
                $currentDateTime += 86400000;
            }

            return new Date($currentDateTime);
        }

        function getFirstDay(){
            switch(self.startingDay){
                case 'Monday': return TheDatepicker.DayOfWeek.Monday;
                case 'Sunday': return TheDatepicker.DayOfWeek.Sunday;
                default: return TheDatepicker.DayOfWeek.Sunday;
            }
        }

        /* end - Setup date pickers */
    }

    SetupSubmitAppointment(){
        let self = this;

        /* submit */
        $(self.element).find("form").on("submit",function(e){
            e.preventDefault();

            if(self.addReCaptcha && self.gReCaptchaSitekey.length > 0){
                grecaptcha.enterprise.ready(function() {
                    grecaptcha.enterprise.execute(self.gReCaptchaSitekey, {action: 'submit'}).then(function(token) {
                        let additionalHeaders = {
                            'g-reCaptcha-siteKey': self.gReCaptchaSitekey,
                            'g-reCaptcha-token': token
                        }
                        //calling the method should happen here because executing and returning token could take time and submit must happen after
                        self.SubmitAppointment(additionalHeaders);
                    });
                });
            }
            else {
                self.SubmitAppointment();
            }
        });
    }

    SubmitAppointment(additionalHeaders){
        let self = this;
        var $firstChoice = $(self.element).find("[data-date=\"first-choise\"]")[0].datepicker.getSelectedDate();

        var $firstChoiceTimeElement = $(self.element).find("[data-time=\"first-choise\"]")[0];
        var $firstChoiceTime = self.timeEnabled ? $firstChoiceTimeElement.options[$firstChoiceTimeElement.selectedIndex].text : self.GetOpenTime($firstChoice);

        var $secondChoice = $(self.element).find("[data-date=\"second-choise\"]");
        var $secondChoiceTimeElement = $(self.element).find("[data-time=\"second-choise\"]")[0];

        var payload = $.extend({},{
            "clientId": clientId,

            "locationId": self.selectedLocation.id,

            "firstChoice": $firstChoice !== null ? self.GetFormatedDateForInputs($firstChoice, $firstChoiceTime) : undefined,

            "firstName": $(self.element).find("[data-first-name]").val(),

            "lastName": $(self.element).find("[data-last-name]").val(),

            "email": $(self.element).find("[data-email]").val(),

            "phone": $(self.element).find("[data-phone]").val(),

            "reason":  $(self.element).find("[data-appointment-reason]").val(),

            "vehicle": (self.vehicleInput.is(":hidden")) ? self.vehicleMake.find('option:selected').text()+" "+self.vehicleModel.find('option:selected').text()+" "+self.vehicleYear.find('option:selected').text() : self.vehicleInput.val(),

            "coupon":  self.coupons.find('option:selected').val(),

            "kpTracking": getCookie("_KPTracking"),
        });

        if($secondChoice.length > 0){

            let $secondChoiceDate = $secondChoice[0].datepicker.getSelectedDate();
            if($secondChoiceDate !== null){

                var $secondChoiceTime = self.timeEnabled ?  $secondChoiceTimeElement.options[$secondChoiceTimeElement.selectedIndex].text : self.GetOpenTime($secondChoiceDate);
                payload.secondChoice = self.GetFormatedDateForInputs($secondChoiceDate, $secondChoiceTime);
            }
        }

        payload.metaFields = [];

        const callme = $(self.element).find('[data-zen-element="call-me"]')
        if(callme && callme.is(":checked")){
            var newMetaObject = {key: "callMeIn30", value: 'Customer requests call back in 30 minutes.'};
            payload.metaFields.push(newMetaObject);
        }

        if($(self.element).find('[data-zen-element="optin-checkbox"]').is(":checked")){
            var newMetaObject = {key: "optin", value: $(element).find('[data-zen-element="optin-checkbox"]').is(":checked")};
            payload.metaFields.push(newMetaObject);
        }

        if(self.data.config.vin_input){
            var $vehicleVin = $(self.element).find('[data-zen-element="vehicle-vin"]').val();
            if($vehicleVin){
                var vinMetaObject = {key: "vin", value: `${$vehicleVin}`};
                payload.metaFields.push(vinMetaObject);
            }
        }

        if(self.data.config.licensePlate_input){
            var $licensePlate = $(self.element).find('[data-zen-element="vehicle-license-plate"]').val();
            if($licensePlate){
                var licensePlateMetaObject = {key: "licensePlate", value: `${$licensePlate}`};
                payload.metaFields.push(licensePlateMetaObject);
            }
        }

        if(self.data.config.deliveryOptions_input){
            var $deliveryOption = $(self.element).find('[data-zen-element="delivery-options"]').val();
            if($deliveryOption){
                var deliveryOptionMetaObject = {key: "deliveryOption", value: `${$deliveryOption}`};
                payload.metaFields.push(deliveryOptionMetaObject);
            }
        }

       if(self.customInputs && self.customInputs.length && self.customInputsSection){
            let inputs = $(self.element).find('[data-zen-component="custom-fields-section"] ul')[0].children;
            if(inputs.length){
                let customInputs = getCustomInputs(inputs);

                function addValue(key, value, arr, keyOName, iteration) {
                    if (!arr.find((el) => el.key === key)) {
                        arr.push({ key: key, value: value });
                    }
                    else {
                        key = keyOName.concat(" ", iteration++);
                        addValue(key, value, arr, keyOName, iteration++);
                    }
                }

                if (customInputs.length) {
                    customInputs.forEach((el) => { addValue(el.key, el.value, payload.metaFields, el.key, 2); });
                }
            }
        }

        let requestHeaders = {
            'Content-Type': 'application/json',
            'zw-client' : clientId,
        }
        if(additionalHeaders){
            $.extend(requestHeaders, additionalHeaders);
        }

        jQuery.ajax({
            method: "POST",
            url: `${zenogreApiBaseUrl}/clients/${clientId}/appointments`,
            headers: requestHeaders,
            data: JSON.stringify(payload),

            beforeSend: function() {
                var $validationArea= jQuery(self.element).find("[data-validation-message]");
                $validationArea.hide();

                var $submitButton = jQuery(self.element).find("[data-zen-component='appointment.Submit']");
                $submitButton.prop('disabled', true);
                $submitButton.parent().addClass('disabled');
            },
            success: function() {
                var $validationArea= jQuery(self.element).find("[data-validation-message]");

                if (self.successPageUrl) {
                    try {
                        window.location.replace(self.successPageUrl);
                    }
                    catch (e) {
                        if (data.inEditor) {
                            let validationAreaParent = $validationArea.parent();
                            let validationAreaRedirect = $('<div data-zen-element="validation-redirect-message" class="textbox error">Redirect URL is invalid.(this message is visible only in Editor mode)<div>');
                            validationAreaParent.append(validationAreaRedirect)
                        }
                    }
                }

                $validationArea.html("Thank you. Your appointment request has been submitted.");
                $validationArea.attr('class', 'textbox success');
                $validationArea.show();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                var $errorContainer="<ul>";

                if(xhr.responseJSON && xhr.responseJSON.errors !== undefined){
                    jQuery.each(xhr.responseJSON.errors,function(i,v){
                       jQuery.each(v,function(){
                           $errorContainer+=`<li>${this}</li>`;
                       });
                    });
                }
                else {
                    let message = (xhr.status === 401) ? "Validation problem. Please try again later." : "There was a problem submitting your appointment. Please try again later."
                    $errorContainer += `<li>${message}</li>`;
                }

                $errorContainer+="</ul>";
                var $validationArea= jQuery(self.element).find("[data-validation-message]");
                $validationArea.html($errorContainer);
                $validationArea.attr('class', 'textbox error');
                $validationArea.show();
                var $submitButton = jQuery(self.element).find("[data-zen-component='appointment.Submit']");
                $submitButton.prop('disabled', false);
                $submitButton.parent().removeClass('disabled');
            }
        });
    }

    GetOpenTime(date)  {
        let self = this;

        var $convertedDate = new Date(date);

        var $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var $dayName = $days[$convertedDate.getDay()];

        let $workDays = $.grep(self.selectedLocation.appointmentTimes, function(item){
            	return item.day == $dayName;
        });

        return $workDays[0].openTime;
    }

    GetFormatedDateForInputs(date, hour){
        let self = this;
        var regExTime = /([0-9]?[0-9]):([0-9][0-9])/;

        hour = get24hfrom12h(hour);

        var hourArr = regExTime.exec(hour);
        return `${date.getFullYear()}-${date.getMonth()+1}-${date.getDate()}T${hourArr[1]}:${hourArr[2]}`;
    }

    InitDeliveryOptionsDropdown(){
        let self = this;

        if(self.data.config.deliveryOptions_input){
            if(!self.data.config.dropOff_input && !self.data.config.delivery_input && !self.data.config.waiting_input && !self.data.config.shuttle_input){
                $(self.element).find('[data-zen-component="delivery-options-section"]').hide();
            }

            if(!self.data.config.dropOff_text && !self.data.config.delivery_text && !self.data.config.waiting_text && !self.data.config.shuttle_text){
                $(self.element).find('[data-zen-component="delivery-options-section"]').hide();
            }
        }
    }

    BindLocations(){
        let self = this;

        let appointmentForm = $(self.element).find(`[data-zen-component="appointment-form"]`)

        let appointmentWidgetBody = $(self.element).find('[data-zen-component="widget-body"]');

        let notHiddenLocations = widgetSettings.locations.filter(function( location ) {
          return location.hidden !== true;
        });

        if(notHiddenLocations.length > 1){
            $(self.locationsDropdown).addClass("not-selected");

            notHiddenLocations.forEach((location)=>{
                self.locationsDropdown.append(`<option value="${location.id}">${location.name}</option>`);
            });

            $(appointmentWidgetBody).addClass('disabled-element')
            $(appointmentForm).addClass('default-cursor')
        }
        else{
            $(self.element).find('[data-zen-component="locations-section"]').hide();
            $(self.element).find('[data-zen-component="locations-section"] select').removeAttr('required');
            self.selectedLocation = notHiddenLocations[0];

            self.SetupVehicleDropdowns();

            self.datePickers.forEach((i) => {
                    self.SetupDatePicker(i);
                });
        }

        $(self.element).find("select").on("change",function(){
            if(this.name==="locations"){
                let selectedValue = $(this).find('option:selected').val();
                if(this.selectedIndex != 0){
                    self.selectedLocation = $.grep(notHiddenLocations, function(item){
                        return item.id == selectedValue;
                    })[0];

                    $(appointmentWidgetBody).removeClass('disabled-element');
                    $(appointmentForm).removeClass('default-cursor');

                    if( self.isFullWidget){
                        self.SetupVehicleDropdowns();
                        self.ResetModelAndYear();
                    }

                    var dateDeselects = $(self.element).find('.the-datepicker__deselect').toArray();

                    dateDeselects.forEach((i) => {

                        if($(i).css('visibility') === "visible")
                        {
                           $(i).find('.the-datepicker__deselect-button')[0].click();
                        }
                    });

                    let termsAndConditions = $(self.root).find('[data-zen-element="terms-and-conditions-message"] a')[0];
                    if (termsAndConditions){
                        termsAndConditions.href = zenogreTermsAndConditionsUrl + `?locationId=${self.selectedLocation.id}`;
                    }

                }
                else
                {
                    self.selectedLocation = undefined;
                    $(appointmentWidgetBody).addClass('disabled-element');
                    $(appointmentForm).addClass('default-cursor');
                }

                self.datePickers.forEach((i) => {
                    self.SetupDatePicker(i);
                });

                self.SetupRequiredFields();
            }
        });

    }

    GetQueryParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }
}

class ZenogreSettingsComponent{

    constructor(options = {}){

        let self = this;
        self.settings = JSON.parse(JSON.stringify(widgetSettings));

        self.root = $(options.root);
        self.minutesStep = $(options.minutesStep);

        self.Init();

        self.appointmentTimesData = {};

        self.selectedLocation = {};

        self.updated = false;
    }

    Init(){
        let self = this;
        self.daysOfTheWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

        self.root.prepend("<div data-element='settingsTriger' class='settings-trigger'>Settings</div>");
        $(`
                <div data-element="settings" class="settings" style="display:none;">
                <h2>Settings</h2>
                <div data-element="locations" class="locations">
                    <span>Select the Location to load it's settings</span>
                </div>
                <div data-zen-element="locationSettings">
                    <div>
                        <div class="checkbox" data-input="hideLocation" data-zen-element="hide-location" id="hideLocation">Hide Location from Appointment Form</div>

                        <div><small>* The setting affects only this widget instance</small></div>
                    </div>
                    <div data-zen-element="appointment-times">
                    </div>
                </div>
                <div class="save-btn-wrapper" data-zen-component='save-widget-settings-wrapper' hidden>
                    <a title="Save changes" class="widget-button success-btn" href='javascript:void(0);' data-zen-element='save-widget-settings'><span class="text">Save Changes</span></a>
                </div>
            </div>
        `).insertAfter($(".settings-trigger"));

        let $settingsTrigger = self.root.find("[data-element='settingsTriger']");
        let $saveSettings = self.root.find("[data-zen-element='save-widget-settings']");
        let $settings = self.root.find("[data-element='settings']");
        let $appointmentWrapper = self.root.find("[data-zen-component='appointment-wrapper']");
        let noVisibleLocationsWarning = self.root.find("[data-zen-component='no-visible-locations']")[0];

        $settingsTrigger.on('click', function(){
           $settings.toggle();
           $settingsTrigger.toggleClass("visible");

           if($appointmentWrapper.is(":visible")){
               $appointmentWrapper.hide();
           }
           else{
               $appointmentWrapper.show();
           }

           if (noVisibleLocationsWarning){
               if($(noVisibleLocationsWarning).is(":visible")){
                $(noVisibleLocationsWarning).hide();
               }
               else{
                   $(noVisibleLocationsWarning).show();
               }
           }
        });

        $saveSettings.on('click',function(){
            self.SaveSettings();
        });

        $('head').append('<link rel="stylesheet" href="../cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">');

        if (typeof(timepicker) !== "undefined") {
             self.LoadData();
        }
        else {
            dmAPI.loadScript('https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js', function(){  self.LoadData(); });
        }
    }

    SetupWidgetSettings(locationsData){
        let self = this;

        let hiddenCheckbox = self.root.find("[data-zen-element='hide-location']");

        if (locationsData === undefined){
            hiddenCheckbox.on('click', function() {
                $(this).toggleClass("checked");
                self.selectedLocation.hidden = $(this).hasClass("checked");
                let saveSettingsButton = self.root.find("[data-zen-element='save-widget-settings']");
                $(saveSettingsButton).show();
                let saveBtnWrapper = self.root.find(`[data-zen-component='save-widget-settings-wrapper']`);
                saveBtnWrapper.show();
            });
        } else {
            locationsData.hidden ? $(hiddenCheckbox).addClass('checked') : $(hiddenCheckbox).removeClass('checked');
        }

    }

    SetupAddHoursButtons(locationData){
        let self = this;

        let appointmentTimes = self.root.find("[data-zen-element='appointment-times']");

        appointmentTimes.empty();

        appointmentTimes.append(`<legend>Appointment times</legend>`);

        let note = `
        <div data-zen-element="note-appointment-times" class="note-wrapper">
            <span class="note-warning">
                - If no hours are selected for a given day, that day will be disabled in Appointment Form calendar!
            </span>
            <span class="note-warning">
                - If there are no hours selected for any of the days, Appointment Form will default to the usual work time for this location.
            </span>
        </div>`

        appointmentTimes.append(note);

        for(let i=0; i < self.daysOfTheWeek.length; i++){
            let currentDay = self.daysOfTheWeek[i];

            let dayElement = `
                    <div data-zen-element="appointment-times-${currentDay}" class="workDay">
                        <span class="day-name">${currentDay}</span>
                        <div data-zen-component="selectedHours-${currentDay}">
                            <div class="existing-hours" data-zen-element="existing-hours-${currentDay}">

                            </div>
                            <div class="addHours-btn-wrapper">
                                <a title="Add hours" class="widget-button add-time-btn" href='javascript:void(0);' data-element='add-hours-${currentDay}'><span class="text">Add</span></a>
                            </div>
                        </div>
                        <div class="add-edit-hours" data-zen-component="add-edit-hours-${currentDay}" hidden>
                            <div class="time-pickers-wrapper">
                                <div class="appointmentTimePicker">
                                    <label class="appointmentTimePickerLabel" for="apptTimeFrom-${currentDay}">Start time</label>
                                    <input id="apptTimeFrom-${currentDay}" data-zen-element="appointment-time-from-${currentDay}" required class="time-picker-input timepicker">
                                </div>
                                <div class="appointmentTimePicker">
                                    <label class="appointmentTimePickerLabel" for="apptTimeTo-${currentDay}">End time</label>
                                    <input id="apptTimeTo-${currentDay}" data-zen-element="appointment-time-to-${currentDay}" required class="time-picker-input timepicker">
                                </div>
                            </div>

                            <div class="addEdit-buttons-wrapper">
                                <a title="Save hours" class="widget-button addEdit-btn addEdit-confirm-btn" href='javascript:void(0);' data-element='save-hours-${currentDay}'><span class="text">Confirm</span></a>

                                <a title="Cancel" class="widget-button addEdit-btn addEdit-cancel-btn" href='javascript:void(0);' data-element='cancel-hours-${currentDay}'><span class="text">Cancel</span></a>
                            </div>
                        </div>
                    </div>`;
            appointmentTimes.append(dayElement);

            let timePickerSettings = {
                timeFormat: use24h ? 'HH:mm' : 'hh:mm p',
                interval: 15,
                minTime: '0',
                maxTime: '23:59',
                defaultTime: '9',
                startTime: '8',
                dynamic: false,
                dropdown: true,
                scrollbar: true,
                zindex: 500
            };

            $($(appointmentTimes).find(`[data-zen-element="appointment-time-from-${currentDay}"]`)).timepicker(timePickerSettings);

            timePickerSettings.zindex++;
            $($(appointmentTimes).find(`[data-zen-element="appointment-time-to-${currentDay}"]`)).timepicker(timePickerSettings);

            if(locationData && locationData.appointmentTimes !== undefined){

                let currentDayAppointmentHours = $.grep(locationData.appointmentTimes, function(item){
                    return item.day == currentDay;
                })

                currentDayAppointmentHours.forEach((item) => {
                    item.openTime = get24hfrom12h(item.openTime);
                    item.closeTime = get24hfrom12h(item.closeTime);

                    var tempId = `${currentDay}-${item.openTime}-${item.closeTime}`;

                    item.id = tempId;

                    self.AddHours(currentDay, item.openTime, item.closeTime, tempId);
                });

                self.SetupAppointmentHoursButtonEvents(currentDay);
            }
        };
    }

    SetupTimePicker(timePickerElement){
        $(timePickerElement).timepicker();
    }

    SetupAppointmentHoursButtonEvents(currentDay){
        let self = this;

        let selectedHoursComponent = $(self.root).find(`[data-zen-component='selectedHours-${currentDay}']`);
        let addNewHoursComponent = $(self.root).find(`[data-zen-component='add-edit-hours-${currentDay}']`);

        let addButtonInstance = $(self.root).find(`[data-element='add-hours-${currentDay}']`)
        let saveButtonInstance = $(self.root).find(`[data-element='save-hours-${currentDay}']`)
        let cancelButtonInstance = $(self.root).find(`[data-element='cancel-hours-${currentDay}']`)
        let appointmentTimePickerFrom = $(self.root).find(`[data-zen-element='appointment-time-from-${currentDay}']`);
        let appointmentTimePickerTo = $(self.root).find(`[data-zen-element='appointment-time-to-${currentDay}']`);

        addButtonInstance.on('click',function(){
            selectedHoursComponent.hide();
            addNewHoursComponent.show();

            appointmentTimePickerFrom.val(null);
            appointmentTimePickerTo.val(null);
        });

        saveButtonInstance.on('click', function(){
            let fromTime = appointmentTimePickerFrom.val();
            let toTime = appointmentTimePickerTo.val();
            let tempId = `${currentDay}-${fromTime}-${toTime}`;

            let checkIdExists = $.grep(self.selectedLocation.appointmentTimes, function(item){return item.id == tempId;})

            if(checkIdExists !== undefined && checkIdExists.length > 0){
                alert(`Selected time period already exist. Please update the time and try again!`);
            }
            else{
                let appointmentTimePayload = {
                  "day": currentDay,
                  "openTime": get24hfrom12h(fromTime),
                  "closeTime": get24hfrom12h(toTime),
                  "id": tempId
                };

                if($(this).prop("time-entry-edit-id") === undefined){
                    self.selectedLocation.appointmentTimes.push(appointmentTimePayload);

                     self.selectedLocation.addedHours.push(appointmentTimePayload);
                }
                else{
                    let originalId = $(this).prop("time-entry-edit-id");

                    self.selectedLocation.appointmentTimes = self.selectedLocation.appointmentTimes.map(originalEntry => originalEntry.id !== originalId ? originalEntry : appointmentTimePayload);

                    $(this).removeProp("time-entry-edit-id");

                    self.selectedLocation.editedHours.push(appointmentTimePayload);
                }

                selectedHoursComponent.show();
                addNewHoursComponent.hide();

                let saveSettingsButton = self.root.find("[data-zen-element='save-widget-settings']");
                $(saveSettingsButton).show();

                self.SetupAddHoursButtons(self.selectedLocation);

                let saveBtnWrapper = $(self.root).find(`[data-zen-component='save-widget-settings-wrapper']`);
                saveBtnWrapper.show();
            }
        });

        cancelButtonInstance.on('click', function(){
            let saveButtonInstance = $(self.root).find(`[data-element='save-hours-${currentDay}']`);

            if($(saveButtonInstance).prop("time-entry-edit-id") !== undefined){
                    $(saveButtonInstance).removeProp("time-entry-edit-id");
            }

            selectedHoursComponent.show();
            addNewHoursComponent.hide();
        });
    }

    AddHours(currentDay, fromTime, toTime, tempId){
        let self = this;

        let existingHoursElement = $(self.root).find(`[data-zen-element='existing-hours-${currentDay}']`)

        var newHoursEntry = `
        <div id="time-entry-${tempId}" class="time-entry">
            <a title="Edit" class="btn-edit-hours" href='javascript:void(0);' data-zen-element='edit-hours-${tempId}'>
                <span id="${tempId}" class="text">${formatTimeForDisplay(fromTime)} - ${formatTimeForDisplay(toTime)}</span>
            </a>
            <a href='javascript:void(0);' class="btn-delete-hours" data-zen-element='delete-hours-${tempId}'>
                <span id="${tempId}-delete">X</span>
            </a>
        </div>`;

        existingHoursElement.append(newHoursEntry);

        let editButtonInstance = $(existingHoursElement).find(`[data-zen-element='edit-hours-${tempId}']`);
        let deleteButtonInstance = $(existingHoursElement).find(`[data-zen-element='delete-hours-${tempId}']`);

        editButtonInstance.on('click', function(){

            let saveButtonInstance = $(self.root).find(`[data-element='save-hours-${currentDay}']`);

            let dataEntry = $.grep(self.selectedLocation.appointmentTimes, function(item){
                return item.id == tempId;
            });

            if(dataEntry === undefined){
                dataEntry = $.grep(self.selectedLocation.addedHours, function(item){
                    return item.id == tempId;
                });
            }

            dataEntry = dataEntry[0];

            saveButtonInstance.prop("time-entry-edit-id", `${tempId}`);

            let selectedHoursComponent = $(self.root).find(`[data-zen-component='selectedHours-${currentDay}']`);
            let addNewHoursComponent = $(self.root).find(`[data-zen-component='add-edit-hours-${currentDay}']`);
            let appointmentTimePickerFrom = $(self.root).find(`[data-zen-element='appointment-time-from-${currentDay}']`);
            let appointmentTimePickerTo = $(self.root).find(`[data-zen-element='appointment-time-to-${currentDay}']`);

            appointmentTimePickerFrom.val(get24hfrom12h(dataEntry.openTime));
            appointmentTimePickerTo.val(get24hfrom12h(dataEntry.closeTime));

            selectedHoursComponent.hide();
            addNewHoursComponent.show();
        });

        deleteButtonInstance.on('click', function(){
            let entryForDeletion = $.grep(self.selectedLocation.appointmentTimes, function(item){ return item.id == tempId; });

            self.selectedLocation.appointmentTimes = $.grep(self.selectedLocation.appointmentTimes, function(item){ return item.id != tempId; });

            self.selectedLocation.deletedHours.push(entryForDeletion);

            self.SetupAddHoursButtons(self.selectedLocation);

            let saveBtnWrapper = $(self.root).find(`[data-zen-component='save-widget-settings-wrapper']`);
            saveBtnWrapper.show();
        });

    }

    LoadData(){
        let self = this;

        let settingsContainer = $(self.root).find('[data-element="settings"]')[0];

        let locationsContainer = $(self.root).find('[data-element="locations"]')[0];

        let locationSettings = $(self.root).find(`[data-zen-element='locationSettings']`);
        $(locationSettings).hide();

        if(self.settings.locations !== undefined){

            self.selectedLocation = undefined;

            let locationsDropDownInstance = $(locationsContainer).find('[data-element="location"]').remove();

            let select = $(`<select name="locations" data-element="location" data-number="0"><option value="00000000-0000-0000-0000-000000000000">-Select Location-</option></select>`).appendTo($(locationsContainer));

            self.settings.locations.forEach((location) => {
                select.append(`<option value="${location.id}">${location.name}</option>`)
            });

            if(self.settings.locations.length > 1){

                self.settings.locations.forEach(item => {
                   item.originalData = $.map(item.appointmentTimes, function(item){ return item; });
                });

                $(locationsContainer).find("select").on("change",function(){
                    if(this.name==="locations"){

                        let selectedValue = $(this).find('option:selected').val();

                        if(this.selectedIndex != 0){
                            if(self.selectedLocation !== undefined &&
                            ((self.selectedLocation.addedHours !== undefined && self.selectedLocation.addedHours.length > 0) ||
                            (self.selectedLocation.editedHours !== undefined && self.selectedLocation.editedHours.length > 0) ||
                            (self.selectedLocation.deletedHours !== undefined && self.selectedLocation.deletedHours.length > 0))){

                                if(confirm('There are unsaved changes for this location! Are you sure you want to continue?')){
                                    switchLocation(self.settings, selectedValue);
                                }
                                else{
                                   var lastDropdownEntry = $(select).find(`[value="${self.selectedLocation.id}"]`);
                                   lastDropdownEntry.attr({'selected':'selected'});
                                   $(this).removeAttr('selected');
                                }
                            }
                            else{
                                switchLocation(self.settings, selectedValue);
                            }
                        }
                        else{
                            self.selectedLocation = undefined;

                            if( $(locationSettings).is(":visible")){
                                 locationSettings.hide();
                            }
                        }
                    }
                });
            }
            else{
                self.selectedLocation = self.settings.locations[0];
                self.selectedLocation.addedHours = [];
                self.selectedLocation.deletedHours = [];
                self.selectedLocation.editedHours = [];

                self.SetupAddHoursButtons(self.selectedLocation);

                if( $(locationSettings).is(":hidden")){
                     locationSettings.show();
                }

                var locationsDropdown = self.root.find(`[data-element='locations']`);
                locationsDropdown.hide();
            }

            self.SetupWidgetSettings();
        }

        function switchLocation(dataPayload, selectedValue){
           self.selectedLocation = $.grep(dataPayload.locations, function(item){
               return item.id == selectedValue;
           })[0];

           self.selectedLocation.appointmentTimes = $.map(self.selectedLocation.originalData, function(item){ return item; });

           self.selectedLocation.addedHours = [];
           self.selectedLocation.deletedHours = [];
           self.selectedLocation.editedHours = [];

           self.SetupAddHoursButtons(self.selectedLocation);

           if( $(locationSettings).is(":hidden")){
                locationSettings.show();
           }

           self.SetupWidgetSettings(self.selectedLocation);

            let saveBtnWrapper = $(self.root).find(`[data-zen-component='save-widget-settings-wrapper']`);
            saveBtnWrapper.hide();
        }
    }

    SaveSettings(){
        let self = this;

        let settingsToSave = {
            "locationId" : self.selectedLocation.id,
            "appointmentTimes" : self.selectedLocation.appointmentTimes,
            "page": data.page,
            "elementId": data.elementId,
            "widgetId": data.widgetId,
            "version": data.widgetVersion,
            "hidden": self.selectedLocation.hidden
        };

        let saveSettingsUrl = `${zenogreApiBaseUrl}/settings/${clientId}/appointmentWidgetSettings`;

        let settingsTrigger = self.root.find("[data-element='settingsTriger']");

        jQuery.ajax({
            method: "POST",
            url: saveSettingsUrl,
            headers: {
             'Content-Type': 'application/json',
             'zw-client' : clientId,
            },
            data: JSON.stringify(settingsToSave),

            success: function () {

                location.reload(true)
            }
        });
    }
}

var use24h = (function(){

    if(data.config.ddHoursFormat === "24h"){
    	return true;
    }
    else {
        return false;
    }
})();

function toggleElementVisibility(element){
    if($(element).is(":visible")){
        $(element).hide();
    }
    else{
        $appointmentWrapper.show();
    }
}

function get24hfrom12h(ampmHour){
    if(ampmHour.toLowerCase().includes("am") || ampmHour.toLowerCase().includes("pm")){
       	var time = ampmHour;
	    var hours = Number(time.match(/^(\d+)/)[1]);
	    var minutes = Number(time.match(/:(\d+)/)[1]);
	    var AMPM = time.match(/\s(.*)$/)[1];
	    if(AMPM.toLowerCase() == "pm" && hours<12) hours = hours+12;
	    if(AMPM.toLowerCase() == "am" && hours==12) hours = hours-12;
	    var sHours = hours.toString();
	    var sMinutes = minutes.toString();
	    if(hours<10) sHours = "0" + sHours;
	    if(minutes<10) sMinutes = "0" + sMinutes;

        return (sHours + ":" + sMinutes);
    }
    else{
        return ampmHour;
    }
}

function get12hFrom24h(time){
    // Check correct time format and split into components
    let result = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

    if (result.length > 1) { // If time format correct
      result = result.slice (1);  // Remove full string match value
      result[5] = +result[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
      result[0] = +result[0] % 12 || 12; // Adjust hours
    }

    return result.join ('');
}

function formatTimeForDisplay(time){

    if(use24h){
       return get24hfrom12h(time);
    }
    else{
        return get12hFrom24h(time);
    }
}

let displayAppointmentComponent = new ZenogreAppointmentComponent({
    data: data,
    element: element,
    api: api,
    root: $(element).find('[data-zen-component="appointment"]')[0],
});

$(element).find("select").on("change",function(e){
    if(this.name==="deliveryOptions"){
         let selectedValue = $(this).find('option:selected').val();

        if(selectedValue !== `initial`){
            $(this).removeClass("not-selected");
        }
        else{
            $(this).addClass("not-selected");
        }
    }
    if(this.name==="firstChoiceTimeList"){
        $(this).removeClass("not-selected");
    }
    if(this.name==="secondChoiceTimeList"){
        $(this).removeClass("not-selected");
    }
    if(this.name==="locations"){
        $(this).removeClass("not-selected");
    }
});

function generateOptions(inputsList){
    for(let i = 0; i < inputsList.length; i++) {
        let el = inputsList[i];
        let dataName = `[data-zen-element="custom-field-${i}"]`;

        if(el.inputType == "radio" && el.inputRadioOptions){
            let radioOptions = el.inputRadioOptions.split(',');
            const customFieldsGroup = $(element).find(dataName)[0];
            const radioWrapper = customFieldsGroup.getElementsByTagName("fieldset")[0];
            radioOptions.forEach((opt) => {
                let labelValue = opt.trim();
                let inputValue = labelValue.replace(/(?:^\w|[A-Z]|\b\w)/g, function(word, index) { return index === 0 ? word.toLowerCase() : word.toUpperCase(); }).replace(/\s+/g, '');
                radioWrapper.innerHTML += `<input id="${inputValue}-${i}" name="options-group-${i}" type="radio" value="${labelValue}"/>
                                           <label for="${inputValue}-${i}">${labelValue}</label><br/>`;
            });

            let inputDOM = $(element).find(dataName)[0].getElementsByTagName('input')[0];
            if(inputDOM && el.inputRequired !== undefined){
                if(el.inputRequired){
                    inputDOM.setAttribute("required", "required");
                    inputDOM.dataset.validationText = el.inputValidationText;
                    $(radioWrapper).parent().find('.legend').addClass('required');
                }
                else if(inputDOM.hasAttribute("required")){
                    inputDOM.removeAttribute("required");
                    inputDOM.removeAttribute("data-validation-text");
                    $(radioWrapper).parent().find('.legend').removeClass('required');
                }
            }
        }
        else if(el.inputType == "dropdown" && el.inputDropdownOptions){
            let dropdownOptions =  el.inputDropdownOptions.split(',');
            const dropdownWrapper = $(element).find(dataName)[0].getElementsByTagName("select")[0];
            dropdownOptions.forEach((opt) => {
                let labelValue = opt.trim();

                dropdownWrapper.innerHTML += `<option value="${labelValue}">${labelValue}</option>`;
            });
        }
    }
}

function getCustomInputs(inputs) {
    let filledInputs = [];
    Array.from(inputs).forEach((i) => {

        let inputType = i.dataset.zenElementType;
        let label, inputValue = "";
        label = i.getElementsByClassName("legend")[0]?.innerHTML;

        switch (inputType) {
          case "radio":
            {
              let radio = i.querySelectorAll("input");
              Array.from(radio).forEach((r) => {
                if (r.checked) {
                  inputValue = r.value;
                }
              });
            }
            break;
          case "dropdown":
            {
              let dropdown = i.getElementsByTagName("select").customDropdownOptions;
              Array.from(dropdown).forEach((d) => {
                if (d.selected && d.value) {
                  inputValue = d.value;
                }
              });
            }
            break;
          case "text":
            {
                let text = i.querySelector("input[name=customTextInput]");
                inputValue = text.value;
            }
            break;
          case "checkbox":
            {
              let checkbox = i.getElementsByTagName("fieldset")[0].querySelector("input");
              let label = i.getElementsByTagName("fieldset")[0].querySelector("label[name=labelCustomCheckbox]").innerHTML;
              if (checkbox.checked) {
                inputValue = "Yes";
              } else {
                inputValue = "No";
              }
            }
            break;
          default:
            console.log("The input type is not supported!");
        }
        if (inputValue !== "") {
            filledInputs.push({ key: label, value: inputValue})
        }
    });
  return filledInputs;
}

function isNullOrUndefined(value) {
    return value === null || typeof value === "undefined";
}

function getCookie(name) {
    var cookieArr = document.cookie.split(";");

    for(var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");
        if(name == cookiePair[0].trim()) {
            return decodeURIComponent(cookiePair[1]);
        }
    }
    return "";
}
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
