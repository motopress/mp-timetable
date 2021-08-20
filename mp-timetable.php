<?php

/**
 * Plugin Name: Timetable and Event Schedule
 * Plugin URI: https://motopress.com/products/timetable-event-schedule/
 * Description: Smart time-management tool with a clean minimalist design for featuring your timetables and upcoming events.
 * Version: 2.4.2
 * Author: MotoPress
 * Author URI: https://motopress.com
 * License: GPLv2 or later
 * Text Domain: mp-timetable
 * Domain Path: /languages
 */

/*
 * This plugin contains hooks that allow you to edit, add and move content without the need to edit template files.
 * This method protects against upgrade issues. There are several actions and filters you can use to modify content output.
 * You can check \mp-timetable\classes\class-hooks.php for the list of hooks.
 * 
 * Alternatively in "Developer Mode", you can copy template files from '/mp-timetable/templates/' folder to '/your-theme/mp-timetable/' to override them.
 * 
 * The Timetable plugin also supports default WordPress templates hierarchy:
 * https://developer.wordpress.org/themes/basics/template-hierarchy/#visual-overview
 */

defined( 'ABSPATH' ) || exit;

use mp_timetable\plugin_core\classes\Core;

if ( ! defined( 'MP_TT_PLUGIN_NAME' ) ) {
	define( "MP_TT_PLUGIN_NAME", 'mp-timetable' );
}

if ( ! defined( 'MP_TT_DEBUG' ) ) {
	define( 'MP_TT_DEBUG', false );
}

