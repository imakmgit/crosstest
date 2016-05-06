
$('.news-teaser *').click(function() {
	window.location.href = $(this).parent().attr('data-url');
});
