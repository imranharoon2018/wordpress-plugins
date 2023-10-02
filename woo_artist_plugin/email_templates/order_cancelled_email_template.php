<?php

function order_cancelled_email_template($order_id,$artist_email,$artist_comission,$order_item_ids,$order_item_exists){
	$sum_subtotal = 0.0;
	$order_items= array();
	$order = wc_get_order($order_id);	
	if($order != null && is_object($order))
		$order_items = $order->get_items();
	ob_start();

	?>
<!DOCTYPE html>
<html lang="en-US">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title><?=get_bloginfo()?></title>
   </head>
   <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="padding:0;background:rgb(236,236,236)">
      <div id="wrapper" dir="ltr">
         <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
            <tr>
               <td align="center" valign="top" align="left" style="font-family:inherit;padding:15px 25px 15px 25px">
                  
                  <table width="650" cellspacing="0" cellpadding="0" border="0" align="center"  style="min-width:650px" id="template_container">
				  <tr>
                        <td align="center" valign="top">
                           <!-- Header -->
                           <table width="650" cellspacing="0" cellpadding="0" border="0" align="center"  style="min-width:650px" id="template_header">
                              <tr>
                                 <td style="font-family:inherit;padding:15px 25px 15px 25px" id="header_wrapper">
									 <a href= "<?=get_site_url()?>">
										<img border="0" src="<?=trailingslashit(plugin_dir_url(__FILE__))."assets/images/header.png"?>" width="220" height="auto" style="border:none;display:inline-block;font-size:14px;font-weight:bold;height:auto;outline:none;text-decoration:none;text-transform:capitalize;margin-right:10px;max-width:100%" >
									</a>
                                 </td>
                              </tr>
                           </table>
                           <!-- End Header -->
                        </td>
                     </tr>
                     <tr>
                        <td align="center" valign="top">
                           <!-- Header -->
                           <table width="650" cellspacing="0" cellpadding="0" border="0" align="center"  style="background-color:#000000;min-width:650px" id="template_header">
                              <tr>
                                 <td style="font-size:13px;line-height:22px;font-family:Helvetica,Roboto,Arial,sans-serif;padding:41px 25px 41px 25px;color:#ffffff"id="header_wrapper">
                                    <h1 style="text-align:inherit;font-size:30px;font-weight:300;line-height:normal;margin:0px;color:inherit;font-family:Helvetica,Roboto,Arial,sans-serif">New order: #<?=$order->get_id()?></h1>
                                 </td>
                              </tr>
                           </table>
                           <!-- End Header -->
                        </td>
                     </tr>
                     <tr>
                        <td align="center" valign="top">
                           <!-- Body -->
                           <table  width="650" cellspacing="0" cellpadding="0" border="0" align="center" style="background-color:#fff;min-width:650px" id="template_body">
                              <tr>
                                 <td valign="top" id="body_content">
                                    <!-- Content -->
                                    <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                       <tr>
                                          <td valign="top">
                                             <div id="body_content_inner">
											 <div style="min-height:10pxfont-size:13px;line-height:22px;font-family:Helvetica,Roboto,Arial,sans-serif;;color:#000000">
		
													<p style="padding-top:10px;padding-bottom:30px;">
														<span tyle="font-size:14px">The following order has been canceled</span>
													</p>
												</div>
                                               
                                                <h2 style="text-align:inherit;margin-bottom:0.83em;display:block;font-family:inherit;line-height:130%;margin:0 0 18px;font-size:18px;font-weight:700;color:#000000">
													<div style="font-family:Helvetica,Roboto,Arial,sans-serif">
				
														[Order #<?=$order->get_order_number()?>] (<?= wc_format_datetime($order->get_date_created() )?>)		
													</div>
												</h2>
                                                <div style="margin-bottom: 40px;">
                                                   <table  cellspacing="0" cellpadding="6" border="1" width="100%" style="border-collapse:separate;color:#000000;border:1px solid #f6f4f4;font-family:Helvetica,Roboto,Arial,sans-serif">
                                                      <thead>
                                                         <tr>
                                                            <th  scope="col"style="border:1px solid #e5e5e5;color:inherit;text-align:left;vertical-align:middle;padding:12px;font-size:14px;border-width:1px;border-style:solid;border-color:#f6f4f4">Product</th>
                                                            <th scope="col" style="border:1px solid #e5e5e5;color:inherit;text-align:left;vertical-align:middle;padding:12px;font-size:14px;border-width:1px;border-style:solid;border-color:#f6f4f4">Quantity</th>
                                                            <th  scope="col" style="border:1px solid #e5e5e5;color:inherit;text-align:left;vertical-align:middle;padding:12px;font-size:14px;border-width:1px;border-style:solid;border-color:#f6f4f4">Price</th>
                                                         </tr>
                                                      </thead>
                                                      <tbody>
												<?php 
													$sum_subtotal = 0.0;
													foreach($order_items as $item){
														
													$item_id = $item->get_id();
													$product = $item->get_product();
													
													if($order_item_exists[$item_id] && $product != null & is_object($product)){
														$sum_subtotal  +=  $order->get_line_subtotal($item,true);
														$image = $product->get_image(array(32,32),array(
																			'loading'=>'eager',
																			'style'=>'border:none;display:inline-block;font-size:14px;font-weight:bold;height:auto;outline:none;text-decoration:none;text-transform:capitalize;max-width:100%;vertical-align:middle;margin-right:10px;padding-top:9px;'));
														$sku           = $product->get_sku();
														$product_name           = $product->get_title();
												?>
                                                         <tr >
                                                            <th style="border:1px solid #e5e5e5;color:inherit;text-align:left;font-weight:normal;vertical-align:middle;padding:12px;font-size:14px;border-width:1px;border-style:solid;border-color:#f6f4f4;width:300px;">
                                                              	<div style="float:left;margin-bottom:5px">
																	<!--<img src="http://192.168.1.106/xx35/wp-content/uploads/2021/06/t-shirt-with-logo-1-100x100.jpg"" alt="Product image" height="50" width="50" style="border:none;display:inline-block;font-size:14px;font-weight:bold;height:auto;outline:none;text-decoration:none;text-transform:capitalize;max-width:100%;vertical-align:middle;margin-right:10px" />-->
																	<?= wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );?>
																</div>
																<div style="padding:5px 0;line-height:22px;">
																	<span>
																		<?php echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );?>
																	</span>
																	<span> (#<?=$sku?>)</span>
																	
																	<ul style="margin-top:24px;margin-left:24px;">
																	<?php
																		
																		$metas  = wap_get_item_meta($item->get_meta_data() );
																		foreach($metas as $a_meta){
																	?>
																		<li>
																			<strong><?=$a_meta["meta_key"]?>:</strong> <p style="margin:0px"><?=$a_meta["meta_value"]?></p>
																		</li>
																	<?php
																		}
																	?>
																	</ul>
																</div>
                                                            </th>
                                                            <th  style="border:1px solid #e5e5e5;color:inherit;text-align:left;font-weight:normal;vertical-align:middle;padding:12px;font-size:14px;border-width:1px;border-style:solid;border-color:#f6f4f4;width:83px;">
                                                               	<?php
																	$qty          = $item->get_quantity();
																	$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

																	if ( $refunded_qty ) {
																		$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
																	} else {
																		$qty_display = esc_html( $qty );
																	}
																	echo wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $qty_display, $item ) );
																	?>								
                                                            </th>
                                                            <th style="border:1px solid #e5e5e5;color:inherit;text-align:left;font-weight:normal;vertical-align:middle;padding:12px;font-size:14px;border-width:1px;border-style:solid;border-color:#f6f4f4">
<!--                                                              <?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>							-->
						
															  <?=wc_price($order->get_line_subtotal($item,false))?>
                                                            </th>
                                                         </tr
												<?php
														}//if($order_item_exists[$item_id] && $product != null & is_object($product)){
													}
												?>
                                                      </tbody>
                                                      <tfoot>
                                                         <tr   >
                                                            <th colspan="2" style="border:1px solid #e5e5e5;color:inherit;text-align:left;vertical-align:middle;padding:12px;font-size:14px;border-width:1px;border-style:solid;border-color:#f6f4f4;border-top-width:4px;">
                                                               Subtotal:
                                                            </th>
                                                            <th  style="border:1px solid #e5e5e5;color:inherit;font-weight:normal;text-align:left;vertical-align:middle;padding:12px;font-size:14px;border-width:1px;border-style:solid;border-color:#f6f4f4;border-top-width:4px">
                                                              <?=wc_price(number_format((float)$sum_subtotal, 2, '.', ''))?>							
                                                            </th>
                                                         </tr>
                                                         <tr >
                                                            <th colspan="2"  style="border:1px solid #e5e5e5;color:inherit;text-align:left;vertical-align:middle;padding:12px;font-size:14px;border-width:1px;border-style:solid;border-color:#f6f4f4;">
                                                               Royalty percentage:
                                                            </th>
                                                            <th  style="border:1px solid #e5e5e5;color:inherit;font-weight:normal;text-align:left;vertical-align:middle;padding:12px;font-size:14px;border-width:1px;border-style:solid;border-color:#f6f4f4;">
                                                              <?=$artist_comission?> %								
                                                            </th>
                                                         </tr>
                                                         <tr >
                                                            <th colspan="2" style="border:1px solid #e5e5e5;color:inherit;text-align:left;vertical-align:middle;padding:12px;font-size:14px;border-width:1px;border-style:solid;border-color:#f6f4f4;">
                                                               You earned:
                                                            </th>
                                                            <td style="border:1px solid #e5e5e5;color:inherit;font-weight:normal;text-align:left;vertical-align:middle;padding:12px;font-size:14px;border-width:1px;border-style:solid;border-color:#f6f4f4;">
                                                             <?php
																	$comission = (float)$artist_comission;
																	$caluclated_comission = (float)($sum_subtotal*$comission )/100.00;
																?>
																<?=wc_price(number_format((float)$caluclated_comission, 2, '.', ''))?>						
                                                            </td>
                                                         </tr>
                                                      </tfoot>
                                                   </table>
												  
                                                </div>
                                                
                                             </div>
                                          </td>
                                       </tr>
                                    </table>
									
                                    <!-- End Content -->
                                 </td>
                              </tr>
                           </table>
						   <br/>
									<table   width="650" cellspacing="0" cellpadding="0" border="0" align="center"  style="background-color:#fff;min-width:650px">
                                       <tbody>
										<tr>
											<td style="font-size:13px;line-height:22px;font-family:Helvetica,Roboto,Arial,sans-serif;padding:0px 50px 38px 50px;color:#636363"><div style="min-height:10px">
		<p style="margin:0px"><span style="font-size:14px"><br>Congratulations on the sale.</span></p>		</div>
											</td>
											</tr>
									</tbody>
								</table>
								<table width="650" cellspacing="0" cellpadding="0" border="0" align="center" id="tbl_social" style="min-width:650px"><tbody>
									<tr>
									<td align="center" id="td_fb" style="font-family:inherit;padding:15px 52px 15px 53px">

									<div style="min-height:4px"></div>
									<a href="https://www.facebook.com/Bestofbharat-740411426300822/" style="color:#3c3c3c;font-weight:normal;border:none;text-decoration:none" target="_blank">  		<img border="0" src="<?= WAP_PLUGIN_URL."assets/images/fb.png"?>" width="32" height="32" style="border:none;display:inline-block;font-size:14px;font-weight:bold;height:auto;outline:none;text-decoration:none;text-transform:capitalize;vertical-align:middle;margin-right:10px;max-width:100%;padding:5px"></a>

									<a href="https://www.instagram.com/bestofbharat/" style="color:#3c3c3c;font-weight:normal;border:none;text-decoration:none" target="_blank" >  
					
										<img border="0" src="<?= WAP_PLUGIN_URL."assets/images/inst.png"?>" width="32" height="32" style="border:none;display:inline-block;font-size:14px;font-weight:bold;height:auto;outline:none;text-decoration:none;text-transform:capitalize;vertical-align:middle;margin-right:10px;max-width:100%;padding:5px" ></a>

									<a href="https://in.pinterest.com/bestofbharat/" style="color:#3c3c3c;font-weight:normal;border:none;text-decoration:none" target="_blank" >  
					
										<img border="0" src="<?= WAP_PLUGIN_URL."assets/images/pint.png"?>" width="32" height="32" style="border:none;display:inline-block;font-size:14px;font-weight:bold;height:auto;outline:none;text-decoration:none;text-transform:capitalize;vertical-align:middle;margin-right:10px;max-width:100%;padding:5px" /></a>

										<a href="https://www.linkedin.com/company/bestofbharatstore/" style="color:#3c3c3c;font-weight:normal;border:none;text-decoration:none" target="_blank" >  
					
										<img border="0" src="<?= WAP_PLUGIN_URL."assets/images/link.png"?>" width="32" height="32" style="border:none;display:inline-block;font-size:14px;font-weight:bold;height:auto;outline:none;text-decoration:none;text-transform:capitalize;vertical-align:middle;margin-right:10px;max-width:100%;padding:5px" ></a>

					</td>
	  </tr></tbody></table>
                           <!-- End Body -->
                        </td>
                     </tr>
                  </table>
               </td>
            </tr>
            <tr>
               <td align="center" valign="top">
                  <!-- Footer -->
                  <table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">
                     <tr>
                        <td valign="top">
                           <table border="0" cellpadding="10" cellspacing="0" width="100%">
                              <tr>
                                 <td colspan="2" valign="middle" id="credit" style="font-size:13px;line-height:22px;font-family:Helvetica,Roboto,Arial,sans-serif;padding:15px 50px 15px 50px;color:#8a8a8a">
                                    <div style="min-height:10px">
		<p style="font-size:14px;margin:0px 0px 16px;text-align:center">Copyright © 2021 BestOfBharat.com, All rights reserved.</p><font color="#888888">		</font></div>
                                 </td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                  </table>
                  <!-- End Footer -->
               </td>
            </tr>
         </table>
      </div>
   </body>
</html>
	<?php
	return ob_get_clean();
	
}

?>