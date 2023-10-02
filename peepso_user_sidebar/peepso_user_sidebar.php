<?php
/

/**
 * @package peepso_user_sidebar
 * @version 1
 */
/*
Plugin Name: peepso_user_sidebar
Plugin URI: www.google.com
Description: This plugin list peeps user sidebar with search.requires peeps.
Requires Peeps
Author:Imran
Version: 1
Author URI: http://www.google.com
*/
define('PUS_NUM_USER',999999);
add_action( 'wp_enqueue_scripts', function(){
	wp_enqueue_style('pusw-styles', trailingslashit(plugin_dir_url( __FILE__ )). 'style.css', array());	
	wp_enqueue_script('pusw-scripts', trailingslashit(plugin_dir_url( __FILE__ )). 'script.js', array());	
	wp_localize_script('pusw-scripts', 'pusw_data', array(
			'users' => array()
		));	
	
},PHP_INT_MAX);
add_action( 'set_user_role', function($user_id, $role='', $old_roles =array()){
	
	foreach($old_roles as $a_role){
		$meta_key  = "psu_".strtolower( $a_role )."_last_login";	
		delete_user_meta( $user_id,  $meta_key);
		
	}
},PHP_INT_MAX,3);

$user_added  = array();
add_action('wp_login', 'pus_store_last_login', 10, 2);
function pus_store_last_login($current_user,$user) {
   
	$roles = ( array ) $user->roles; 

	if ( !empty( $roles ) && is_array( $roles ) ) {
				foreach ( $roles as $role ){
					$meta_key = 'psu_'.$role.'_last_login';
					if(!get_user_meta($user->ID, $meta_key))
						add_user_meta($user->ID, $meta_key, time());
					else 
						update_user_meta($user->ID, $meta_key, time());					
				}
				
	}
	

}
function get_users_sorted_by_last_login($exclude,$usr_role,$num_user = PUS_NUM_USER){
	global $wpdb;
	$users=$wpdb->prefix."users";
	$usermeta=$wpdb->prefix."usermeta";
	$meta_key = 'psu_'.$usr_role.'_last_login';	
	
	$str_user_id = "";
	if(count($exclude)){
		$sql_user_id = " and user_id not in (".implode(",",$exclude).") ";
	}
	$sql = "
SELECT
	*,
	$usermeta.meta_value 
FROM
	$users
	INNER JOIN $usermeta ON $users.ID = $usermeta.user_id 
WHERE
	meta_key =  '$meta_key' 
$sql_user_id
ORDER BY
	CAST(  $usermeta.meta_value AS UNSIGNED ) DESC
LIMIT 0,$num_user
	";
	// echo $sql; exit();
	// $sql = "select user_id from $usermeta where meta_key like '%capabilities' psu_last_login' $sql_user_id  "
	$results = $wpdb->get_results( $sql );
	return $results;
}
function get_online_users_id(){
	global $wpdb;
	
	$results = $wpdb->get_results( "SELECT user_id FROM $wpdb->useronline ORDER BY timestamp DESC" );
	foreach($results as $a_result){
		$ret[] = $a_result->user_id;
	}
	
	return $ret;
	
	
}
function output_user_list($user_list,$status){
	foreach($user_list as $a_user){
						$profile_url = esc_url(get_author_posts_url($a_user->ID));
						$avatar_url = esc_url( get_avatar_url( $a_user->ID  ));
						$display_name = $a_user->display_name;
						$user_id = $a_user->ID;
				?>
					<div class= "pusw_user_list_item"   data-user_id = "<?=$user_id ?>" >
						<div class="pusw_left">
							<a data-user_id = "<?=$user_id ?>" class = "pusw_image_link" href='<?=$profile_url?>'><img class="pusw_user_img" src="<?=$avatar_url?>" />
							 
							</a>
							<span class="<?=$status?>_status"></span>
						</div>
						<div class="pusw_right">
							<a style="text-decoration:none" data-user_id = "<?=$user_id ?>"  class = "pusw_name_link"  href='<?=$profile_url?>'><?=$display_name?></a>
						</div>
						
					</div>
				<?php
					}
}
if ( ! class_exists( 'Peepso_User_Sidebar_Widget', false ) ) :
class Peepso_User_Sidebar_Widget extends WP_Widget {
 
    function __construct() {
 
        parent::__construct(
            'peepso-user-sidebar-widget',  // Base ID
            'KInky-Peepso User Sidebar Widget'   // Name
        );
 
        add_action( 'widgets_init', function() {
            register_widget( 'Peepso_User_Sidebar_Widget' );
        });
 
    }
 
