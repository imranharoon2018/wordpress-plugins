<div class="wklw_container">
  <form id = "wklw_signup_form"  name = "wklw_signup_form" method="post" enctype="multipart/form-data" autocomplete="off">
	<?php wp_nonce_field( 'do_wklw_signup','wklw_signup' );?> 

	<div class="wklw_row">
      <div class="col-25">
        <label for="wklw_username"><?=__('Username','wklw')?></label>
      </div>
      <div class="col-40">
        <input type="text" id="wklw_username" name="wklw_username" placeholder="<?=__('Username','wklw')?> ..." value="<?= isset($_REQUEST["wklw_username"])?$_REQUEST["wklw_username"]:""?>" required  />
      </div>
	  <div class="col-35 error_column">
        <label ><?= isset($_REQUEST["wklw_username_msg"])?$_REQUEST["wklw_username_msg"]:""?></label>
      </div>
    </div>
	
	<div class="wklw_row">
      <div class="col-25">
        <label for="wklw_password"><?=__('Password','wklw')?></label>
      </div>
      <div class="col-40">
        <input type="password" id="wklw_password" name="wklw_password" placeholder="<?=__('Password','wklw')?> ..." value="" required  />
      </div>
	  <div class="col-35 error_column">
        <label id="wklw_password_msg" ><?= isset($_REQUEST["wklw_password_msg"])?$_REQUEST["wklw_password_msg"]:""?></label>
      </div>
    </div>

		
	<div class="wklw_row">
      <div class="col-25">
        <label for="wklw_retype_password"><?=__('Re-type Password','wklw')?></label>
      </div>
      <div class="col-40">
        <input type="password" id="wklw_retype_password" name="wklw_retype_password" placeholder="<?=__('Retype Password','wklw')?> ..." value=""  required />
      </div>
	  <div class="col-35 error_column">
        <label ><?= isset($_REQUEST["wklw_retype_password_msg"])?$_REQUEST["wklw_retype_password_msg"]:""?></label>
      </div>
    </div>
	 
	<div class="wklw_row">
      <div class="col-25">
        <label for="wklw_email"><?=__('Email','wklw')?></label>
      </div>
      <div class="col-40">
        <input type="email" id="wklw_email" name="wklw_email" placeholder="<?=__('Email','wklw')?> ..."  value="<?= isset($_REQUEST["wklw_email"])?$_REQUEST["wklw_email"]:""?>" required />
      </div>
	  
	  <div class="col-35 error_column">
        <label><?= isset($_REQUEST["wklw_email_msg"])?$_REQUEST["wklw_email_msg"]:""?></label>
      </div>
    </div>
    <div class="wklw_row">
      <div class="col-25">
        <label for="wklw_first_name"><?=__('First Name','wklw')?></label>
      </div>
      <div class="col-40">
        <input type="text" id="wklw_first_name" name="wklw_first_name" placeholder="<?=__('First Name','wklw')?> ..." value="<?= isset($_REQUEST["wklw_first_name"])?$_REQUEST["wklw_first_name"]:""?>" required  />
      </div>
	  <div class="col-35 error_column">
        <label ><?= isset($_REQUEST["wklw_first_name_msg"])?$_REQUEST["wklw_first_name_msg"]:""?></label>
      </div>
    </div>
	<div class="wklw_row">
      <div class="col-25">
        <label for="wklw_surname"><?=__('Surname','wklw')?></label>
      </div>
      <div class="col-40">
        <input type="text" id="wklw_surname" name="wklw_surname" placeholder="<?=__('Surname','wklw')?> ..."  value="<?= isset($_REQUEST["wklw_surname"])?$_REQUEST["wklw_surname"]:""?>" required  />
      </div>
	  <div class="col-35 error_column">
        <label ><?= isset($_REQUEST["wklw_surname_msg"])?$_REQUEST["wklw_surname_msg"]:""?> </label>
      </div>
    </div>
	<div class="wklw_row">
      <div class="col-25">
        <label for="wklw_id_no"><?=__('ID No.','wklw')?> </label>
      </div>
      <div class="col-40">
        <input type="text" id="wklw_id_no" name="wklw_id_no" placeholder="<?=__('ID No.','wklw')?> ..."  value="<?= isset($_REQUEST["wklw_id_no"])?$_REQUEST["wklw_id_no"]:""?>" required  />
      </div>
	  <div class="col-35 error_column">
        <label ><?= isset($_REQUEST["wklw_id_no_msg"])?$_REQUEST["wklw_id_no_msg"]:""?></label>
      </div>
    </div>
	<div class="wklw_row">
      <div class="col-25">
        <label for="wklw_phone_no"><?=__('Phone No.','wklw')?></label>
      </div>
      <div class="col-40">
        <input type="text" id="wklw_phone_no" name="wklw_phone_no" placeholder="<?=__('Phone No.','wklw')?> ..." value="<?= isset($_REQUEST["wklw_phone_no"])?$_REQUEST["wklw_phone_no"]:""?>" required />
      </div>
	  <div class="col-35 error_column">
        <label ><?= isset($_REQUEST["wklw_phone_no_msg"])?$_REQUEST["wklw_phone_no_msg"]:""?> </label>
      </div>
    </div> 
	
	<div class="wklw_row">
      <div class="col-25">
        <label for="wklw_address1"><?=__('Address','wklw')?></label>
      </div>
      <div class="col-40">
        <input type="text" id="wklw_address1" name="wklw_address1" placeholder="<?=__('Address','wklw')?>  ..." value="<?= isset($_REQUEST["wklw_address1"])?$_REQUEST["wklw_address1"]:""?>"  required  />
      </div>
	  <div class="col-35 error_column">
        <label ><?= isset($_REQUEST["wklw_address1_msg"])?$_REQUEST["wklw_address1_msg"]:""?></label>
      </div>
    </div>
	<div class="wklw_row">
      <div class="col-25">
        <label for="wklw_address2"><?=__('Address(line. 2)','wklw')?> </label>
      </div>
      <div class="col-40">
        <input type="text" id="wklw_address2" name="wklw_address2" placeholder="<?=__('Address(line. 2)','wklw')?>..."  value="<?= isset($_REQUEST["wklw_address2"])?$_REQUEST["wklw_address2"]:""?>" required>
      </div>
	  <div class="col-35 error_column">
        <label ><?= isset($_REQUEST["wklw_address2_msg"])?$_REQUEST["wklw_address2_msg"]:""?></label>
      </div>
    </div>
	
	
	<div class="wklw_row">
      <div class="col-25">
        <label for="wklw_id_scan1"><?=__('ID Card Front','wklw')?> </label>
      </div>
      <div class="col-40">
         <input type="file" id="wklw_id_scan1" name="wklw_id_scan1" required  />
      </div>
	  <div class="col-35 error_column">
        <label id="wklw_id_scan1_msg"><?= isset($_REQUEST["wklw_id_scan1_msg"])?$_REQUEST["wklw_id_scan1_msg"]:""?></label>
      </div>
    </div>
	<div class="wklw_row">
      <div class="col-25">
        <label for="wklw_id_scan2"><?=__('ID Card Back','wklw')?> </label>
      </div>
      <div class="col-40">
         <input type="file" id="wklw_id_scan2" name="wklw_id_scan2" required /> 
      </div>
	   <div class="col-35 error_column">
        <label  id="wklw_id_scan2_msg"><?= isset($_REQUEST["wklw_id_scan2_msg"])?$_REQUEST["wklw_id_scan2_msg"]:""?></label>
      </div>
    </div>
	<div class="wklw_row">
      <div class="col-25">
        <label for="wklw_avatar"><?=__('Avatar','wklw')?></label>
      </div>
      <div class="col-40">
         <input type="file" id="wklw_avatar" name="wklw_avatar" required />
      </div>
	   <div class="col-35 error_column">
        <label id="wklw_avatar_msg"><?= isset($_REQUEST["wklw_avatar_msg"])?$_REQUEST["wklw_avatar_msg"]:""?></label>
      </div>
    </div>

	
	
    <!-- <div class="wklw_row">
	
      <input type="submit" value="Submit">
    </div> -->
	<div class="wklw_row">
	 <div class="col-25">
        <label for="wklw_avatar">&nbsp;</label>
      </div>
      <div class="col-40" >
          <input style="width:100%;" type="submit" value="<?=__('Submit','wklw')?>" >
      </div>
	   <div class="col-35 error_column">
        <label >&nbsp;</label>
      </div>
     
    </div>
  </form>
</div>