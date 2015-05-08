<?php

/** 
 * WPMU Push (wpmunotify.com)
 * Create get_notifier.php url
 * @author: Jason Jersey
 * @since: 1.0
 */
function get_notifyMe() {

    global $wpmun_VERSION_KEY;

    /** Get plugin directory url */
    $wpmunotify_url    = plugin_dir_url( dirname(__FILE__) );
    $wpmunotify_string = "get_notifier.php?ver=";
    
    /** Used to create version strings. Stops file from being cached */
    $PUT_VER_STRING = time();
    
    /** Url to external js file with version string */
    $GET_NOTIFY = "$wpmunotify_url$wpmunotify_string$wpmun_VERSION_KEY.$PUT_VER_STRING";
    
    return $GET_NOTIFY;
}

/** 
 * WPMU Push (wpmunotify.com)
 * Init notifications
 * @author: Jason Jersey
 * @since: 1.0
 */
function wpmun_init_js() { 	

/** 
 * the code within this function (below) is javascript
 * well, except for the echo of course. 
 */
echo 'jQuery(document).ready(
        function() {
            setInterval(function() {                

            jQuery.ajax({
                url: "' . get_notifyMe() . '",
                context: document.body
            }).done(function() { 
                notifyMe();
            });
                                                          
            }, 3000); /** 3 seconds */
        }
       
    );';
/** 
 * the code within this function (above) is javascript
 * well, except for the echo of course. 
 */

} 

?>