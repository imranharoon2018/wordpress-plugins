<div class="wap_container">
  <form id = "wap_artist_form"  name = "wap_artist_form" method="post" >
	<?php wp_nonce_field( 'do_wap_artist_signup','wap_artist_signup' );?> 
    <div class="wap_row">
      <div class="col-25">
        <label for="wap_artist_username">Username</label>
      </div>
      <div class="col-75">
        <input type="text" id="wap_artist_username" name="wap_artist_username" placeholder="Username..">
      </div>
    </div>
    <div class="wap_row">
      <div class="col-25">
        <label for="wap_artist_email">Email</label>
      </div>
      <div class="col-75">
        <input type="email" id="wap_artist_email" name="wap_artist_email" placeholder="Email..">
      </div>
    </div>
    <div class="wap_row">
      <div class="col-25">
        <label for="country">Password</label>
      </div>
      <div class="col-75">
         <input type="password" id="wap_artist_password" name="wap_artist_password" placeholder="Password..">
      </div>
    </div>
    <div class="wap_row">
      <div class="col-25">
        <label for="wap_artist_re_password">Re-type Password</label>
      </div>
      <div class="col-75">
        <input type="password" id="wap_artist_re_password" name="wap_artist_re_password" placeholder="Re-type Password..">
      </div>
    </div>
	
    <div class="wap_row">
      <input type="submit" value="Submit">
    </div>
  </form>
</div>