<?php
/*
Plugin Name: WPSea Functionality
Description: Functionality plugin for code/settings commonly use in the Seattle WordPress community
Contributors: wpseattle, blobaugh, jaffe75
Version: 0.5
Author: WordPress Seattle
Author URI: http://www.meetup.com/SeattleWordPressMeetup/
*/

/*
 * Prefix everything with wpsea_func
 */

define( 'WPSEA_FUNC_PLUGIN_DIR', trailingslashit( dirname( __FILE__) ) );

// Get some wp-admin functionality rolling
if( is_admin() ) {
    require_once( WPSEA_FUNC_PLUGIN_DIR . 'lib/admin.php' );
}