    public $args = array(
        'before_title'  => '<section id="custom_html-14" class="widget_text widget widget_custom_html"><h4 class="widget-title"><span>',
        'after_title'   => '</span></h4><div class="textwidget custom-html-widget"><span {text-align:center;}="" class="dashicons dashicons-email"></span> <a> Sende mir eine Nachricht </a></div></section>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );
    public function widget( $args, $instance ) {
		// var_dump(htmlspecialchars($this->my_args['before_title']));exit();
		
		$num_user = isset($instance['num_user'])?$instance['num_user']: PUS_NUM_USER;
		$arr_exclude = array();
        $saved_exclude = explode(",",$instance['exclude_user']);
		
		if( is_array($saved_exclude) && count($saved_exclude) )
			foreach($saved_exclude as $id){
				if(is_numeric(trim($id)))
					$arr_exclude[] = trim($id);
			}
		
		$role_to_retrive = "author";
		$user = wp_get_current_user(); // getting & setting the current user 
		if($user){
			$roles = ( array ) $user->roles; 
			if ( !empty( $roles ) && is_array( $roles ) ) {
				foreach ( $roles as $role ){
					if($role=="author")
						$role_to_retrive = "subscriber";
				}
				
			}
		}
		
		//**important remove this
		
		//**important remove this
		
		$user_ids =get_online_users_id();
		
		$x = array(
			'role'		=> $role_to_retrive,
			'orderby' 	=> 'rand',
			'include'	=> $user_ids,
			'exclude'	=> $arr_exclude,
			'number' 	=>$num_user
		);
		
		// var_dump($x); exit();
		$online_user_list = get_users($x);
		$num_users_retrieved = count(		$online_user_list );
		//update exclude
		
		foreach($online_user_list as $a_user){
				$arr_exclude[] = $a_user->ID;//array_merge($arr_exclude ,$user_ids);
		}
		
		$sorted_off_line_users =  array();
		if($num_users_retrieved < $num_user){
			
			$temp_arr =  get_users_sorted_by_last_login($arr_exclude,$role_to_retrive,$num_user-$num_users_retrieved);
			if(is_array($temp_arr) && count($temp_arr)){
				$sorted_off_line_users  = $temp_arr;
				if(is_array($sorted_off_line_users) && count($sorted_off_line_users)){
					$num_users_retrieved += count($sorted_off_line_users);
					foreach($sorted_off_line_users as $a_user)
						$arr_exclude [] = $a_user->ID;
				}
			}
		}
		$offline_user_list = array();
		if($num_users_retrieved < $num_user){
			$x = array(
				'role'		=> $role_to_retrive,
				'orderby' 	=> 'rand',			
				'exclude'	=> $arr_exclude,
				'number' 	=>$num_user
			);
			
			$offline_user_list =  get_users($x);
		}
		
		
		
		

		
		
		echo $args['before_widget'];
		
		?>  
			
			<?php 
			
				if( $instance['show_title']){
					echo  $this->args['before_title']; ;
					echo  $instance['title']; ;
					echo  $this->args['after_title']; ;
					
				}
			?>
			<input type="hidden" id="dummy" />
			<div class= "pusw_user_list_container" >
				<div class= "pusw_user_list_item_input"     id= "pusw_user_list_item_search"  >
					<input class=  "pusw_user_search" placeholder="Suchen ..." type="text"style="width:100%;"/>
				</div>
				<?php 
					output_user_list($online_user_list,"online");
					output_user_list($sorted_off_line_users,"offline");
					output_user_list($offline_user_list,"offline");
				?>				
				
			</div>
			<script>
			(function($) {
				$( document ).ready(function() {
					let temp_data={}
					<?php
					global $user_added ;
					$status_array = array("online","offline","offline");
					$temp_array = array( 
										'online'=>$online_user_list,
										'last_login'=>$sorted_off_line_users,
										'offline'=>$offline_user_list);
					$count = 0;
					foreach($temp_array as $key=>$ulist){
					
						foreach($ulist as $user){
						$status = $status_array[$count] ;
						
						$profile_url = esc_url(get_author_posts_url($user->ID));
						$avatar_url = esc_url( get_avatar_url( $user->ID  ));
						$display_name = $user->display_name;
						$user_id = $user->ID;
						if(!isset($user_added [$user_id])){
							$user_added [$user_id] = true;
					?>
							temp_data= {
								"user_id":"<?=$user_id?>",
								"display_name":"<?=$display_name?>",
								"avatar_url":"<?=$avatar_url?>",
								"profile_url":"<?=$profile_url?>",
								"status":"<?=$status?>",
							};
							pusw_data.users.push(temp_data);
					<?php
							}
						}
						$count++;
					}
					?>
				});
			})( jQuery );
			</script>
			
		<?php
/* 
        if ( ! empty( $instance['title'] ) ) {
			

            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
 
        echo '<div class="textwidget">';
 
        echo esc_html__( $instance['text'], 'text_domain' );
 
        echo '</div>';
 */
        echo $args['after_widget'];
 
    }
 
    public function form( $instance ) {
	
					

		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'text_domain' );
        $num_user = ! empty( $instance['num_user'] ) ? $instance['num_user'] : esc_html__( '', 'text_domain' );
        $exclude_user = ! empty( $instance['exclude_user'] ) ? $instance['exclude_user'] : esc_html__( '', 'text_domain' );
        $show_title =  isset( $instance['show_title'] ) ? $instance['show_title'] : esc_html__( '', 'text_domain' );

		$show_title_checked = "";
			if($show_title){
				$show_title_checked = " CHECKED ";
			}

        ?>
        <p>
                   <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_Title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_title' ) ); ?>" type="checkbox" value="<?php echo esc_attr( $show_title ); ?>" <?=$show_title_checked ?> value="1">
				   <label for="<?php echo esc_attr( $this->get_field_id( 'show_title' ) ); ?>"><?php echo esc_html__( 'Show Title:', 'text_domain' ); ?></label>
				

        </p>
		
		<p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
		 <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'num_user' ) ); ?>"><?php echo esc_html__( 'Number of User to Show:', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'num_user' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'num_user' ) ); ?>" type="number" value="<?php echo esc_attr( $num_user ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'exclude_user' ) ); ?>"><?php echo esc_html__( 'Exclude UserId(put in comma seperate list):', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'exclude_user' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'exclude_user' ) ); ?>" type="text" value="<?php echo esc_attr( $exclude_user ); ?>">
        </p>
        <?php
 
    }
 
    public function update( $new_instance, $old_instance ) {
		
   
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['num_user'] = ( !empty( $new_instance['num_user'] ) ) ? $new_instance['num_user'] : '';
        $instance['exclude_user'] = ( !empty( $new_instance['exclude_user'] ) ) ? $new_instance['exclude_user'] : '';
        $instance['show_title'] = ( array_key_exists( 'show_title',$new_instance ) ) ? '1' : '';

        return $instance;
    }
 
}
endif;
$peepso_user_sidebar_widget = new Peepso_User_Sidebar_Widget();


