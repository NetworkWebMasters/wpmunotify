<?php

/**
 * WPMU Push (wpmunotify.com)
 * Prepare notification script
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmun_notify_js() {

	global $wpdb;

        $user_id = get_current_user_id();
        $post_id = get_posts("numberposts=1");

	$wpmun_ip_address = $_SERVER['REMOTE_ADDR']; /** receiver ip */
	$wpmun_post_id    = $post_id[0]->ID; /** post id */
	
	$db_table  = $wpdb->prefix . 'wpmunotify_posts_activity';
	$sql_one   = $wpdb->query("SELECT * FROM $db_table WHERE user_id = $user_id AND post_id = $wpmun_post_id");
	$sql_two   = $wpdb->query("SELECT * FROM $db_table WHERE ip_address = '$wpmun_ip_address' AND post_id = $wpmun_post_id");
	
	/** Get plugin directory url */
        $wpmunotify_url  = plugin_dir_url( dirname(__FILE__) );
		
	if ( is_user_logged_in() ) {/** START: if logged in */

/** the code within this function (below) is javascript */

?>

    <?php if ( ! $sql_one == $user_id && $wpmun_post_id ) { ?>
    <?php query_posts('showposts=1'); ?>
    <?php while (have_posts()) : the_post(); ?>
    jQuery(function() {
	jQuery(document).ready(function() {
		/*Set the timeout timer, 30000 milliseconds equals 30 seconds*/
		var timer = 30000, timeoutId;
		
		/*Set the selector for the notification alert box*/
		var notiAlert = jQuery(".wpmunotify_noti");

		/*Call the function to show the notification alert box*/
		showAlert();
		
		/*Call the function to start the timer to close the notification alert box*/
		startTimer();

		/**
		 * The function to show notification alert box
		 * <?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>
		 * <?php echo get_avatar_url(get_the_author_meta('ID'), wp_parse_args( $args, array( 'size' => 30 ) )); ?>
		 */
		function showAlert() {
			jQuery(".wpmunotify_notification").html('<a rel="permalink" href="<?php the_permalink(); ?>"><div class="wpmunotify_noti noti_clearfix"><div class="wpmunotify_noti_image noti_left" style="background-image: url(<?php echo get_avatar_url(get_the_author_meta('ID'), wp_parse_args( $args, array( 'size' => 30 ) )); ?>);"></div><div class="wpmunotify_noti_content noti_left"><p><b><?php echo get_the_author(); ?></b> published: "<?php echo ucwords( get_the_title() ); ?>"</p><span class="wpmunotify_noti_time"><i class="icon_comment"></i> <?php the_time('F j, Y'); ?></span></div><div class="icon_close noti_right" id="close"></div></div></a>');
			var wpmupsnd = new Audio("<?php echo $wpmunotify_url; ?>audio/new_post.mp3");wpmupsnd.volume = 0.2;wpmupsnd.play();
		}

		/*The function to start the timeout timer*/
		function startTimer() {
			timeoutId = setTimeout(function() {
				jQuery(".wpmunotify_noti").hide();
			}, timer);
		}

		/*The function to stop the timeout timer*/
		function stopTimer() {
			clearTimeout(timeoutId);
		}

		/*Call the stopTimer function on mouse enter and call the startTimer function on mouse leave*/
		jQuery(".wpmunotify_noti").mouseenter(stopTimer).mouseleave(startTimer);

		/*Close the notification alert box when close button is clicked*/
		jQuery("#close").click(function() {
			jQuery(".wpmunotify_noti").hide();
		});
	});
    });
    <?php endwhile; ?>
    <?php wpmunotify_insert_posts_activity_data(); } ?>

<?php

/** the code within this function (above) is javascript */

	}/** END: if logged in */
	    else { /** START: if logged out */

/** the code within this function (below) is javascript */

?>

    <?php if ( ! $sql_two == $wpmun_ip_address && $wpmun_post_id ) { ?>
    <?php query_posts('showposts=1'); ?>
    <?php while (have_posts()) : the_post(); ?>
    jQuery(function() {
	jQuery(document).ready(function() {
		/*Set the timeout timer, 30000 milliseconds equals 30 seconds*/
		var timer = 30000, timeoutId;
		
		/*Set the selector for the notification alert box*/
		var notiAlert = jQuery(".wpmunotify_noti");

		/*Call the function to show the notification alert box*/
		showAlert();
		
		/*Call the function to start the timer to close the notification alert box*/
		startTimer();

		/**
		 * The function to show notification alert box
		 * <?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>
		 * <?php echo get_avatar_url(get_the_author_meta('ID'), wp_parse_args( $args, array( 'size' => 30 ) )); ?>
		 */
		function showAlert() {
			jQuery(".wpmunotify_notification").html('<a rel="permalink" href="<?php the_permalink(); ?>"><div class="wpmunotify_noti noti_clearfix"><div class="wpmunotify_noti_image noti_left" style="background-image: url(<?php echo get_avatar_url(get_the_author_meta('ID'), wp_parse_args( $args, array( 'size' => 30 ) )); ?>);"></div><div class="wpmunotify_noti_content noti_left"><p><b><?php echo get_the_author(); ?></b> published: "<?php echo ucwords( get_the_title() ); ?>"</p><span class="wpmunotify_noti_time"><i class="icon_comment"></i> <?php the_time('F j, Y'); ?></span></div><div class="icon_close noti_right" id="close"></div></div></a>');
			var wpmupsnd = new Audio("<?php echo $wpmunotify_url; ?>audio/new_post.mp3");wpmupsnd.volume = 0.2;wpmupsnd.play();
		}

		/*The function to start the timeout timer*/
		function startTimer() {
			timeoutId = setTimeout(function() {
				jQuery(".wpmunotify_noti").hide();
			}, timer);
		}

		/*The function to stop the timeout timer*/
		function stopTimer() {
			clearTimeout(timeoutId);
		}

		/*Call the stopTimer function on mouse enter and call the startTimer function on mouse leave*/
		jQuery(".wpmunotify_noti").mouseenter(stopTimer).mouseleave(startTimer);

		/*Close the notification alert box when close button is clicked*/
		jQuery("#close").click(function() {
			jQuery(".wpmunotify_noti").hide();
		});
	});
    });
    <?php endwhile; ?>
    <?php wpmunotify_insert_posts_activity_data(); } ?>
    
<?php

/** the code within this function (above) is javascript */

}/** END: if logged out */

}/** END: wpmun_notify_js() */

?>