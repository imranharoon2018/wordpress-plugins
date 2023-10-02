<?php
	$option_name_for_page_id = array(
		'Artist Dashboard' => "wap_artist_account_dashboard_page_id",
		'Artist Upload File'=> "wap_artist_upload_file_page_id",
		'Artist Sales Statitscs' => "wap_artist_artist_sales_statistics_page_id",
		'Artist Account Information' => "wap_artist_account_information_page_id",
	);
	global $post;
	$current_post_id = $post->ID;
	$dashoard_post_id = get_option("wap_artist_account_dashboard_page_id");
	$upload_post_id = get_option("wap_artist_upload_file_page_id");
	$sales_stat_post_id = get_option("wap_artist_artist_sales_statistics_page_id");
	$account_info_post_id = get_option("wap_artist_account_information_page_id");
	
	$class_dashboard = $current_post_id == $dashoard_post_id  ? " class= 'selected' " : "";
	$class_upload_file = $current_post_id == $upload_post_id  ? " class= 'selected' " : "";
	$class_sales_statistics = $current_post_id == $sales_stat_post_id ? " class= 'selected' " : "";
	$class_account_information = $current_post_id ==$account_info_post_id ? " class= 'selected' " : "";
	
?>
<div class="wap_sidenav">
  <a <?=$class_dashboard?> href="<?=get_permalink($dashoard_post_id )?>">Dashboard</a>
  <a <?=$class_upload_file?>  href="<?=get_permalink($upload_post_id )?>">Upload File</a>
  <a <?=$class_sales_statistics?>  href="<?=get_permalink($sales_stat_post_id )?>">Sales Statistics  </a>
  <a <?=$class_account_information?>  href="<?=get_permalink($account_info_post_id )?>">Account Information </a>
  <a href="<?=wp_logout_url( get_site_url())?>">Log out</a>
</div>

