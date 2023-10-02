(function($) {

	
	$( document ).ready(function() {
		function display_time(){
		var today = new Date();
			var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
			var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
			var dateTime = date+' '+time;
			console.log("Time: "+dateTime);
	}
		$( document ).on( 'heartbeat-send', function ( event, data ) {

			if($("#wklw_is_online").val()==1){
				data.wklw_update_last_heartbeat_user_id = wklw_data.user_id;
				console.log("user online sending data to update");
			}else{
				console.log("user offline don't do anything");
			}
			display_time();
			


		} );

	});
	


})( jQuery );