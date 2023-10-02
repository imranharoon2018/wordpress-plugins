
			
			(function($) {
			
				
				$( document ).ready(function() {
					
					
					$( '.p_s_a_open_chat_window' ).click(function() {
						 // alert("hi "+$(this).data("user_id"));
						ps_messages.new_message($(this).data("user_id"), false);
						return false;
					
					});
					
				});


			})( jQuery );