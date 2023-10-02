<?php
// /**
 // * @package peeps_fix_user_role
 // * @version 990
 // */
// /*
// Plugin Name: peeps_fix_user_role
// Plugin URI: www.google.com
// Description: This plugin helps fix user role. Requires Peepso.
// Author: Imran
// Version: 990
// Author URI: www.google.com
add_action('admin_menu',function(){
add_menu_page( 'peeps_fix_user_role', 'peeps_fix_user_role', 'manage_options', 'peeps-fix-user-role', 'peepso_show_fix_user_role', 'dashicons-welcome-widgets-menus', 90 );
});
function peepso_show_fix_user_role(){
	?>
		<div class="wrap">
		<form method="post">
			<p>
				User Id:<input type="number" id="peepso_user_id" name="peepso_user_id" />
			
			</p>
			<p>
			
			<input type="submit" value="search" class="button-primary"/>
			</p>
			<input type="hidden" name="page"  id="page" value="<?=$_REQUEST["page"]?>" />
		
		</form>
		<hr/>
		<?php
			if(  isset($_REQUEST['user_meta_user_id']) &&  isset($_REQUEST['user_meta_key_to_delete']) ){
				$user_id = $_REQUEST['user_meta_user_id'];
				$meta_key = $_REQUEST['user_meta_key_to_delete'];
				delete_user_meta( $user_id,  $meta_key);
				echo "<h1>done</h1>";
				
				
			}
			if( isset($_REQUEST['peepso_user_id']) ){
				
				?>
				<h3>Results:</h3>
				<?php
				$arr= array( 'author','subscriber' );
				$user_id = $_REQUEST['peepso_user_id'];
				foreach($arr as $elem){
					$key = "psu_".$elem."_last_login";					
					$meta= get_user_meta( $user_id, $key , true );
					if($meta){
					?>
						<form method="post">
							<?=ucfirst($elem)?> Last Login:<?=$meta?>
							<input type="submit" value="delete"/>
							<input type="hidden" name="page"  id="page" value="<?=$_REQUEST["page"]?>" />
							<input type="hidden" name="user_meta_key_to_delete"  id="user_meta_key_to_delete" value="<?=$key?>" />
							<input type="hidden" name="user_meta_user_id"  id="user_meta_user_id" value="<?=$user_id ?>" class="button-primary" />
						</form>
					<?php
					}
					
				}
				
			}
		
		?>
		</div>
	<?php
}