$('.news-teaser *').click(function() {
	window.location.href = $(this).parent().attr('data-url');
});

$('form').submit(function(event) {
	var hasError = false;
	
	if($('[name="news_title"]').val().trim() == ''){
		hasError = true;
		$('[name="news_title"]').parent().find('.error').text('News title can\'t be empty.');
	} else {
		$('[name="news_title"]').parent().find('.error').text('');
	}

	if($('[name="news_content"]').val().trim() == ''){
		hasError = true;
		$('[name="news_content"]').parent().find('.error').text('News content can\'t be empty.');
	} else {
		$('[name="news_content"]').parent().find('.error').text('');
	}

	var file_path_parts = $('[name="news_image"]').val().split('.');
	var extension = file_path_parts[file_path_parts.length - 1];
	if($.inArray(extension.toLowerCase(), ['jpg', 'jpeg', 'png', 'gif']) == -1){
		hasError = true;
		$('[name="news_image"]').parent().find('.error').text('Please upload file with jpg, jpeg, png, gif extension only.');
	} else {
		$('[name="news_image"]').parent().find('.error').text('');
	}

	if(hasError) {
		event.preventDefault();
	}
});
