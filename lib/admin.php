<?php

/**
 * Enque common wp-admin css and javascript files 
 */
function wpsea_func_admin_enqueue() {
 
    wp_register_style('wpsea_func-admin-css', WP_PLUGIN_URL . '/wpsea-functionality/css/admin.css');
    wp_enqueue_style('wpsea_func-admin-css');
    
    wp_register_script('wpsea_func-admin-js', WP_PLUGIN_URL.'/wpsea-functionality/js/admin.js', array('jquery'));
    wp_enqueue_script('wpsea_func-admin-js');
}
add_action( 'admin_enqueue_scripts', 'wpsea_func_admin_enqueue' );