$('.news-info .error').click(function() {
	var result = confirm('Are you sure you want to delete this news?');
	if(result) {
		var url = $(this).attr('data-url');
		$(this).text('Deleting... Please wait...');
		$.ajax({
			url: url,
			type: 'GET',
			success: function(response) {
				
				if(response.error) {
					show_message('error', response.message);
					$('[data-url="' + this.url + '"]').text('Delete this news');
				} else {
					show_message('notice', response.message);
					$('[data-url="' + this.url + '"]').closest('.news').remove();
				}
			},
			error: function(xhr, error) {
				$('[data-url="' + this.url + '"]').text('Delete this news');
				show_message('error', response.message);
			}
		});
	}
});

