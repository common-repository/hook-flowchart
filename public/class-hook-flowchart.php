<?php

/**
 * Hook Flowchart
 *
 * @package   Hook_Flowchart
 * @author    Mte90 <daniele@codeat.it>
 * @license   GPL-2.0+
 * @link      http://codeat.it
 * @copyright 2015 GPL
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-hook-flowchart-admin.php`
 *
 * @package Hook_Flowchart
 * @author  Mte90 <daniele@codeat.it>
 */
class Hook_Flowchart {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * @TODO - Rename "hook-flowchart" to the name of your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected static $plugin_slug = 'hook-flowchart';

	/**

	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected static $plugin_name = 'Hook Flowchart';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Hooks
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	protected $hooks = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		add_action( 'admin_bar_menu', array( $this, 'register_menu' ), 99 );
		add_action( 'all', array( $this, 'parent_hook' ) );
		add_action( 'shutdown', array( $this, 'print_hookr_flowchart' ), 9999 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_stuff' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_stuff' ) );
	}

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	  add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );






	  // Load public-facing style sheet and JavaScript.
	  add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	  add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	  add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_js_vars' ) );


	  }

	  /**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return self::$plugin_slug;
	}

	/**
	 * Return the plugin name.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin name variable.
	 */
	public function get_plugin_name() {
		return self::$plugin_name;
	}

	/**
	 * Return the version
	 *
	 * @since    1.0.0
	 *
	 * @return    Version const.
	 */
	public function get_plugin_version() {
		return self::VERSION;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide ) {
				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();

					restore_current_blog();
				}
			}
		}
		self::single_activate();
	}

	/**
	 * Registers the admin bar menu.
	 * 
	 * @param object $wp_admin_bar
	 * @return void
	 */
	function register_menu( $wp_admin_bar ) {
		$wp_admin_bar->add_node( array(
			'id' => 'hook-flowchart',
			'title' => 'Hook FlowChart',
			'href' => '#'
		) );
	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		$settings = get_option( self::$plugin_slug );
		$settings_new = false;
		//Set default values
		if ( empty( $settings[ 'excluded' ] ) ) {
			$settings_new[ 'excluded' ] = 'wp_default_scripts,map_meta_cap,get_avatar,custom_menu_order,admin_body_class,admin_footer_text,pre_option_gmt_offset,welcome_panel,date_i18n,pre_http_request,determine_current_user,in_admin_header,update_footer';
		}
		if ( $settings_new !== false ) {
			update_option( self::$plugin_slug, $settings_new );
		}
	}

	/**
	 * Fired for each hook
	 *
	 * @since    1.0.0
	 */
	public function parent_hook() {
		global $wp_current_filter;
		foreach ( $wp_current_filter as $child => $hook_name ) {
			if ( $child === 0 && !isset( $this->hooks[ $hook_name ] ) ) {
				$this->hooks[ $hook_name ] = array();
			} elseif ( $child === 1 && !isset( $this->hooks[ $wp_current_filter[ 0 ] ][ $hook_name ] ) ) {
				$this->hooks[ $wp_current_filter[ 0 ] ][ $hook_name ] = array();
			} elseif ( $child === 2 && !isset( $this->hooks[ $wp_current_filter[ 0 ] ][ $wp_current_filter[ 1 ] ][ $hook_name ] ) ) {
				$this->hooks[ $wp_current_filter[ 0 ] ][ $wp_current_filter[ 1 ] ][ $hook_name ] = array();
			} elseif ( $child === 3 ) {
				$this->hooks[ $wp_current_filter[ 0 ] ][ $wp_current_filter[ 1 ] ][ $wp_current_filter[ 2 ] ][ $hook_name ] = 1;
			}
		}
	}

	public function enqueue_stuff() {
		if ( is_user_logged_in() ) {
			wp_enqueue_style( HF_TEXTDOMAIN . '-plugin-styles', plugins_url( 'public/assets/css/mermaid.css', HF_PLUGIN_ABSOLUTE ), array(), HF_VERSION );
			wp_enqueue_script( HF_TEXTDOMAIN . '-mermaid', plugins_url( 'public/assets/js/mermaid.js', HF_PLUGIN_ABSOLUTE ), array( 'jquery' ), HF_VERSION );
			wp_enqueue_script( HF_TEXTDOMAIN . '-plugin-script', plugins_url( 'public/assets/js/popupcode.js', HF_PLUGIN_ABSOLUTE ), array( 'jquery' ), HF_VERSION );
		}
	}

	/**
	 * Generate the window
	 *
	 * @since    1.0.0
	 */
	function print_hookr_flowchart() {
		if ( !isset( $_GET[ 'hookr-flowchart' ] ) ) {
			ksort( $this->hooks );
			$exclude = get_option( $this->get_plugin_slug() );
			$exclude = explode( ',', $exclude[ 'excluded' ] );
			foreach ( $exclude as $key => $value ) {
				if ( isset( $this->hooks[ $value ] ) ) {
					unset( $this->hooks[ $value ] );
				}
			}

			echo '<style>.hookr-flowchart {z-index: 9999;position: absolute;background: white;}.wp-admin .hookr-flowchart{background: #f1f1f1;margin-left: 160px;top:30px;}</style>'
			. '<div class="hookr-flowchart" style="padding-left:20px;display:none;z-index:9999;">'
			. '<h1>Hook Flowchart</h1><h3>' . "\n"
			. __( 'Use ctrl + f to use your browser search function or click on that buttons to jump to the parent hook, check the hook to hide', $this->get_plugin_slug() ) . '</h3><span class="buttons"></span>' . "\n"
			. $this->array_to_mermaid( $this->hooks ) . "\n"
			. '<button class="button button-primary gotop" style="float:right;position:fixed;bottom:10px;right:10px;">' . __( 'Go Top', $this->get_plugin_slug() ) . '</button>'
			. '</div>';
		}
	}

	public function array_to_mermaid( $hooks ) {
		$html = '';
		foreach ( $hooks as $hook_father => $hook_son ) {
			if ( is_array( $hook_son ) && count( $hook_son ) > 1 ) {
				$html .= '<div class="mermaid-noise" style="display:none">';
				$html .= '[n]graph LR' . "[n]";
				$html .= 'A>' . $hook_father . ']' . "[n]";
				if ( is_array( $hook_son ) && count( $hook_son ) > 1 ) {
					$i = 0;
					foreach ( $hook_son as $hook_son_father => $hook_son_father_son ) {
						$i++;
						$html .= 'A -->' . $i . '[' . $hook_son_father . ']' . "[n]";
						$y = 0;
						if ( is_array( $hook_son_father_son ) ) {
							foreach ( $hook_son_father_son as $hook_son_father_son_father => $hook_son_father_son_father_son ) {
								$y++;
								$html .= $i . ' -->' . $y . '[' . $hook_son_father_son_father . ']' . "[n]";
								$z = 0;
								if ( is_array( $hook_son_father_son_father_son ) ) {
									$z++;
									foreach ( $hook_son_father_son_father_son as $hook_son_father_son_father_son_father => $undefined ) {
										$html .= $y . ' -->' . $z . '[' . $hook_son_father_son_father_son_father . ']' . "[n]";
									}
								}
							}
						}
					}
				}
				$html .= '</div>';
			}
		}
		return $html;
	}

}
