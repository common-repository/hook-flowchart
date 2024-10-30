<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Hook_Flowchart
 * @author    Codeat 
 * @license   GPL-2.0+
 * @link      http://codeat.it
 * @copyright 2015 Codeat
 */
// If uninstall not called from WordPress, then exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb;

if ( is_multisite() ) {

    $blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
    delete_option( 'video-ad' );
    if ( $blogs ) {
        foreach ( $blogs as $blog ) {
            switch_to_blog( $blog[ 'blog_id' ] );
            delete_option( 'hook-flowchart' );
            restore_current_blog();
        }
    }
} else {
    delete_option( 'video-ad' );
}