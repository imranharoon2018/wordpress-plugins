
			
			(function($) {
			
				
				$( document ).ready(function() {
					
					
					$( '.p_u_r_s_open_chat_window' ).click(function() {
						 // alert("hi "+$(this).data("user_id"));
						ps_messages.new_message($(this).data("user_id"), false,$("#dummy_test_for_chat"));
						return false;
					
					});
					
				});


			})( jQuery );