(function($) {


	$( document ).ready(function() {
		$( document ).on( 'heartbeat-send', function ( event, data ) {


var today = new Date();
var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
var dateTime = date+' '+time;
			console.log("queen: "+dateTime);

		} );

	});
	


})( jQuery );