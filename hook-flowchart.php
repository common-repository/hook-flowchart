<?php

/**
 *
 * @package   Hook_Flowchart
 * @author    Mte90 <daniele@codeat.it>
 * @license   GPL-2.0+
 * @link      http://codeat.it
 * @copyright 2016 GPL
 *
 * @wordpress-plugin
 * Plugin Name:       Hook Flowchart
 * Plugin URI:        https://wordpress.org/plugins/hook-flowchart/
 * Description:       In every WordPress page there are in action many hooks but what are the relations between them?
 * Version:           1.0.0
 * Author:            Codeat
 * Author URI:        http://codeat.it
 * Text Domain:       hook-flowchart
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * WordPress-Plugin-Boilerplate-Powered: v1.1.6
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}

define( 'HF_VERSION', '2.0.0' );
define( 'HF_TEXTDOMAIN', 'hook-flowchart' );
define( 'HF_NAME', 'Hook Flowchart' );
define( 'HF_PLUGIN_ROOT', plugin_dir_path( __FILE__ ) );
define( 'HF_PLUGIN_ABSOLUTE',  __FILE__  );

/*
 * ------------------------------------------------------------------------------
 * Public-Facing Functionality
 * ------------------------------------------------------------------------------
 */
require_once( plugin_dir_path( __FILE__ ) . 'includes/load_textdomain.php' );

/*
 * Load library for simple and fast creation of Taxonomy 
 */
require_once( plugin_dir_path( __FILE__ ) . 'public/class-hook-flowchart.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Hook_Flowchart', 'activate' ) );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
add_action( 'plugins_loaded', array( 'Hook_Flowchart', 'get_instance' ), 9999 );

if ( is_admin() && (!defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-hook-flowchart-admin.php' );
	add_action( 'plugins_loaded', array( 'Hook_Flowchart_Admin', 'get_instance' ) );
}