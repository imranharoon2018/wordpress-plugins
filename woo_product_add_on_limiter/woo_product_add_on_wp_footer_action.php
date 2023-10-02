<?php
add_action('wp_ajax_load_product_quick_view_ajax',function(){
	exit();
});
add_action("wp_enqueue_scripts",function(){
	wp_register_script ( 'seamless_polyfill',trailingslashit( plugin_dir_url(__FILE__) ). 'js/seamless.js' );
	wp_enqueue_script ( 'seamless_polyfill' );
});
add_action('wp_footer',function($addon){
	// var_dump($addon);
	// exit();
	global $addon_names_for_js ;
	global $addon_max_limit_for_js;
	
	?>
<script>
	//mahmudgood
	(function($) {
		let is_submitted = false;
		function get_color_class(passed,is_submitted){
			
			if(!is_submitted)
				return "correct_msg";
			if(is_submitted && passed)
				return "error_msg";
			if(is_submitted&&!passed)
				return "correct_msg"
			return "correct_msg"
		}
	
		function my_scroll(target){
			
		
			var parent = "html, body"
			if($("html").hasClass("yith-quick-view-is-open"))
					parent = ".ps-active-y"
				 // parent = '.yith-wcqv-main'
				// parent = ".ps-container"				;
			 $(''+parent).animate({
                    scrollTop: $("#"+target).offset().top
                }, 2000);
			alert(target+"where= "+$("#"+target).offset().top-200);
			 alert(parent)
				
		}


		$( document ).ready(function() {
		$("body").on("click",".single_add_to_cart_button",function(e){

			is_submitted=true;
			var ret = true;
			var product_id=$(this).val();
			var gname = "woo_addon_limiter_target_group_names_"+product_id;
			var lname = "woo_addon_limiter_target_group_lengths_"+product_id;
			try {
				var error_where= null;
				var groups = $("#"+gname).val()
				var lengths = $("#"+lname).val()
				if( groups !== undefined && lengths!==undefined){
					groups=groups.split(",");
					lengths=lengths.split(",");
					for(var i=0;i<groups.length&&i<lengths.length;i++){
						g=groups[i]
						l=parseInt(lengths[i],10)
						if( Number.isInteger(l)){

							num_selected_item = $("input[name='"+g+"[]']").filter(':checked').length ;
							console.log("hi>>"+$("input[name='"+g+"[]']").filter(':checked').length 	)
							console.log(num_selected_item+","+l+", "+g)
							if(num_selected_item<l){
								
								ret = false;
								if(error_where==null)	
									error_where= "msg-"+g;
							}
							
							var color_class = get_color_class(num_selected_item<l,is_submitted)
							$("#msg-"+g).removeClass("error_msg") 
							$("#msg-"+g).removeClass("correct_msg") 
							$("#msg-"+g).addClass(color_class) 
						
						}
					}
				}
			}catch(err) {
			console.log(  err.message);;
			}



				
				if(!ret){
					e.preventDefault();
					
					if(error_where!=null)
						seamless.elementScrollIntoView(document.querySelector("#"+error_where), {
							behavior: "smooth",
							block: "center",
							inline: "center",
						});						
					
					
					
				}
					
				
			});						
		});
		$( document ).ready(function() {
		
		<?php

			$idx = 0;
			foreach($addon_names_for_js as $elem){
				$msg_id= "msg-".$elem;

		?>
			$("body").on("click","input[name='<?=$elem?>[]']",function(e){

				if ($("input[name='<?=$elem?>[]']").filter(':checked').length  > <?=$addon_max_limit_for_js[$idx]?> ){
		
					$(this).prop('checked', false);
					e.preventDefault();
				}
				
				if($("input[name='<?=$elem?>[]']").filter(':checked').length  == <?=$addon_max_limit_for_js[$idx]?>){
					
					$("#<?=$msg_id?>").hide("slow");
				}
				
				if($("input[name='<?=$elem?>[]']").filter(':checked').length  < <?=$addon_max_limit_for_js[$idx]?>){
					
					$("#<?=$msg_id?>").show("slow");
				}

				var color_class = get_color_class($("input[name='<?=$elem?>[]']").filter(':checked').length  < <?=$addon_max_limit_for_js[$idx]?>,is_submitted)
				$("#<?=$msg_id?>").removeClass("error_msg") 
				$("#<?=$msg_id?>").removeClass("correct_msg") 
				$("#<?=$msg_id?>").addClass(color_class) 


			});//$("body").on("click","input[name='<?=$elem?>[]']",function(e){


			<?php
				$idx++;
			}
			?>
		});//$( document ).ready(function() {


	})( jQuery);
</script>
<?php
});