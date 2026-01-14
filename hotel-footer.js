(function() {
    if (!window.location.search) {
        return;
    }
	const cleanParams = window.location.search.substring(1); // Strip ?
	const queryParams = cleanParams.split('&');

	const expires = 'expires=' + new Date().getTime() + 24 * 60 * 60 * 1000;
	const domain = 'domain=' + window.location.hostname;
	const path = "path=/";

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

//<![CDATA[
	setTimeout(() => {
		dmAPI.loadScript('https://connect.kukui.com/webchat?key=35622');
	}, 7000);
//]]>
