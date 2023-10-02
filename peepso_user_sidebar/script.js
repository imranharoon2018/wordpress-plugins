
			
			(function($) {
			
				
				$( document ).ready(function() {
				
					
					// $( '.pusw_image_link' ).click(function() {
					$("body").on("click",'.pusw_image_link',function(){
						ps_messages.new_message($(this).data("user_id"), false,$("#dummy"));
						return false;
					
					});	
					// $( '.pusw_name_link' ).click(function() {
					$("body").on("click",'.pusw_name_link',function(){	
						ps_messages.new_message($(this).data("user_id"), false,$("#dummy"));
						return false;
					
					});
					// $( '.pusw_user_list_item' ).click(function() {
					$("body").on("click",'.pusw_user_list_item',function(){	
						ps_messages.new_message($(this).data("user_id"), false,$("#dummy"));						
						return false;
					
					});
					let searching = false
					function create_html_element(elem){
						var str="";
						str += '<div class= "pusw_user_list_item"   data-user_id = "'+elem.user_id+'" >'
						str	+=		'<div class="pusw_left">'
						str	+=			'<a data-user_id = "'+elem.user_id+'" class = "pusw_image_link" href="'+elem.profile_url+'"><img class="pusw_user_img" src="'+elem.avatar_url+'" />'
									 
						str += 			'</a>'
						str +=			'<span class="'+elem.status+'_status"></span>'
						str +=			'</div>'
						str +=			'<div class="pusw_right">'
						str +=				'<a style="text-decoration:none" data-user_id = "'+elem.user_id+'"  class = "pusw_name_link"  href="'+elem.profile_url+'">'+elem.display_name+'</a>'
						str +=			'</div>'
						str +=		'</div>'
						return str;
					}
					function search(strval){
						var arr = [];
						for(var i=0;i<pusw_data.users.length;i++){
							
							if( pusw_data.users[i].display_name.toLowerCase().indexOf(strval.toLowerCase())>=0 ){
							// str += create_html_element(pusw_data.users[arr_index[i]]);
								arr.push(i)
							}
							
						}
						
						return arr;
					}
					function empty_user_list(){
						$(".pusw_user_list_item").remove()
					}
					function update_user_list(arr_index){
						empty_user_list();
						var str = ""
						for(var i=0;i<arr_index.length;i++){
							str += create_html_element(pusw_data.users[arr_index[i]]);
							
						}
						$( "#pusw_user_list_item_search" ).after( str );
						// $( str ).insertBefore( "#pusw_user_list_item_search" );
						// $( str ).insertBefore( "#pusw_user_list_item_search" );
						
					}
					function reset_user_list(){
						var arr_index = []
						for (var i=0;i<pusw_data.users.length;i++){
							arr_index.push(i);
						}
						console.log(pusw_data.users);
						console.log(arr_index);
						empty_user_list();
						update_user_list(arr_index);
					}
					function populate_user_list(){
						
					}
					 $(".pusw_user_search").on("input", function(){
						// Print entered value in a div box
						// $("#result").text($(this).val());
						// console.log($(this).val());
						var strval = $(this).val().trim();
						if(strval.length > 2){
							// if(! searching ){
								searching = true
								var arr = search(strval);
								searching = false;
								empty_user_list();
								update_user_list(arr);
							// }
							
						}
						if(strval.length == 0 ){
							reset_user_list();
						}
						
						
					});
					
				});


			})( jQuery );