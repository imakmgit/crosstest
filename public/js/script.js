email_regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
function show_message(type, message, size, duration) {
	$.growl[type]({
		message: message,
		size: typeof(size) == 'undefined' ? 'large' : size,
		duration: typeof(duration) == 'undefined' ? '10000' : duration
	})
}
if(typeof growl_notification != 'undefined' && growl_notification) {
	show_message(growl_notification.error ? 'error' : 'notice', growl_notification.message);
}
