var _jquery = window.$;

var jqueryAliases = ['$', 'jquery', 'jQuery'];

jqueryAliases.forEach((alias) => {
    Object.defineProperty(window, alias, {
        get() {
            return _jquery;
        },
        set() {
            console.warn("Trying to over-write the global jquery object!");
        }
    });
});
window.jQuery.migrateMute = true;

window.popups =
[{"title":"Image credits","url":"/image-credits","options":{"backgroundColor":"#FFFFFF","borderRadius":"5","width":"612","overlayColor":"rgba(0, 0, 0, 0.5)","height":"436","animation":"fadeIn"},"name":"image-credits"}]

window.cookiesNotificationMarkupPreview = 'null';

window.INSITE = window.INSITE || {};
window.INSITE.device = "desktop";

window.rtCommonProps = {};
rtCommonProps["rt.ajax.ajaxScriptsFix"] =true;
rtCommonProps["rt.pushnotifs.sslframe.encoded"] = 'aHR0cHM6Ly97c3ViZG9tYWlufS5wdXNoLW5vdGlmcy5jb20=';
rtCommonProps["runtimecollector.url"] = 'https://rtc.multiscreensite.com/';
rtCommonProps["performance.tabletPreview.removeScroll"] = 'false';
rtCommonProps["inlineEditGrid.snap"] =true;
rtCommonProps["popup.insite.cookie.ttl"] = '0.5';
rtCommonProps["rt.pushnotifs.force.button"] =true;
rtCommonProps["common.mapbox.token"] = 'pk.eyJ1IjoiZGFubnliMTIzIiwiYSI6ImNqMGljZ256dzAwMDAycXBkdWxwbDgzeXYifQ.Ck5P-0NKPVKAZ6SH98gxxw';
rtCommonProps["common.mapbox.js.override"] =false;
rtCommonProps["common.here.appId"] = 'iYvDjIQ2quyEu0rg0hLo';
rtCommonProps["common.here.appCode"] = '1hcIxLJcbybmtBYTD9Z1UA';
rtCommonProps["isCoverage.test"] =false;
rtCommonProps["ecommerce.ecwid.script"] = '../app.multiscreenstore.com/script.html';
rtCommonProps["feature.flag.mappy.kml"] =false;
rtCommonProps["common.resources.dist.cdn"] =true;
rtCommonProps["common.build.dist.folder"] = 'production/5030';
rtCommonProps["common.resources.cdn.host"] = 'https://static.cdn-website.com/';
rtCommonProps["common.resources.folder"] = 'https://static.cdn-website.com/mnlt/production/5030';
rtCommonProps["feature.flag.runtime.backgroundSlider.preload.slowly"] =true;
rtCommonProps["feature.flag.runtime.newAnimation.enabled"] =true;
rtCommonProps["feature.flag.runtime.newAnimation.respectCssAnimationProps.enabled"] =true;
rtCommonProps["feature.flag.runtime.newAnimation.jitAnimation.enabled"] =true;
rtCommonProps["feature.flag.sites.google.analytics.gtag"] =true;
rtCommonProps["feature.flag.runOnReadyNewTask"] =true;
rtCommonProps["isAutomation.test"] =false;

rtCommonProps['common.mapsProvider'] = 'mapbox';

