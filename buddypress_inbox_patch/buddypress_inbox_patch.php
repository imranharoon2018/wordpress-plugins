<?php
/**
 * @package buddypress_inbox_patch
 * @version 1
 */
/*
Plugin Name: buddypress_inbox_patch
Plugin URI: www.google.com
Description: This is a patch for buddypress inbxo scroll.
Author: imran
Version: 1.0.0
Author URI: http://www.google.com
*/

add_action('wp_footer',function(){
	
	?>
		<script>
		(function($) {
			$( document ).ready(function() {
				// let inbox_element_id = "message-threads";
				let inbox_element_id = "bp-message-thread-list";
				$('#'+inbox_element_id).on('DOMSubtreeModified', function(event) {
					// dos tuff
					$('#'+inbox_element_id).animate({ scrollTop: $('#'+inbox_element_id)[0].scrollHeight},0);
				});
				console.log("ready")
			});
		})( jQuery );
		
		
		
		</script>
	<?php
	
}) 
?>