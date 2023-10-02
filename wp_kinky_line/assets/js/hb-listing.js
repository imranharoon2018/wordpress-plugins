(function($) {

	
	$( document ).ready(function() {
		function display_time(){
		var today = new Date();
			var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
			var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
			var dateTime = date+' '+time;
			// console.log("Time: "+dateTime);
	}
		$( document ).on( 'heartbeat-send', function ( event, data ) {
		
			data.wklw_get_online_user_ids = true;
			
			display_time();
			
			

		} );
		function remove_blocked_user(blocked_user_ids){
			
		}
		function update_online_element(callme_now_id,container_id,user_id){
			
			if( $("#"+container_id).length ){
					$("#"+callme_now_id).removeClass("wklw_callme_now_offline")
					$("#"+callme_now_id).addClass("wklw_callme_now_online");
					$("#"+callme_now_id).html(	wklw_listing_data.msg_call_me_now);
				}else{
					var num_children=$( ".wklw_profile_row" ).last().children().length;
					
					var str_parent_start="";
					var str_parent_end="";
					var str_element = "";
					if(num_children>5){
						
						str_parent_start = "<div class='wp-block-columns is-style-default wklw_profile_row'>";
						str_parent_end = '</div>';
					}
					var data = {
						'action': 'wklw_get_user_by_id',
						'wklw_user_id':user_id
						
					};
					
					jQuery.post(wklw_listing_data.ajax_url, data, function(response) {
						
						if('user' in  response)
							if(response.user!=null){
							
								
								var avatar_url = 'avatar_url' in  response.user? response.user.avatar_url:'';
								var display_name = 'display_name' in  response.user? response.user.display_name:'&nbsp;';
								var routing_no = 'routing_no' in  response.user? response.user.routing_no:'\u00A0  '; 
								var online_status = 'online_status' in  response.user? response.user.online_status:'';  
								var call_rate = 'call_rate' in  response.user? response.user.call_rate:'\u00A0';  
								var msg =   online_status =="online"? wklw_listing_data.msg_call_me_now :wklw_listing_data.msg_offline
								str_element += "<div class='wp-block-columns is-style-default wklw_item_container wklw_profile' id='item_container_"+user_id+"'>";
								str_element += "<div class='wp-block-column' id='wp-block-column_"+user_id+"'>";
								str_element += "<figure class='wp-block-image size-large'>";
							
							
								str_element +="<img   src='"+avatar_url+"' class='wklw_avatar' />"      
									
								str_element +="</figure>";
								str_element +="<h2 class='has-text-align-center'>"+display_name+"</h2>";
								str_element +="<p class='has-text-align-center'>"+routing_no+"</p>";
								str_element +="<p id='callme_now_"+user_id+"'  class='has-text-align-center has-white-color has-text-color has-background  wklw_callme_now_"+online_status+"' >"+msg+"</p>";
								str_element +="<p class='has-text-align-center' style='font-size:12px'>"+call_rate+"€/Min. a.d. Dt. Festnetz / Mobil abweichend</p>";
								
								str_element +="</div>";
								str_element +="</div>";
								if($( ".wklw_profile_row" ).length>0){
									var num_children=$( ".wklw_profile_row" ).last().children().length;
									if(num_children>=5){
											// alert("here 82")
											var str_parent_start = "<div class='wp-block-columns is-style-default wklw_profile_row'>";
											var str_parent_end = '</div>';
											$( ".wklw_profile_row" ).first().parent().append(str_parent_start+str_element+str_parent_end);
									}else{
										$( ".wklw_profile_row" ).last().append(str_element);
										// alert("here 88")
									}
								}else{
									// alert("here 91")
									var str_parent_start = "<div class='wp-block-columns is-style-default wklw_profile_row'>";
									var str_parent_end = '</div>';
									$( "#wklw_profile_row_parent" ).append(str_parent_start+str_element+str_parent_end)
								}
						
							}
					});
					

				
				}
			
		}
		function check_for_blocked_users(wklw_blocked_user_ids){
			if(!wklw_blocked_user_ids)
				return;
			console.log(wklw_blocked_user_ids);
			console.log(Array.isArray(wklw_blocked_user_ids));
			console.log(wklw_blocked_user_ids.length);
			if(Array.isArray(wklw_blocked_user_ids))
				for(var i=0;i<wklw_blocked_user_ids.length;i++){
					var container_id = "item_container_"+wklw_blocked_user_ids[i];
					
					$("#"+container_id).remove();
				}
			
		}
		// update_online_element("callme_now_6","item_container_6",6);
		jQuery( document ).on( 'heartbeat-tick', function ( event, data ) {
		
			// if( 'wklw_blocked_user_ids' in data)
				// check_for_blocked_users(data.wklw_blocked_user_ids );
			if ( ! data.wklw_online_user_ids ) {
			return;
			}
			// wklw_listing_data.online_user_ids 
		
			
			let difference = wklw_listing_data.online_user_ids.filter(x => !data.wklw_online_user_ids.includes(x));
			for(var i=0;i< difference.length;i++){
				var id = "callme_now_"+difference[i]
				$("#"+id).removeClass("wklw_callme_now_online")
				$("#"+id).addClass("wklw_callme_now_offline");
				$("#"+id).html(wklw_listing_data.msg_offline);
			}
			wklw_listing_data.online_user_ids = []
			for(var i=0;i<data.wklw_online_user_ids.length ;i++){

				wklw_listing_data.online_user_ids.push( data.wklw_online_user_ids[i]) 
				var callme_now_id = "callme_now_"+data.wklw_online_user_ids[i]
				var container_id = "item_container_"+data.wklw_online_user_ids[i];
				update_online_element(callme_now_id ,container_id,data.wklw_online_user_ids[i]);
			}
			
			display_time();
		} );
			

	});
	


})( jQuery );