if ( ! defined( 'MP_TT_PLUGIN_FILE' ) ) {
	define( 'MP_TT_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'MP_TT_PLUGIN_BASENAME' ) ) {
	define( 'MP_TT_PLUGIN_BASENAME', plugin_basename( MP_TT_PLUGIN_FILE ) );
}

register_activation_hook( __FILE__, array( Mp_Time_Table::init(), 'on_activation' ) );
register_deactivation_hook( __FILE__, array( 'Mp_Time_Table', 'on_deactivation' ) );
register_uninstall_hook( __FILE__, array( 'Mp_Time_Table', 'on_uninstall' ) );

add_action( 'plugins_loaded', array( 'Mp_Time_Table', 'init' ) );

if ( version_compare( get_bloginfo( 'version' ), '5.1', '>=' ) ) {
	add_action( 'wp_insert_site', array( 'Mp_Time_Table', 'on_create_blog' ) );
} else {
	add_action( 'wpmu_new_blog', array( 'Mp_Time_Table', 'on_create_blog' ) );
}

add_filter( 'wpmu_drop_tables', array( 'Mp_Time_Table', 'on_delete_blog' ) );

/**
 * Class Mp_Time_Table
 */
class Mp_Time_Table {
	
	protected static $instance;
	
	/**
	 * Mp_Time_Table constructor.
	 */
	public function __construct() {
		$this->include_all();
		Core::get_instance()->init_plugin( 'mp_timetable' );
	}
	
	/**
	 * Include all files
	 */
	public function include_all() {
		/**
		 * Include Gump
		 */
		require_once self::get_plugin_path() . 'classes/libs/class-gump.php';
		/**
		 * Install Parsers
		 */
		require_once self::get_plugin_path() . 'classes/libs/parsers.php';
		/**
		 * Include Permalinks
		 */
		require_once self::get_plugin_path() . 'classes/class-permalinks.php';
		/**
		 * Include Core
		 */
		require_once self::get_plugin_path() . 'classes/class-core.php';
		/**
		 * Include module
		 */
		require_once self::get_plugin_path() . 'classes/class-module.php';
		/**
		 * Include Model
		 */
		require_once self::get_plugin_path() . 'classes/class-model.php';
		
		/**
		 * Include Controller
		 */
		require_once self::get_plugin_path() . 'classes/class-controller.php';
		/**
		 * Include State factory
		 */
		require_once self::get_plugin_path() . 'classes/class-state-factory.php';
		
		/**
		 * Include Preprocessor
		 */
		require_once self::get_plugin_path() . 'classes/class-preprocessor.php';
		
		/**
		 * include shortcodes
		 */
		require_once( self::get_plugin_path() . 'classes/class-shortcode.php' );
		/**
		 * include Widgets
		 */
		require_once self::get_plugin_path() . 'classes/widgets/class-mp-timetable-widget.php';
		/**
		 * Include view
		 */
		require_once self::get_plugin_path() . 'classes/class-view.php';
		/**
		 * Include hooks
		 */
		require_once self::get_plugin_path() . 'classes/class-hooks.php';
		
		/**
		 * Include blocks
		 */
		require_once self::get_plugin_path() . 'classes/blocks/class-timetable-block.php';

		/**
         * Include Widgets Managers
         */
        require_once self::get_plugin_path() . 'classes/class-widgets-manager.php';
	}
	
	/**
	 * Get plugin path
	 */
	public static function get_plugin_path() {
		return plugin_dir_path( __FILE__ );
	}
	
	/**
	 * @return Mp_Time_Table
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * Retrieve relative to theme root path to templates.
	 *
	 * @return string
	 */
	public static function get_template_path() {
		return apply_filters( 'mptt_template_path', 'mp-timetable/' );
	}
	
	/**
	 * Retrieve relative to plugin root path to templates.
	 *
	 * @return string
	 */
	public static function get_templates_path() {
		return self::get_plugin_path() . 'templates/';
	}
	
	/**
	 * Get plugin part path
	 *
	 * @param string $part
	 *
	 * @return string
	 */
	public static function get_plugin_part_path( $part = '' ) {
		return self::get_plugin_path() . $part;
	}
	
	/**
	 * On activation
	 */
	public static function on_activation( $network_wide = false ) {
		
		if ( $network_wide && is_multisite() ) {

			$sites = get_sites();

			foreach( $sites as $site ) {

				$blog_id = $site->blog_id;

				switch_to_blog( $blog_id );
				Mp_Time_Table::install();
				restore_current_blog();

			}
		} else {

			Mp_Time_Table::install();

		}
	}

	/**
	 * Install
	 */
	public static function install() {
		// Register post type
		Core::get_instance()->register_all_post_type();

		// Register taxonomy all
		Core::get_instance()->register_all_taxonomies();

		flush_rewrite_rules( false );

		//Create table in not exists
		Core::get_instance()->create_table();
	}

	/**
	 * On deactivation
	 */
	public static function on_deactivation() {
		flush_rewrite_rules( false );
	}

	/**
	 * On uninstall
	 */
	public static function on_uninstall() {
		do_action( 'mptt_on_uninstall' );
	}
	
	/**
	 * On create blog
	 *
	 * @param int|WP_Site $blog
	 */
	public static function on_create_blog( $blog ) {

		if ( is_plugin_active_for_network( MP_TT_PLUGIN_BASENAME ) ) {

			if ( ! is_int( $blog ) ) {
				$blog = $blog->id;
			}

			switch_to_blog( $blog );
			Mp_Time_Table::install();
			restore_current_blog();
		}
	}

	/**
	 * Get plugin name
	 *
	 * @return string
	 */
	public static function get_plugin_name() {
		return dirname( plugin_basename( __FILE__ ) );
	}
	
	/**
	 * On blog creation
	 */
	public static function on_delete_blog( $tables ) {
		
		$tables[] = self::get_datatable();
		
		return $tables;
	}
	
	/**
	 * Get data table name
	 *
	 * @return string
	 */
	public static function get_datatable() {
		global $wpdb;
		
		return $wpdb->prefix . "mp_timetable_data";
	}
	
	/**
	 * Get plugin url
	 *
	 * @param bool|false $path
	 * @param string $pluginName
	 * @param string $sync
	 *
	 * @return string
	 */
	static function get_plugin_url( $path = false, $pluginName = 'mp-timetable', $sync = '' ) {
		return plugins_url() . '/' . $pluginName . '/' . $path . $sync;
	}

}
