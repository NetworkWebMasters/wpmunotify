<?php

/** 
 * WPMU Push (wpmunotify.com)
 * Create random version key
 * @author: Jason Jersey
 * @since: 1.0
 */
function generate_version_key($length = 3) {
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
        return $randomString;
}

/** 
 * WPMU Push (wpmunotify.com)
 * Make version key global
 * @author: Jason Jersey
 * @since: 1.0
 */
global $wpmun_VERSION_KEY;
$wpmun_VERSION_KEY = generate_version_key();

/** 
 * WPMU Push (wpmunotify.com)
 * Create init url
 * @author: Jason Jersey
 * @since: 1.0
 */
function get_initMe() {

    global $wpmun_VERSION_KEY;
    
    /** Used to create version strings. Stops file from being cached */
    $PUT_VER_STRING = time();
    
    /** Get plugin directory url */
    $wpmunotify_URL    = plugin_dir_url( __FILE__ );  
    $wpmunotify_STRING = "get_init.php?ver=$PUT_VER_STRING.$wpmun_VERSION_KEY"; 
    
    /** Url to external js file with version string */
    $GET_INIT = "$wpmunotify_URL$wpmunotify_STRING";
    
    return $GET_INIT;
}

/** 
 * WPMU Push (wpmunotify.com)
 * Create init css
 * @author: Jason Jersey
 * @since: 1.0
 */
function get_initCSS() {

    global $wpmun_VERSION_KEY;
    
    /** Used to create version strings. Stops file from being cached */
    $PUT_VER_STRING = time();

    /** Get plugin directory url */
    $wpmunotify_URL    = plugin_dir_url( __FILE__ );  
    $wpmunotify_STRING = "css/notification.css?ver=$PUT_VER_STRING.$wpmun_VERSION_KEY"; 
    
    /** Url to external js file with version string */
    $GET_INIT = "$wpmunotify_URL$wpmunotify_STRING";
    
    return $GET_INIT;
}

/** 
 * WPMU Push (wpmunotify.com)
 * Add stuff to frontend header
 * @author: Jason Jersey
 * @since: 1.0
 */
function the_notifyMe() {

echo "\n<!-- WordPress Notifications by WPMU Notify -->\n";
echo "<link rel='stylesheet' type='text/css' href='" . get_initCSS() . "' />\n";
echo "<script type='text/javascript' src='" . get_initMe() . "' defer></script>\n";
echo "<div class='wpmunotify_notification'></div>\n";
echo "<!--// WPMU Notify (www.wpmunotify.com) -->\n\n";

}
/** add_action('wp_head', 'the_notifyMe'); */
add_action('wp_footer', 'the_notifyMe');

/** 
 * WPMU Push (wpmunotify.com)
 * Insert data into wpmunotify_posts table in db
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmunotify_insert_posts_data( $post_ID ) {
	global $wpdb;
	
        $pub_post   = get_post($post_ID);
        $author_id  = $pub_post->post_author;
        $post_title = $pub_post->post_title;
        $postperma  = get_permalink( $post_ID );     
  	
	$wpmun_post_id    = $post_ID;
	$wpmun_msg_status = 'true';
	$wpmun_author     = $author_id;
	$wpmun_title      = $post_title;
	$wpmun_url        = $postperma;
	
	$table_name = $wpdb->prefix . 'wpmunotify_posts';
	$already    = $wpdb->get_row("SELECT * FROM $table_name WHERE post_id = $post_ID");
	
	if ( ! $already == $post_ID ) {
	
	    $wpdb->insert( 
		  $table_name, 
		  array(
			'post_id'    => $wpmun_post_id,
			'msg_status' => $wpmun_msg_status,
			'author'     => $wpmun_author,
			'title'      => $wpmun_title,
			'url'        => $wpmun_url
		  )		
	    );
	
	}
	
	wpmunotify_update_posts_data();
	
}
add_action('publish_post', 'wpmunotify_insert_posts_data');

/** 
 * WPMU Push (wpmunotify.com)
 * Update data in wpmunotify_posts row in db
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmunotify_update_posts_data() {
	global $wpdb;
	
	$a                = $wpdb->insert_id;
        $lastid           = bcsub($a, 1);  
	$table_name       = $wpdb->prefix . 'wpmunotify_posts';
	$wpmun_id         = $lastid;
	$wpmun_msg_status = 'false';

	$data = array(
	     'msg_status' => $wpmun_msg_status
	);
	
        $where        = array( 'id' => $wpmun_id );
	$format       = array( '%s', '%d' );
	$where_format = array( '%d' );

	$wpdb->update( 
		$table_name, $data, $where, $format, $where_format 
		);
	
}

/**
 * WPMU Push (wpmunotify.com)
 * Insert data into wpmunotify_posts_activity table in db
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmunotify_insert_posts_activity_data() {
	global $wpdb;

        $user_id = get_current_user_id();
        $post_id = get_posts("numberposts=1");
	
	$wpmun_user_id        = $user_id; /** receiver user_id */
	$wpmun_ip_address     = $_SERVER['REMOTE_ADDR']; /** receiver ip */
	$wpmun_footprint      = '0000000000000000000000000'; /** receiver footprint */
	$wpmun_post_id        = $post_id[0]->ID; /** post id */
	$wpmun_post_author_id = get_post_field( 'post_author', $wpmun_post_id ); /** author */
	
	$table_name = $wpdb->prefix . 'wpmunotify_posts_activity';
	
	    $wpdb->insert( 
		  $table_name, 
		  array(
			'user_id'        => $wpmun_user_id,
			'ip_address'     => $wpmun_ip_address,
			'footprint'      => $wpmun_footprint,
			'post_id'        => $wpmun_post_id,
			'author_user_id' => $wpmun_post_author_id
		  )		
	    );
	
}