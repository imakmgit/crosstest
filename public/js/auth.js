$(document).ready(function() {
	$('#signup_form, #login_form,  #change_password_form').find('[type="text"], [type="password"]').keydown(function() {
		$(this).removeClass('error-bg');
	});
	$('#login_form').submit(function(event) {
		
		event.preventDefault();
		var url = $(this).attr('action');
		if(!email_regex.test($('#login_form').find('[name="email"]').val())) {
			show_message('error', 'Provide a valid email address');
			$(this).find('[name="email"]').addClass('error-bg').focus();
			return false;
		}
		if($('[name="password"]').val().trim() == '') {
			show_message('error', 'Password field length can\'t be less than 8 characters.');
			$(this).find('[name="password"]').addClass('error-bg').focus();
			return false;
		}
		
		$('#login_form').find('button').prop('disabled', true);
		$('#login_form').find('button').text('Please wait...');
		$.ajax({
			url: url,
			type: 'POST',
			data: $('#login_form').serialize(),
			success: function(response) {
				$('#login_form').find('button').prop('disabled', false);
				$('#login_form').find('button').text('Login');
				
				$('.growl').remove();
				if(response.error) {
					if(typeof response.message == 'object') {
						for(var element in response.message) {
							$('#login_form').find('[name="' + element + '"]').addClass('error-bg');
							show_message('error', response.message[element].join('<br/>'));
						}
					} else {
						show_message('error', response.message);
					}
				} else {
					$('#login_form').find('input').val('');
					show_message('notice', '<strong>' + response.message + '</strong>', 'large', 20000);
					window.location.href = '/dashboard'
				}
			},
			error: function(xhr, error) {
				$('#login_form').find('button').prop('disabled', false);
				$('#login_form').find('button').text('Login');
				show_message('error', xhr.statusText + '(' + xhr.status + ')');
			}
		});
		
	});

	$('#signup_form').submit(function(event) {
		event.preventDefault();
		var url = $(this).attr('action');
		if($('[name="full_name"]').val().trim() == '') {
			show_message('error', "Name field can't be empty.");
			$('[name="full_name"]').addClass('error-bg').focus();
			return false;
		}

		if(!email_regex.test($('#signup_form').find('[name="email"]').val())) {
			show_message('error', "Provide a valid email address");
			$('#signup_form').find('[name="email"]').addClass('error-bg').focus();
			return false;
		}
		$('#signup_form').find('button').prop('disabled', true);
		$('#signup_form').find('button').text('Please wait...');
		$.ajax({
			url: url,
			type: 'POST',
			data: $('#signup_form').serialize(),
			success: function(response) {
				$('#signup_form').find('button').prop('disabled', false);
				$('#signup_form').find('button').text('Register');
				
				$('.growl').remove();
				if(response.error) {
					if(typeof response.message == 'object') {
						for(var element in response.message) {
							$('#signup_form').find('[name="' + element + '"]').addClass('error-bg');
							show_message('error', response.message[element].join('<br/>'));
						}
					} else {
						show_message('error', response.message);
					}
				} else {
					$('#signup_form').find('input').val('');
					show_message('notice', '<strong>' + response.message + '</strong>', 'large', 20000);
				}
			},
			error: function(xhr, error) {
				$('#signup_form').find('button').prop('disabled', false);
				$('#signup_form').find('button').text('Register');
				show_message('error', xhr.statusText + '(' + xhr.status + ')');
			}
		})
	});

	$('#change_password_form').submit(function(event) {
		event.preventDefault();
		var url = $(this).attr('action');

		$('#change_password_form').find('button').prop('disabled', true);
		$('#change_password_form').find('button').text('Please wait...');
		$.ajax({
			url: url,
			type: 'POST',
			data: $('#change_password_form').serialize(),
			success: function(response) {
				$('#change_password_form').find('button').prop('disabled', false);
				$('#change_password_form').find('button').text($('#change_password_form').find('button').attr('data-text'));
				
				$('.growl').remove();
				if(response.error) {
					if(typeof response.message == 'object') {
						for(var element in response.message) {
							$('#change_password_form').find('[name="' + element + '"]').addClass('error-bg');
							show_message('error', response.message[element].join('<br/>'));
						}
					} else {
						show_message('error', response.message);
					}
				} else {
					$('#change_password_form').find('input').val('');
					show_message('notice', '<strong>' + response.message + '</strong>', 'large', 20000);
					setTimeout(function() {
						window.location.href = '/dashboard';
					}, 2000);
				}
			},
			error: function(xhr, error) {
				$('#change_password_form').find('button').prop('disabled', false);
				$('#change_password_form').find('button').text($('#change_password_form').find('button').attr('data-text'));
				show_message('error', xhr.statusText + '(' + xhr.status + ')');
			}
		})
	});

	$('#forgot_password_form').submit(function(event) {
		event.preventDefault();
		var url = $(this).attr('action');

		$('#forgot_password_form').find('button').prop('disabled', true);
		$('#forgot_password_form').find('button').text('Please wait...');
		$.ajax({
			url: url,
			type: 'POST',
			data: $('#forgot_password_form').serialize(),
			success: function(response) {
				$('#forgot_password_form').find('button').prop('disabled', false);
				$('#forgot_password_form').find('button').text($('#forgot_password_form').find('button').attr('data-text'));
				
				$('.growl').remove();
				if(response.error) {
					if(typeof response.message == 'object') {
						for(var element in response.message) {
							$('#forgot_password_form').find('[name="' + element + '"]').addClass('error-bg');
							show_message('error', response.message[element].join('<br/>'));
						}
					} else {
						show_message('error', response.message);
					}
				} else {
					$('#forgot_password_form').find('input').val('');
					show_message('notice', '<strong>' + response.message + '</strong>', 'large', 20000);
				}
			},
			error: function(xhr, error) {
				$('#forgot_password_form').find('button').prop('disabled', false);
				$('#forgot_password_form').find('button').text($('#forgot_password_form').find('button').attr('data-text'));
				show_message('error', xhr.statusText + '(' + xhr.status + ')');
			}
		})
	});

	$('.signin-signup').click(function(event){
		event.preventDefault();
		// Switches the Icon
		$(this).children('i').toggleClass('fa-pencil');
		// Switches the forms  
		$('.form').animate({
			height: "toggle",
			'padding-top': 'toggle',
			'padding-bottom': 'toggle',
			opacity: "toggle"
		}, "slow");
	});
});


