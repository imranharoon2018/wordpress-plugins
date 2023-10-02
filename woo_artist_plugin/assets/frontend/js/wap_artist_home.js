(function($) {
	const maxFile=5;
	const numFile=1;
	function isNumber(n) { return !isNaN(parseFloat(n)) && !isNaN(n - 0) }
	function addNewField(target,n){
		var str = '';
		str += '<div class = "wap_artist_upload_row" id ="wap_artist_upload_'+n+'">'
		str += '<div>'+n+'. File: <input type="file" name="wap_artist_art_uploadfile_'+n+'" id="wap_artist_art_uploadfile_'+n+'" class="wap_artist_art_uploadfile" /></div>'
		
		str += '</div>'
		$("#"+target).append(str);
				
				
				
			
	}
	function init_tags(arr_data){
		// var input = document.querySelector('input[class=uploaded_item_tags]'),
		var inputs = document.getElementsByClassName('uploaded_item_tags');
		for(var i=0;i<inputs.length;i++)
    tagify = new Tagify(inputs[i], {
        pattern             : /^.{0,20}$/,  // Validate typed tag(s) by Regex. Here maximum chars length is defined as "20"
        delimiters          : ",| ",        // add new tags when a comma or a space character is entered
        keepInvalidTags     : true,         // do not remove invalid tags (but keep them marked as invalid)
        editTags            : {
            clicks: 1,              // single click to edit a tag
            keepInvalid: false      // if after editing, tag is invalid, auto-revert
        },
        maxTags             : 6,      
        whitelist           : arr_data,
        // transformTag        : transformTag,
        backspace           : "edit",
        placeholder         : "Type something",
        dropdown : {
            enabled: 1,            // show suggestion after 1 typed character
            fuzzySearch: false,    // match only suggestions that starts with the typed characters
            position: 'text',      // position suggestions list next to typed text
            caseSensitive: true,   // allow adding duplicate items if their case is different
        },
        templates: {
            dropdownItemNoMatch: function(data) {
                return `<div class='${this.settings.classNames.dropdownItem}' tabindex="0" role="option">
                    No suggestion found for: <strong>${data.value}</strong>
                </div>`
            }
        }
    })
	}
	$( document ).ready(function() {
			// alert(ajax_object.ajax_url);
			var data={
				action:"wap_get_tags_to_display"
			}
			$.getJSON( ajax_object.ajax_url,data, function( data ) {
				// console.log(data);
				init_tags(data);
				// var items = [];
				// $.each( data, function( key, val ) {
				// items.push( "<li id='" + key + "'>" + val + "</li>" );
				// });

				// $( "<ul/>", {
				// "class": "my-new-list",
				// html: items.join( "" )
				// }).appendTo( "body" );
			});
		// $('#wap_artist_password, #wap_artist_re_password').on('keyup', function () {
			// if ($('#wap_artist_password').val() == $('#wap_artist_re_password').val()) {
				// $('#wap_artist_password_msg').html('Matching').css('color', 'green');
			// } else 
				// $('#wap_artist_password_msg').html('Not Matching').css('color', 'red');
		// });
		 $('#wap_artist_home_edit, #wap_artist_home_settings').click(function(event){
			$('#settings_panel').show("slow");
			 return false;
			 
		 });
		  $('#wap_artist_home_close').click(function(event){
			$('#settings_panel').hide("slow");
			 return false;
			 
		 });
		 
		 $('#wap_file_upload').click(function(event){
			$('#wap_artist_upload_form').show("slow");
			 return false;
			 
		 });
		 $("body").on("change",".wap_artist_art_uploadfile",function(e){
		 // $('.wap_artist_art_uploadfile').change(function(event){
			var id= parseInt($(this).attr("id").replace("wap_artist_art_uploadfile_",""));
			
			if(isNumber(id) && id<maxFile){
				console.log(id);
				next_id = id+1;
				if( !$("#wap_artist_art_uploadfile_"+next_id).length ){
					addNewField("wap_artist_upload_rows",next_id);
				}
				
			}
		 });
		 
		 
		 


	});


})( jQuery );