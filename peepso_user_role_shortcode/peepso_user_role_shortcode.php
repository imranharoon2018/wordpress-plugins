<?php
/**
 * @package peepso_user_role_shortcode
 * @version 1
 */
/*
Plugin Name: peepso_user_role_shortcode
Plugin URI: www.google.com
Description: This plugin implement shortcode[p_u_role] that list peepso user by role , allows exludeing user by username  and conditionally allow chats.
Requires Peeps
Author:Imran
Version: 1
Author URI: http://www.google.com
*/
// to show meessage button use show_message_button attributes [p_u_role role="author"   days="40" exclude = "king,queen,jack" show_message_button="true" ] 
// upload and activate this plugin you should see a link for chat.
// if you can see the link. go to line 102 and disable it. go to line 106 and enable it. Upload the file you should now see button
 
//for author created in last 40 days and exclude author with login "king", "queen" and "jack" :  [p_u_role role="author"   days="40" exclude = "king,queen,jack"] 
/****Important only days are supported for 1 month use days="30" for 6 month use days= "180"****/
//for all author : [p_u_role role="author"   ]
//for author created in last 40 days [p_u_role role="author"   days="40" ]


//for all subscriber : [p_u_role role="subscriber"   ]
//for subscribers created in last 40 days: [p_u_role role="subscriber"  days="40"  ]
/****Important only days are supported for 1 month use days="30" for 6 month use days= "180"****/

add_action( 'wp_enqueue_scripts', function(){
	wp_enqueue_style('peepso_user_role_shortcode-styles', trailingslashit(plugin_dir_url( __FILE__ )). 'assets/css/peepso_user_role_shortcode.css', array());	
	wp_enqueue_script('peepso_user_role_shortcode-script', trailingslashit(plugin_dir_url( __FILE__ )). 'assets/js/script.js', array());	
});

add_shortcode( 'p_u_role', 'wpquick_restrict_content_display' );


function wpquick_restrict_content_display( $atts, $content ){

	$sh_attr = shortcode_atts( array(
		'role' => 'author',
		'days'=>'',
		'exclude'=>'',
		'show_message_button' => ''
	), $atts );
	
	$role = trim($sh_attr['role']);
	$role = preg_replace('!\s+!', ' ', $role);
	$role =  str_replace(" ","_",$role);
	$exclude = trim($sh_attr['exclude']);
	$exclude = explode(",",$exclude);
	$exclude = array_map('trim',$exclude);
	// $exclude_key = array();

	// foreach($exclude as $name){
		// $exclude_key[$name ] = true;
	// }
	$show_message_button = (bool)$sh_attr['show_message_button'];
	
	$days = $sh_attr['days'];
	$date_query = array();
	if($days && is_numeric($days)){
		$seconds = $days*24*60*60;
		$end = time();
		$start = $end - $seconds;
		$date_query = array(
			array(
				'before' => array(
					'year'  => date('Y',$end),
					'month' => date('m',$end),
					'day'   => date('d',$end),
				),
				'after' => array(
					'year'  => date('Y',$start),
					'month' => date('m',$start),
					'day'   => date('d',$start),
				),
				'inclusive' => true,
			),
		);
	}

	$x = array(
		'role'			=> $role,
		'date_query' 	=>$date_query ,
		'login__not_in'	=>	$exclude 
	);
	$total = count(get_users($x));
	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	$limit = 20; // number of rows in page
	$offset = ( $pagenum - 1 ) * $limit;
	$num_of_pages = ceil( $total / $limit );
	
	$x = array(
		'role'			=> $role,
		'date_query' 	=>$date_query ,
		'login__not_in'	=>	$exclude ,
		'orderby'             => 'registered',
		'order'               => 'DESC',
		'offset'	=> $offset,
		'number'	=> $limit
	);
	$all_users = get_users($x);
	
	ob_start();
    echo "<div class='p_u_r_s_bg container'>";
    echo '<input type="hidden" id="dummy_test_for_chat" />';
    echo "<div class='p_u_r_s_row'>";
	foreach($all_users as $a_user){
		
		if(!isset($exclude_key[$a_user->user_login])){
			$profile_url = esc_url(get_author_posts_url($a_user->ID));

			echo "<div class='p_u_r_s_col-3 nopadding shadow custm_mycread_container'>";
			echo "<div class='p_u_r_s_img_container'><a href='".$profile_url."'>"."<img class='p_u_r_s_img' src=".esc_url( get_avatar_url( $a_user->ID  ))." /></a></div>";          
			echo "<div class='p_u_r_s_name'><a class='p_u_r_s_name_link' href='".$profile_url."'>".($a_user->display_name)."</a>";		
				if($show_message_button ){
					//step 1 show link
					// echo "<br/><a class='p_u_r_s_name_link p_u_r_s_open_chat_window' data-user_id='".$a_user->ID  ."' href='#'>Nachricht senden</a>";
					//step 2 show button
					//disable the line above in step 1 by adding a // .
					//enable the code  below
					echo '<br/><button class="p_u_r_s_open_chat_window dokan-btn dokan-btn-theme dokan-follow-store-button dokan-btn-sm" data-user_id="'.$a_user->ID.'">Nachricht senden<i class="dashicons dashicons-email-alt store_banner_dash_icons"></i></button>';

				}

			echo "</div>";//<div class='p_u_r_s_name'>

			echo "</div>";
		}
		
	}
	echo "</div>";
	
	$arr_query_arg = array(
							'pagenum' => '%#%'
						) ;
	$page_links = paginate_links( array(
			// 'base' => add_query_arg( 'pagenum', '%#%' ),
			'base' => add_query_arg($arr_query_arg),
			'format' => '',
			'prev_text' => __( '&laquo;&nbsp;Vorherige Seite', 'text-domain' ),
			'next_text' => __( 'Nächste Seite&nbsp;&raquo;', 'text-domain' ),
			'total' => $num_of_pages,
			'current' => $pagenum
		) );

		if ( $page_links ) {
			echo '<div class="paginate_role_tablenav">' . $page_links . '</div>';
		}
    echo "</div>";
	$out = ob_get_clean();
	return $content.$out;
}