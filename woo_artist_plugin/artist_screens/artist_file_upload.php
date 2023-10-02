
<div class="artist_account_page">
<?php 
require_once(WAP_PLUGIN_DIR."artist_screens/artist_navigation.php");

?>

<div class= "wap_artist_page_content" >
 
<div class="wap_container">
  <form id = "wap_artist_upload"  name = "wap_artist_upload" method="post" enctype="multipart/form-data">
	<?php wp_nonce_field( 'do_wap_artist_upload','wap_artist_upload' );?> 
	  <div class="wap_row">
      <div class="col-25">
        <label for="wap_artist_file" style="padding:1px 6px;"> File:</label>
      </div>
      <div class="col-75">
        <input type="file" id="wap_artist_file" name="wap_artist_file" placeholder="Re-type Password..">
      </div>
    </div>
	
    <div class="wap_row">
      <input type="submit" value="Submit">
    </div>
  </form>
</div>

</div>