add_shortcode( 'pus_user_list', function( $atts, $content ){

	$sh_attr = shortcode_atts( array(		
		'num_user'=>'20',
		'exclude'=>'',
		),$atts );
	
	$num_user = trim($sh_attr['num_user']);
	$exclude = trim($sh_attr['exclude']);
	
	
	$result = psu_show_list($num_user,$exclude );
	return $content.$result;
},10,2);
function psu_show_list($num_user = PUS_NUM_USER,$exclude ){

		$arr_exclude = array();
        $saved_exclude = explode(",",$exclude);
		
		if( is_array($saved_exclude) && count($saved_exclude) )
			foreach($saved_exclude as $id){
				if(is_numeric(trim($id)))
					$arr_exclude[] = trim($id);
			}
		
		$role_to_retrive = "author";
		$user = wp_get_current_user(); // getting & setting the current user 
		if($user){
			$roles = ( array ) $user->roles; 
			if ( !empty( $roles ) && is_array( $roles ) ) {
				foreach ( $roles as $role ){
					if($role=="author")
						$role_to_retrive = "subscriber";
				}
				
			}
		}
		
		//**important remove this
		
		//**important remove this
		
		$user_ids =get_online_users_id();
		
		$x = array(
			'role'		=> $role_to_retrive,
			'orderby' 	=> 'rand',
			'include'	=> $user_ids,
			'exclude'	=> $arr_exclude,
			'number' 	=>$num_user
		);
		
		// var_dump($x); exit();
		$online_user_list = get_users($x);
		$num_users_retrieved = count(		$online_user_list );
		//update exclude
		foreach($online_user_list as $a_user){
				$arr_exclude[] = $a_user->ID;//array_merge($arr_exclude ,$user_ids);
		}
		
		$sorted_off_line_users =  array();
		if($num_users_retrieved < $num_user){
			
			$temp_arr =  get_users_sorted_by_last_login($arr_exclude,$role_to_retrive,$num_user-$num_users_retrieved);
			if(is_array($temp_arr) && count($temp_arr)){
				$sorted_off_line_users  = $temp_arr;
				if(is_array($sorted_off_line_users) && count($sorted_off_line_users)){
					$num_users_retrieved += count($sorted_off_line_users);
					foreach($sorted_off_line_users as $a_user)
						$arr_exclude [] = $a_user->ID;
				}
			}
		}
		$offline_user_list = array();
		if($num_users_retrieved < $num_user){
			$x = array(
				'role'		=> $role_to_retrive,
				'orderby' 	=> 'rand',			
				'exclude'	=> $arr_exclude,
				'number' 	=>$num_user
			);
			
			$offline_user_list =  get_users($x);
			
		}
		ob_start();
		?>
		<section id="custom_html-14" class="widget_text widget widget_custom_html"><h4 class="widget-title"><span>Sende mir eine Nachricht</span></h4><div class="textwidget custom-html-widget"><span {text-align:center;}="" class="dashicons dashicons-email"></span> <a> Sende mir eine Nachricht </a>
		</div></section>
		<input type="hidden" id="dummy" />
		<div class= "pusw_user_list_container" >
		
		
		<?php
		
			output_user_list($online_user_list,"online");
			output_user_list($sorted_off_line_users,"offline");
			output_user_list($offline_user_list,"offline");
		?>
		</div>
		<?php
		return ob_get_clean();
		
}
?>