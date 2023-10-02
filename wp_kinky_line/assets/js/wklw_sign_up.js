(function($) {


	function checkPasswordMatch() {
		var password = $("#wklw_password").val();
		var confirmPassword = $("#wklw_retype_password").val();

		if (password != confirmPassword)
			$("#wklw_password_msg").html( wklw_signup.msg_password);
		else
			$("#wklw_password_msg").html("");
    }
	 
	function checkForm(){
		var ret = true;
		var password = $("#wklw_password").val();
		var confirmPassword = $("#wklw_retype_password").val();
		if (password != confirmPassword){
			ret = false;
			$("#wklw_password_msg").html(wklw_signup.msg_password);
		}
		return ret;
	}
	$( document ).ready(function() {
		  $("#wklw_retype_password").keyup(checkPasswordMatch);
		  $("#wklw_password").keyup(checkPasswordMatch);
		  $("#wklw_signup_form").submit(checkForm);
		 $('#wklw_avatar,#wklw_id_scan2,#wklw_id_scan1').on('change', function() {
			
			 const size =this.files[0].size;
			 const fileExtension = ['jpeg', 'jpg', 'png'];
			 let msg = ""
			 const msg_id = $(this).attr("id")+"_msg"
			 if( size > wklw_signup.max_file_size){
				
				msg += wklw_signup.msg_file_size;
			 }else{
				 // $("#"+msg_id).html("");
			 }
			  
			if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
				msg +="<br/>"+wklw_signup.msg_file_type ;
			}
			if(msg){
				$("#"+msg_id).html(msg);
				$(this).val('');
			}else{
				$("#"+msg_id).html("");
			}
				 
			 // wklw_signup.max_file_size
			/*
            const size =
               (this.files[0].size / 1024 / 1024).toFixed(2);
 
            if (size > 4 || size < 2) {
                alert("File must be between the size of 2-4 MB");
            } else {
                $("#output").html('<b>' +
                   'This file size is: ' + size + " MB" + '</b>');
            }*/
        });
		function verify_signup_form(){
			
			let error = []
			/*
			if( $('#wklw_name').val() == "" ){
				error.push({'wklw_name':'Please fill in Name'})
			}
			if( $('#wklw_surname').val() == "" ){
				error.push({'wklw_surname':'Please fill in Surname'})
			}
			if( $('#wklw_address1').val() == "" ){
				error.push({'wklw_address1':'Please fill in Adress'})
			}
			
			if( $('#wklw_id_no').val() == "" ){
				error.push({'wklw_id_no':'Please fill in Id No'})
			}
			
			if( $('#wklw_id_scan1').val() == "" ){
				error.push({'wklw_id_scan1':'Please fill in Id Scan'})
			}
			if( $('#wklw_id_scan2').val() == "" ){
				error.push({'wklw_id_scan2':'Please fill in Id Scan'})
			}
			if( $('#wklw_phone_no').val() == "" ){
				error.push({'wklw_phone_no':'Please fill in Phone No.'})
			}
			if( $('#wklw_email').val() == "" ){
				error.push({'wklw_email':'Please fill in Surname'})
			}
			
		*/
			return error;
		}

		// $('#wklw_signup_form').submit(function( event ) {
		  // alert( "Handler for .submit() called." );
		  // event.preventDefault();
		  
			
		// });
		// $('#wap_artist_password, #wap_artist_re_password').on('keyup', function () {
			// if ($('#wap_artist_password').val() == $('#wap_artist_re_password').val()) {
				// $('#wap_artist_password_msg').html('Matching').css('color', 'green');
			// } else 
				// $('#wap_artist_password_msg').html('Not Matching').css('color', 'red');
		// });


	});


})( jQuery );