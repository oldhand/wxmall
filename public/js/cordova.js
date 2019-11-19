var agent = navigator.userAgent;
if (agent.indexOf("tezan_Android") != -1) {
	document.write('<script type="text/javascript" charset="utf-8" src="/public/js/cordova.android.js"></script>');
}else if(agent.indexOf("tezan_iOS") != -1) {
	document.write('<script type="text/javascript" charset="utf-8" src="/public/js/cordova.ios.js"></script>');
}
