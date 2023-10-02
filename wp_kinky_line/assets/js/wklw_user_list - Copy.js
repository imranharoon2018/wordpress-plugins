
			
			(function($) {
				function ajax_post(data){
					
				}
				function post_wklw_routing_no(user_id){
					
					var wklw_user_id = user_id;
					var wklw_routing_no = $("#wklw_routing_no_"+user_id).val();
					//$("#wklw_user_id_"+user_id).val();
					var span_id = "span_routing_no_"+user_id;
				
					var data = {
						'action': 'update_user_list_routing_no',
						'wklw_routing_no': wklw_routing_no    ,
						'wklw_user_id': wklw_user_id    
					};
					console.log(data);
					$.post(wklw_user_list.ajax_url, data, function(response) {
						console.log(response);
						 
						if(response.status=="used_by_other" ||response.status=="updated"  ||response.status=="added" ||response.status=="removed"  )
							alert(response.msg);
						
						 if(response.status=="updated" ||response.status=="added")
							$("#"+span_id).html(wklw_routing_no);
						 if(response.status=="removed")
							$("#"+span_id).html("");
						
					});

				}
				function post_wklw_phone_no(user_id){
					
					
					var wklw_user_id = user_id;
					var wklw_phone_no = $("#wklw_phone_no_"+user_id).val();
					//$("#wklw_user_id_"+user_id).val();
					var span_id = "span_phone_no_"+user_id;
				
					var data = {
						'action': 'update_user_list_phone_no',
						'wklw_phone_no': wklw_phone_no    ,
						'wklw_user_id': wklw_user_id    
					};
					
					console.log(data);
					$.post(wklw_user_list.ajax_url, data, function(response) {
						console.log(response);
						 
						if(response.status=="used_by_other" ||response.status=="updated" ||response.status=="added" ||response.status=="removed"  )
							alert(response.msg);
						if(response.status=="updated" ||response.status=="added")
						 $("#"+span_id).html(wklw_phone_no);
						if(response.status=="removed")
						 $("#"+span_id).html("");
						
					});

				}
				
				$( document ).ready(function() {
					
					
					$( '.wklw_phone_save' ).click(function() {
						var user_id = $(this).data("user_id");
						post_wklw_phone_no(user_id);
					});
					
					$( '.wklw_routing_save' ).click(function() {
						console.log(64);
						var user_id = $(this).data("user_id");
						
						post_wklw_routing_no(user_id);
					});
					$( '.routing_edit' ).click(function() {
						 // alert("hi "+$(this).data("user_id"));
						var user_id =$(this).data("user_id")
						var row1= "routing_row_1_"+user_id;
						var row2= "routing_row_2_"+user_id;
						$("#"+row1).hide();
						$("#"+row2).show();
						return false;
					
					});
					$( '.wklw_routing_cancel' ).click(function() {
						 // alert("hi "+$(this).data("user_id"));
						var user_id =$(this).data("user_id")
						var row1= "routing_row_1_"+user_id;
						var row2= "routing_row_2_"+user_id;
						$("#"+row1).show();
						$("#"+row2).hide();
						return false;
					
					});
					$( '.phone_edit' ).click(function() {
						 // alert("hi "+$(this).data("user_id"));
						var user_id =$(this).data("user_id")
						var row1= "phone_row_1_"+user_id;
						var row2= "phone_row_2_"+user_id;
						$("#"+row1).hide();
						$("#"+row2).show();
						return false;
					
					});
					$( '.wklw_phone_cancel' ).click(function() {
						 // alert("hi "+$(this).data("user_id"));
						var user_id =$(this).data("user_id")
						var row1= "phone_row_1_"+user_id;
						var row2= "phone_row_2_"+user_id;
						$("#"+row1).show();
						$("#"+row2).hide();
						return false;
					
					});
					
					
					
					
				});


			})( jQuery );