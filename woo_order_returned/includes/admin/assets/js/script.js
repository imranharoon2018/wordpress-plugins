	
		(function($) {
		//sherekhan
			function call_ajax(data,call_back){
				
				jQuery.post(ajax_object.ajax_url, data, function(response) {
					
					call_back(response)		;
				
				}, "json");
					
				
			}
			 
			$( document ).ready(function() {
				$('.btn_use').click(function(event){
					
					event.preventDefault();
					var proceed = confirm("Are you sure you want to use this sku?");
					if( proceed ){
						let order_id = $(this).data("order_id")
						let sku_meta_id = $(this).data("sku_meta_id")
						let action = "mark_order_sku_as_used"
						let data  = {
							order_id:order_id,
							sku_meta_id:sku_meta_id,
							action:action ,
						}
						let use_button = "btn_use_"+order_id+"_"+sku_meta_id;
						let recycle_button = "btn_recycle_"+order_id+"_"+sku_meta_id;
						let row = "row_"+order_id+"_"+sku_meta_id;
						$("#"+use_button).prop('disabled', true);
						$("#"+recycle_button).prop('disabled', true);
						call_ajax(data,function(response){
							
							// if(!response.success){
							if( typeof response === 'object' && response!== null)
								if(response.hasOwnProperty('success') && response.success == false ){
									alert("This SKU is not available any more. Please try using another SKU");
								}	
							
							$("#"+row).remove();
						});
						
					}
					
				});
				$('.btn_recycle').click(function(event){
					
					event.preventDefault();
					var proceed = confirm("Are you sure you want to recycle this sku?");
					if( proceed ){
						let order_id = $(this).data("order_id")
						let sku_meta_id = $(this).data("sku_meta_id")
						let action = "mark_order_sku_as_recycled"
						let data  = {
							order_id:order_id,
							sku_meta_id:sku_meta_id,
							action:action ,
						}
						let use_button = "btn_use_"+order_id+"_"+sku_meta_id;
						let recycle_button = "btn_recycle_"+order_id+"_"+sku_meta_id;
						let row = "row_"+order_id+"_"+sku_meta_id;
						$("#"+use_button).prop('disabled', true);
						$("#"+recycle_button).prop('disabled', true);
						call_ajax(data,function (response){
								// if(!response.success){
								if( typeof response === 'object' && response !== null)
									if(response.hasOwnProperty('success') && response.success == false ){
										alert("This SKU is not available any more.");
									}
																
								$("#"+row).remove();
						});

					}
					
				});
				
				$('.btn_available').click(function(event){
					event.preventDefault();
					var proceed = confirm("Are you sure you want to make this SKU unavailble?");
					if( proceed ){
						
						let order_id = $(this).data("order_id")
						let sku_meta_id = $(this).data("sku_meta_id")
						let action = "mark_sku_as_not_available"
						let data  = {
							order_id:order_id,
							sku_meta_id:sku_meta_id,
							action:action ,
						}
						console.log(data);
						let btn_available = "btn_available_"+order_id+"_"+sku_meta_id;						
						let message_id = "sku_availabe_status_"+order_id+"_"+sku_meta_id;
						let recycle_button = "btn_recycle_returned_"+order_id+"_"+sku_meta_id;						
						
						call_ajax(data,function (response){
							// if(!response.success){
							if( typeof response === 'object' && response!== null)
								if(response.hasOwnProperty('success') && response.success == true ){
									$("#"+message_id).html("This SKU is Not currently Available in Store.");
									$("#"+btn_available).prop('disabled', true);
									$("#"+recycle_button).prop('disabled', true);
								
								
								}
						});
						
						
						
					}
					
					
				});
				
				$('.enable_edit_woo_product_attributes_in_line_item').click(function(event){
					event.preventDefault();
					$( "#woo_product_attributes_in_line_item" ).prop('disabled', false);
					$( "#submit_woo_product_attributes_in_line_item" ).prop('disabled', false);
					alert("hi");
				});
				$('.btn_recycle_returned').click(function(event){
					event.preventDefault();
					var proceed = confirm("Are you sure you want to recycle this sku?");
					if( proceed ){
						let order_id = $(this).data("order_id")						
						let sku_meta_id = $(this).data("sku_meta_id")						
						let action = "mark_order_sku_as_recycled"
						
						let data  = {
							order_id:order_id,
							sku_meta_id:sku_meta_id,
							action:action ,
						}
						
						console.log(data);
						let btn_available = "btn_available_"+order_id+"_"+sku_meta_id;						
						let message_id = "sku_availabe_status_"+order_id+"_"+sku_meta_id;
						let recycle_button = "btn_recycle_returned_"+order_id+"_"+sku_meta_id;
						let td_dash_icon = "td_dash_icon_"+order_id+"_"+sku_meta_id;
						
						call_ajax(data,function (response){
							if( typeof response === 'object' && response!== null)
								if(response.hasOwnProperty('success') && response.success == true ){
								
									$("#"+message_id).html("This SKU has been recycled.");
									$("#"+btn_available).prop('disabled', true);
									$("#"+recycle_button).prop('disabled', true);
									$("#"+td_dash_icon).html('<span class="dashicons dashicons-saved"></span>');
								
								}
						});
						
						
					}
					
					
				});
			});
				
		})( jQuery);;