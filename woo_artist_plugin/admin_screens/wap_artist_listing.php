<?php
	$x = array(
		'role'			=> 'wap_artist'
		
	);
	$total = count(get_users($x));
	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	$limit = 20; // number of rows in page
	$offset = ( $pagenum - 1 ) * $limit;
	$num_of_pages = ceil( $total / $limit );
	$x = array(
		'role'			=> 'wap_artist',
		
		'orderby'             => 'login',
		'order'               => 'DESC',
		'offset'	=> $offset,
		'number'	=> $limit
	);
	$all_users = get_users($x);
?>

<div class="wrap">

	<h1>Woo Artist Plugin Setting</h1>
	<hr/>
	<h3>Artist Listing:</h3>
	<table class="wp-list-table widefat fixed striped table-view-list tags">
			<tr><th>Artist</th><th>Page Id</th></tr>
		<?php
			foreach($all_users as $a_user){
				
				?>
				<tr>
					<td><a href="<?=(admin_url('/admin.php?page=woo-artist-list'))?>&artist_id=<?=$a_user->ID?>"><?=$a_user->user_login?></a></td>
					<td><a href="<?=get_edit_user_link($a_user->ID)?>">Edit Profile </a></td>
						
					</td>
				</tr>
				
				<?php
			}
		?>
	</table>
	

</div>