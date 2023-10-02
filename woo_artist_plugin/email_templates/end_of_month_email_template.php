<?php

function end_of_month_email_template($artist_email){
	
	
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
                                    <h1 style="text-align:inherit;font-size:30px;font-weight:300;line-height:normal;margin:0px;color:inherit;font-family:Helvetica,Roboto,Arial,sans-serif">Sales Summary for <?=date('M')?>, <?=date('y')?>
	
				 </h1>
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
                                          <?php
                                             // Get user data by user id
                                             $user = get_user_by('email','rishabsingla003@gmail.com');
                                             $name = (isset($user->display_name)) ? ($user->display_name != '') ? $user->display_name : 'Artist' : 'Artist';
                                          ?>
                                          Hey <?php echo $name; ?>,<br><br>
														<span tyle="font-size:14px">Your art store at BestOfBharat earned royalties this month. Kindly find the summary of the same attached to this email. The money will be transferred to your account soon.</span>
                                          <br><br>
                                          Thank you,<br>
                                          Team Bestofbharat
													</p>
												</div>
                                               
                                             

										</td>
                                       </tr>
                                    </table>
									
                                    <!-- End Content -->
                                 </td>
                              </tr>
                           </table>
						   <br/>
								<table width="650" cellspacing="0" cellpadding="0" border="0" align="center" id="tbl_social" style="min-width:650px"><tbody>
									<tr>
									<td align="center" id="td_fb" style="font-family:inherit;padding:15px 52px 15px 53px">

									<div style="min-height:4px"></div>
									<a href="https://www.facebook.com/Bestofbharat-740411426300822/" style="color:#3c3c3c;font-weight:normal;border:none;text-decoration:none" target="_blank">  		<img border="0" src="<?= WAP_PLUGIN_URL."assets/images/fb.png"?>" width="32" height="32" style="border:none;display:inline-block;font-size:14px;font-weight:bold;height:auto;outline:none;text-decoration:none;text-transform:capitalize;vertical-align:middle;margin-right:10px;max-width:100%;padding:5px"></a>

									<a href="https://www.instagram.com/bestofbharat/" style="color:#3c3c3c;font-weight:normal;border:none;text-decoration:none" target="_blank" >  
					
										<img border="0" src="<?=  WAP_PLUGIN_URL."assets/images/inst.png"?>" width="32" height="32" style="border:none;display:inline-block;font-size:14px;font-weight:bold;height:auto;outline:none;text-decoration:none;text-transform:capitalize;vertical-align:middle;margin-right:10px;max-width:100%;padding:5px" ></a>

									<a href="https://in.pinterest.com/bestofbharat/" style="color:#3c3c3c;font-weight:normal;border:none;text-decoration:none" target="_blank" >  
					
										<img border="0" src="<?= WAP_PLUGIN_URL."assets/images/pint.png"?>" width="32" height="32" style="border:none;display:inline-block;font-size:14px;font-weight:bold;height:auto;outline:none;text-decoration:none;text-transform:capitalize;vertical-align:middle;margin-right:10px;max-width:100%;padding:5px" /></a>

										<a href="https://www.linkedin.com/company/bestofbharatstore/" style="color:#3c3c3c;font-weight:normal;border:none;text-decoration:none" target="_blank" >  
					
										<img border="0" src="<?=  WAP_PLUGIN_URL."assets/images/link.png"?>" width="32" height="32" style="border:none;display:inline-block;font-size:14px;font-weight:bold;height:auto;outline:none;text-decoration:none;text-transform:capitalize;vertical-align:middle;margin-right:10px;max-width:100%;padding:5px" ></a>

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