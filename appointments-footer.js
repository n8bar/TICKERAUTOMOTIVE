(function() {
    if (!window.location.search) {
        return;
    }
	const cleanParams = window.location.search.substring(1); // Strip ?
	const queryParams = cleanParams.split('&');

	const expires = 'expires=' + new Date().getTime() + 24 * 60 * 60 * 1000;
	const domain = 'domain=' + window.location.hostname;
	const path = "path%3d/index.html";

	queryParams.forEach((param) => {
		const [key, value = ''] = param.split('=');
		if (key.startsWith('utm_')) {
			const cookieName = "_dm_rt_" + key.substring(4);
			const cookie = cookieName + "=" + value;
			const joined = [cookie, expires, domain, path].join(";");
			document.cookie = joined;
		}
	});
}());

var _dm_gaq = {};
var _gaq = _gaq || [];
var _dm_insite = [];

;(function(p,l,o,w,i,n,g){if(!p[i]){p.GlobalSnowplowNamespace=p.GlobalSnowplowNamespace||[];
p.GlobalSnowplowNamespace.push(i);p[i]=function(){(p[i].q=p[i].q||[]).push(arguments)
};p[i].q=p[i].q||[];n=l.createElement(o);g=l.getElementsByTagName(o)[0];n.async=1;
n.src=w;g.parentNode.insertBefore(n,g)}}(window,document,"script","../d32hwlnfiv2gyn.cloudfront.net/sp-2.0.0-dm-0.1.min.js","snowplow"));
window.dmsnowplow  = window.snowplow;

dmsnowplow('newTracker', 'cf', 'd32hwlnfiv2gyn.cloudfront.net', { // Initialise a tracker
  appId: 'fd5deb14'
});

// snowplow queries element styles so we wait until CSS calculations are done.
requestAnimationFrame(() => {
	dmsnowplow('trackPageView');
	_dm_insite.forEach((rule) => {
		// Specifically in popup only the client knows if it is shown or not so we don't always want to track its impression here
		// the tracking is in popup.js
		if (rule.actionName !== "popup") {
			dmsnowplow('trackStructEvent', 'insite', 'impression', rule.ruleType, rule.ruleId);
		}
		window?.waitForDeferred?.('dmAjax', () => {
			$.DM.events.trigger('event-ruleTriggered', {value: rule});
		});
	});
});

window?.waitForDeferred?.('dmAjax', () => {
	// Collects client data and updates cookies used by smart sites
	window.expireDays = 365;
	window.visitLength = 30 * 60000;
	$.setCookie("dm_timezone_offset", (new Date()).getTimezoneOffset(), window.expireDays);
		setSmartSiteCookiesInternal("dm_this_page_view","dm_last_page_view","dm_total_visits","dm_last_visit");
});

Parameters.NavigationAreaParams.MoreButtonText = 'MORE';

Parameters.NavigationAreaParams.LessButtonText = 'LESS';
Parameters.HomeLinkText = 'Home';
