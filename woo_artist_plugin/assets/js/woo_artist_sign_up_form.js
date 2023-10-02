(function($) {


	$( document ).ready(function() {

		$('#wap_artist_password, #wap_artist_re_password').on('keyup', function () {
			if ($('#wap_artist_password').val() == $('#wap_artist_re_password').val()) {
				$('#wap_artist_password_msg').html('Matching').css('color', 'green');
			} else 
				$('#wap_artist_password_msg').html('Not Matching').css('color', 'red');
		});


	});


})( jQuery );