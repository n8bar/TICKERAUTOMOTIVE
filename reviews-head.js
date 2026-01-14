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
    InitialPageAlias: 'reviews',
    InitialPageUuid: '64825817d5904231b9a7ce8be9bb31cc',
    InitialPageId: '1157175292',
    InitialEncodedPageAlias: 'cmV2aWV3cw==',
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

    window.customWidgetsFunctions["5c7533db59034a958464278c6b0ac552~28"] = function (element, data, api) {
        let zenogreApiBaseUrl = 'https://zapi.kukui.com/api/v1';
let clientId = dmAPI.getSiteExternalId();

if(clientId == null){
    clientId = '00000000-0000-0000-0000-000000000000';
}

let widgetSettings;
let settingsPanel;

class ZenogreDisplayReviewsWidget {
    constructor(options = {}) {

        var self = this;

        // Internal settings mapped from options object
        self.settings = {};

        //Controls
        self.root = $(options.root);
        self.showMoreButton = $(options.showMoreButton);
        self.locations = $(options.locations);
        self.message = $(options.message);
        self.authorDropdownValue = options.authorDropdownValue;
        self.reviewsCountDropdownValue = options.reviewsCountDropdownValue;
        self.pageNumber = 1;
        self.totalPages = 0;
        self.showLocationName = false;
        self.singleLocationName = "";
        self.element = options.element;
        self.data = options.data;

        // Component internal data state
        self.data = {};

        self.showMoreButton.hide();

          jQuery.ajax({
            method: "GET",
            url: `${zenogreApiBaseUrl}/settings/${clientId}/displayReviewsWidgetsettings?page=${data.page}&elementid=${data.elementId}&widgetId=${data.widgetId}`,
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
                    $(self.element).find('[data-zen-component="loading-failed"]').addClass('textbox loading-failed');
                }
                else{
                    if(!notHiddenLocations.length){
                        $(self.element).prepend('<div data-zen-component="no-visible-locations" class="loading-failed" style="margin-bottom: 1rem;">All available locations are set as hidden. To set a location to be visible in the widget, please uncheck the "Hide Location from Display Reviews Widget" checkbox in the Settings panel.</div>');
                    } else{
                        self.getLocations();
                    }

                    if(data.inEditor && settingsPanel === undefined){
                        settingsPanel = new ZenogreSettingsComponent({
                            root: self.element
                        });
                    }
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $(self.element).find('[data-zen-component="loading-failed"]').show();
                $(self.element).find('[data-zen-component="loading-failed"]').addClass('loading-failed');
            }
        });

        // Setup styles
        $(self.locations).on('focus', function(e) {
            $(this).attr('style', `
                color: ${data.config.selectLocationColorOnFocus}!important;
                background-color: ${data.config.selectLocationBgColorOnFocus}!important;
                border-color: ${data.config.selectLocationBorderColorOnFocus}!important;
                outline:none!important;
                box-shadow:none!important;
            `);
        });

        $(self.locations).on('blur', function(e) {
            $(this).removeAttr("style");
        })

        $(self.showMoreButton).on('click', function (e) {
            self.showMoreButton.hide();
            self.pageNumber ++;
            self.getReviews();
        });
    }

    getLocations() {
        var self = this;

        let notHiddenLocations = widgetSettings.locations.filter(function(location) {
          return location.hidden !== true;
        });

        if(notHiddenLocations.length == 0){
             $(element).find('[data-zen-component="no-data"]').show();
             $(element).find('[data-zen-component="no-data"]').addClass('no-data');

        }

        self.data.selectedLocation = "all";

        if(notHiddenLocations.length > 1){

        	notHiddenLocations.forEach((location)=>{
                $(self.locations).append(`<option value="${location.id}">${location.name}</option>`);
            });

        	$(self.locations).change(function(){
                $(self.root).empty();
                self.data.selectedLocation = $(this).val();
                self.pageNumber = 1;
                self.getReviews();
            });

            self.showLocationName = true;
            $(element).find('.locations-wrapper').show();
        }
        else{
            self.singleLocationName = notHiddenLocations[0].name;
            self.data.selectedLocation = notHiddenLocations[0].id;
        }

        $(self.element).find('[data-zen-component="display-reviews-main"]').show();

        self.getReviews();
    }

    //Methods
    getReviews(){
        let self = this;
        let filterByLocationParam = self.data.selectedLocation !== "all" ? "&locationId=" + self.data.selectedLocation: "";

        jQuery.ajax({
            method: "GET",
            url: `${zenogreApiBaseUrl}/clients/${clientId}/reviews?pageNumber=${self.pageNumber}&pageSize=${self.reviewsCountDropdownValue}${filterByLocationParam}`,
            headers: {"zw-client" : clientId},
            success: function (data) {
                if(!data.reviews.length){
                    $(element).find('[data-zen-component="no-data"]').show();
                    $(element).find('[data-zen-component="no-data"]').addClass('no-data');
                }
                else{
                    self.totalPages = data.pagination.totalPages;
                    self.displayReviews(data.reviews);

                    if(self.pageNumber < self.totalPages)
                    {
                      self.showMoreButton.show();
                    }
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log("Error occured while getting the data.");
            }
        })
    }

    displayReviews(reviews){
        let self = this;
        $(reviews).each(function(index, el){
            self.createReview(el, self.root, self.authorDropdownValue);
        });
    }

    createReview(review, parentElement, authorDropdownValue){
        let self = this;

        let reviewElement = $('<div class="review"></div>').appendTo(parentElement);
        let reviewInformation = $('<div class="information"></div>').appendTo(reviewElement);
        let reviewContent = $('<div class="content"></div>').appendTo(reviewElement);
        let reviewAuthor = $(`<div class="author"><span>${this.getReviewAuthor(review, authorDropdownValue)}</span></div>`).appendTo(reviewInformation);
        let reviewDate = $(`<div class="date"><span>${review.publishedDate}</span></div>`).appendTo(reviewInformation);
        let reviewRating = $(`<div class="rating"><div class="rating-wrapper">${this.getRatingHtml(review.rating)}</div></div>`).appendTo(reviewInformation);
        let reviewOpinion = $(`<div class="opinion"><p>${review.content}</p></div>`).appendTo(reviewContent);

        if(review.reply){
            let reviewReply = $(`<div class="reply"><p>${review.reply}</p><div class="respondent">- ${self.singleLocationName ? self.singleLocationName : review.locationName}</div></div>`).appendTo(reviewContent);
        }

        if(self.showLocationName){
            let locationInfo = $(`<div class="location-info"><p>${review.locationName}</p></div>`).prependTo(reviewContent);
        }
    }

    getReviewAuthor(review, authorDropdownValue){
        let name = "Anonymous";

        if (this.isNullOrWhitespace(review.firstName) && this.isNullOrWhitespace(review.lastName)) {
            return name;
        }

        review.firstName = !this.isNullOrWhitespace(review.firstName) ? review.firstName : "";
        review.lastName = !this.isNullOrWhitespace(review.lastName) ? review.lastName : "";

        switch(authorDropdownValue) {
            case "FullName":
                name = `${review.firstName} ${review.lastName}`.trim();
                break;
            case "InitialsOnly":
                name = `${!this.isNullOrWhitespace(review.firstName) ? `${review.firstName.charAt(0)}.` : ""} ${!this.isNullOrWhitespace(review.lastName) ? `${review.lastName.charAt(0)}.` : ""}`.trim();
                break;
            case "FirstNameLastInitial":
                name = `${review.firstName} ${!this.isNullOrWhitespace(review.lastName) ? `${review.lastName.charAt(0)}.` : ""}`.trim();
                break;
            case "FirstInitialLastName":
                name = `${!this.isNullOrWhitespace(review.firstName) ? `${review.firstName.charAt(0)}.`: ""} ${review.lastName}`.trim();
                break;
        }

        return name;
    }

    getRatingHtml(rating){
        let ratingHtml = "";
        let starClass;

        for (let i = 0; i < 5; i++) {
            starClass = "star";

            if(rating > i){
                starClass += " full";

                if(rating < (i+1) && rating%1 === 0.5){
                    starClass += " half";
                }
            }

            ratingHtml += `<span class="${starClass}">&#x2605;</span>`
        }

        return ratingHtml;
    }

    isNullOrWhitespace( input ) {

        if (typeof(input) === 'undefined' || input === null) return true;

        return input.replace(/\s/g, '').length < 1;
    }
}

class ZenogreSettingsComponent{
    constructor(options = {}){

        let self = this;

        self.root = $(options.root);

        self.Init();

        self.updated = false;
    }

     Init(){
        let self = this;

        self.root.prepend("<div data-element='settingsTriger' class='settings-trigger'>Settings</div>");
        $(`
                <div data-element="settings" class="settings" style="display:none;">
                <h2>Settings</h2>
                <div data-element="locations" class="locations"></div>
                <div class="save-btn-wrapper" data-zen-component='save-widget-settings-wrapper' hidden>
                    <a title="Save changes" class="widget-button success-btn" href='javascript:void(0);' data-zen-element='save-widget-settings'><span class="text">Save Changes</span></a>
                </div>
            </div>
        `).insertAfter($(".settings-trigger"));

        let $settingsTrigger = self.root.find("[data-element='settingsTriger']");
        let $saveSettings = self.root.find("[data-zen-element='save-widget-settings']");
        let $settings = self.root.find("[data-element='settings']");
        let $displayReviewsWrapper = self.root.find("[data-zen-component='display-reviews-wrapper']");
        let noVisibleLocationsWarning = self.root.find("[data-zen-component='no-visible-locations']")[0];

        $settingsTrigger.on('click', function(){
           $settings.toggle();
           $settingsTrigger.toggleClass("visible");

           if($displayReviewsWrapper.is(":visible")){
               $displayReviewsWrapper.hide();
           }
           else{
               $displayReviewsWrapper.show();
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

       self.LoadData();
    }

    SetupWidgetSettings(){
        let self = this;

        let hiddenCheckboxes = Array.from(self.root.find("[data-zen-element='hide-location']"));

        hiddenCheckboxes.forEach((hiddenCheckbox) => {
                $(hiddenCheckbox).on('click', function() {
                $(this).toggleClass("checked");
                let saveSettingsButton = self.root.find("[data-zen-element='save-widget-settings']");
                $(saveSettingsButton).show();
                let saveBtnWrapper = self.root.find(`[data-zen-component='save-widget-settings-wrapper']`);
                saveBtnWrapper.show();
            });
        });

    }

    LoadData(){
        let self = this;

        self.settings = JSON.parse(JSON.stringify(widgetSettings));

        let settingsContainer = $(self.root).find('[data-element="settings"]')[0];

        let locationsContainer = $(self.root).find('[data-element="locations"]')[0];

        if(self.settings.locations !== undefined){

            self.settings.locations.forEach((location) => {
                let locationContent = `<div id=${location.id} data-element="location-settings">
                    <h4>${location.name}</h4>
                    <div data-zen-element="locationSettings">
                        <div>
                            <div class="checkbox${location.hidden ? " checked" : ""}" data-input="hideLocation" data-zen-element="hide-location" id="hideLocation">Hide Location from the Display Reviews Widget</div>
                        </div>
                    </div>
                </div>`;

                $(locationsContainer).append(locationContent);
            });

            $(locationsContainer).append("<div><small>* The setting affects only this widget instance</small></div>");

            self.SetupWidgetSettings();
        }
    }

    SaveSettings(){
        let self = this;
        let locationsSaveData = [];

        let locationsSettings = Array.from($(self.root).find('[data-element="location-settings"]'));

        locationsSettings.forEach((locationSettings) => {
            let locationCheckbox = $(locationSettings).find('[ data-zen-element="hide-location"]')[0];

            let location = { "locationId" : $(locationSettings)[0].id, "hidden": $(locationCheckbox).hasClass('checked')};

            locationsSaveData.push(location);

        });

        let settingsToSave = {
            "locations" : locationsSaveData,
            "page": data.page,
            "elementId": data.elementId,
            "widgetId": data.widgetId,
            "version": data.widgetVersion,
        };

        let saveSettingsUrl = `${zenogreApiBaseUrl}/settings/${clientId}/displayReviewsWidgetSettings`;

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

new ZenogreDisplayReviewsWidget({ root: $(element).find('[data-zen-component="display-reviews"]')[0],
                                  showMoreButton:  $(element).find('[data-zen-element="display-more-reviews"]')[0],
                                  locations:  $(element).find('[data-zen-element="locations"]')[0],
                                  message: $(element).find('[data-zen-element="message"]')[0],
                                  authorDropdownValue: data.config.authorDropdown,
                                  element: element,
                                  data: data,
                                  reviewsCountDropdownValue: data.config.reviewsCountDropdown });
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

var d_version = "production_4999";
var build = "2024-12-09T13_48_33";
window['v' + 'ersion'] = d_version;

function buildEditorParent() {
    window.isMultiScreen = true;
    window.editorParent = {};
    window.previewParent = {};
    window.assetsCacheQueryParam = "?version=2024-12-09T13_48_33";
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
