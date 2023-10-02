<div id="wklw_profile_row_parent">
<?php 
$users = wklw_get_user_list_to_display();
?>

			

			<?php 
		
			foreach($users as $a_user){
				$user_id =$a_user->ID ;
				$profile_url = esc_url(get_author_posts_url($user_id ));
				$avatar_url = esc_url( get_avatar_url( $user_id   ));
				$display_name = $a_user->display_name;				
				// $display_name = $a_user->display_name;			
				$routing_no = get_user_meta($user_id ,'wklw_routing_no',true);
				$routing_no = 	$routing_no ?$routing_no :"&nbsp;";
				$msg =  __('Offline','wklw');
				$status= "offline";
				$call_rate=get_user_meta($user_id,'wklw_call_rate',true);
				if($a_user->online_status == 1){
					$msg =  __('Call Me Now','wklw');
					$status= "online";
				} 
				?>
				<?php if($count ==0 ){ ?>
					<div class="wp-block-columns is-style-default wklw_profile_row">
				<?php } 
					$count++;
				?>
				<!--  -->
				
					<div class='wp-block-columns is-style-default wklw_item_container wklw_profile' id="item_container_<?=$user_id ?>">
						<div class='wp-block-column' id="wp-block-column_<?=$user_id ?>">
							<figure class="wp-block-image size-large">
								<img   src='<?=$avatar_url ?>' class="wklw_avatar" />      
								<!-- style= "height:220.217px;width:243.8px;" -->
							</figure>
							<h2 class="has-text-align-center"><?=$display_name?></h2>
							<p class="has-text-align-center"><?=$routing_no?></p>
							<p id="callme_now_<?=$user_id ?>" class="has-text-align-center has-white-color has-text-color has-background  wklw_callme_now_<?=$status?>" ><?=$msg ?></p>
							<p class="has-text-align-center" style="font-size:12px"><?=$call_rate?>€/Min. a.d. Dt. Festnetz / Mobil abweichend</p>
							
						</div>
					</div>
				<?php if($count ==5 ){ ?>
					</div>
				<?php
					$count = 0;
				} ?>
				
			<?php } 
				if($count < 5){
					?>
					</div>
					<?php
				}
			?>
			

</div>