rtCommonProps['common.mapsProvider.version'] = '0.52.0';
rtCommonProps['common.geocodeProvider'] = 'mapbox';
rtCommonProps['common.map.defaults.radiusSize'] = '1500';
rtCommonProps['common.map.defaults.radiusBg'] = 'rgba(255, 255, 255, 0.4)';
rtCommonProps['common.map.defaults.strokeColor'] = 'rgba(255, 255, 255, 1)';
rtCommonProps['common.map.defaults.strokeSize'] = '2';
rtCommonProps['server.for.resources'] = '';
rtCommonProps['feature.flag.lazy.widgets'] = true;
rtCommonProps['feature.flag.single.wow'] = false;
rtCommonProps['feature.flag.disallowPopupsInEditor'] = true;
rtCommonProps['feature.flag.mark.anchors'] = true;
rtCommonProps['captcha.public.key'] = '6LffcBsUAAAAAMU-MYacU-6QHY4iDtUEYv_Ppwlz';
rtCommonProps['captcha.invisible.public.key'] = '6LeiWB8UAAAAAHYnVJM7_-7ap6bXCUNGiv7bBPME';
rtCommonProps["images.sizes.small"] =160;
rtCommonProps["images.sizes.mobile"] =640;
rtCommonProps["images.sizes.tablet"] =1280;
rtCommonProps["images.sizes.desktop"] =1920;
rtCommonProps["modules.resources.cdn"] =true;
rtCommonProps["import.images.storage.imageCDN"] = 'https://lirp.cdn-website.com/';
rtCommonProps["facebook.api.version"] = '7.0';
rtCommonProps["feature.flag.runtime.inp.threshold"] =150;
rtCommonProps["feature.flag.performance.logs"] =true;
rtCommonProps["site.widget.form.captcha.type"] = 'g_recaptcha';
rtCommonProps["friendly.captcha.site.key"] = 'FCMGSQG9GVNMFS8K';
rtCommonProps["cookiebot.mapbox.consent.category"] = 'marketing';
rtCommonProps["platform.monolith.personalization.dateTimeCondition.popupMsgAction.moveToclient.enabled"] =true;

// feature flags that's used out of runtime module (in  legacy files)
rtCommonProps["site.runtime.video.background.ssr"] =true;

window.rtFlags = {};
rtFlags["unsuspendEcwidStoreOnRuntime.enabled"] =true;
rtFlags["scripts.widgetCount.enabled"] =true;
rtFlags["ecom.ecwid.categoryPage.modifyLinks"] = true;
rtFlags["ecom.ecwidNewUrlStructure.enabled"] = false;
rtFlags["ecom.ecwid.old.store.fix.scrolling.enabled"] = true;
rtFlags["ecom.ecwid.old.store.fix.facebook.share"] = true;
rtFlags["ecom.ecwid.fallBackInCaseLinksNotFound.enabled"] = true;
rtFlags["feature.flag.photo.gallery.exact.size"] =true;
rtFlags["new.store.fix.ecwid.back.bug"] =true;
rtFlags["facebook.runtime.widgets.upgrade"] =true;
rtFlags["ecom.ecwid.solve.url.modifications"] = true;
rtFlags["ecom.ecwid.configOptions"] = true;
rtFlags["geocode.search.localize"] =false;
rtFlags["feature.flag.runtime.newAnimation.asyncInit.setTimeout.enabled"] =false;
rtFlags["site.contact.form.fix.for.attribute"] =true;
rtFlags["contact.form.date.format.enabled"] = true;
rtFlags["twitter.heightLimit.enabled"] = true;
rtFlags["ecom.ecwid.fixTranslations.enabled"] = true;
rtFlags["runtime.load.script.native"] =true;
rtFlags["editor.classicHybrid.photogallery.fix"] =true;
rtFlags["runtime.lottieOverflow"] =false;
rtFlags["runtime.monitoring.sentry.ignoreErrors"] = "";
rtFlags["ecom.ecwid.old.store.fix.scrolling.detect.enable"] =true;
rtFlags["contact.form.browserValidation.enabled"] =true;
rtFlags["feature.flag.notifications.push.from.top"] =false;
rtFlags["streamline.monolith.personalization.supportMultipleConditions.enabled"] =false;

$(window).bind("orientationchange", function (e) {
    $.layoutManager.initLayout();

});
$(document).resize(function () {

});

if(dmAPI.getCurrentEnvironment() === 'live'){
  //   Input tracking URL below inside of ""
    jQuery.getScript("https://tracking.kukui.com/02/Tracking/Js/7dbaa052-49e5-466b-9019-3e29a353d498");
}

window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-ME7X83EBJH');
gtag('config', 'AW-10784944369');
gtag('event', 'conversion', {'send_to': 'AW-10784944369/FmqFCPuKjvkCEPHh1JYo'});
