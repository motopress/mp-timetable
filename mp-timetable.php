<?php

/**
 * Plugin Name: Timetable
 * Plugin URI: http://www.getmotopress.com
 * Description: Smart time-management tool with a clean minimalist design for featuring your timetables and upcoming events.
 * Version: 1.0.4
 * Author: MotoPress
 * Author URI: http://www.getmotopress.com
 * License: GPLv2 or later
 * Text Domain: mp-timetable
 * Domain Path: /languages
 */

use mp_timetable\plugin_core\classes\Core;

define("MP_TT_PLUGIN_NAME", "mp-timetable");

register_activation_hook(__FILE__, array(Mp_Time_Table::init(), 'on_activation'));
register_deactivation_hook(__FILE__, array('Mp_Time_Table', 'on_deactivation'));
register_uninstall_hook(__FILE__, array('Mp_Time_Table', 'on_uninstall'));
add_action('plugins_loaded', array('Mp_Time_Table', 'init'));


class Mp_Time_Table {

	protected static $instance;

	public static function init() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Mp_Time_Table constructor.
	 */
	public function __construct() {
		$this->include_all();
		Core::get_instance()->init_plugin('mp_timetable');
	}

	/**
	 * Include all files
	 */
	public static function include_all() {
		/**
		 * Include Gump
		 */
		require_once self::get_plugin_path() . 'classes/libs/class-gump.php';
		/**
		 * Install Fire bug
		 */
		require_once self::get_plugin_path() . 'classes/libs/FirePHPCore/fb.php';
		/**
		 * Install Parsers
		 */
		require_once self::get_plugin_path() . 'classes/libs/parsers.php';
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
		require_once(self::get_plugin_path() . 'classes/class-shortcode.php');
		/**
		 * include Widget
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
	}

	/**
	 * Get plugin path
	 */
	public static function get_plugin_path() {
		return plugin_dir_path(__FILE__);
	}

	/**
	 * Get plugin part path
	 *
	 * @param string $part
	 *
	 * @return string
	 */
	public static function get_plugin_part_path($part = '') {
		return self::get_plugin_path() . $part;
	}

	/**
	 * Get plugin name
	 * @return string
	 */
	public static function get_plugin_name() {
		return dirname(plugin_basename(__FILE__));
	}


	/**
	 * On activation defrozo plugin
	 */
	public static function on_activation() {
		global $wpdb;
		// Register post type
		Core::get_instance()->register_all_post_type();
		// Register taxonomy all
		Core::get_instance()->register_all_taxonomies();
		flush_rewrite_rules();
		//Create table in not exists
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . "mp_timetable_data";
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `column_id` int(11) NOT NULL,
				  `event_id` int(11) NOT NULL,
				  `event_start` time NOT NULL,
				  `event_end` time NOT NULL,
				  `user_id` int(11) NOT NULL,
				  `description` text NOT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `id` (`id`)
				) $charset_collate";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	/**
	 * On deactivation defrozo plugin
	 */
	public static function on_deactivation() {
	}

	/**
	 * On uninstall
	 */
	public static function on_uninstall() {
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
	static function get_plugin_url($path = false, $pluginName = "mp-timetable", $sync = '') {
		return plugins_url() . '/' . $pluginName . '/' . $path . $sync;
	}
}