(function($) {


	$( document ).ready(function() {
		
		 $('#wklw_avatar').on('change', function() {
			 
			 // console.log(this.files[0].size);
			 const size =this.files[0].size;
			 const fileExtension = ['jpeg', 'jpg', 'png'];
			 let msg = ""
			 const msg_id = $(this).attr("id")+"_msg"
			 if( size > wklw_dashboard.max_file_size){
				console.log($(this).attr("id"));
				
				// $(this).val('');
				// $("#"+msg_id).html("File is too big");
				msg += wklw_data.msg_file_size;
				
					
			 }else{
				 // $("#"+msg_id).html("");
			 }
			  
			if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
				msg +=	wklw_data.msg_file_type;
			}
			if(msg){
				$("#"+msg_id).html(msg);
				$(this).val('');
			}else{
				$("#"+msg_id).html("");
			}
				 
			
        });	
	
	});


})( jQuery );