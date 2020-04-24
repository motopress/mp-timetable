<?php

namespace mp_timetable\classes\models;

use mp_timetable\plugin_core\classes\Model as Model;

/**
 * Model Events
 */
class Settings extends Model {

	protected static $instance;

	/**
	 * Settings constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 *  Get instance
	 *
	 * @return Settings
	 */
	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Render settings
	 */
	public function render_settings() {
		$data = $this->get_settings();

		$this->get_view()->render_html("settings/general", array('settings' => $data), true);
	}

	/**
	 * Get Settings
	 *
	 * @return mixed
	 */
	public function get_settings() {

		$mp_timetable_general = array(
			'theme_mode' => 'theme',
		);

		$settings = get_option('mp_timetable_general', $mp_timetable_general);

		if ($this->is_theme_supports()) {
			$settings['theme_mode'] = 'plugin';
		}

		return $settings;
	}

	/**
	 * Theme supports plugin mode.
	 *
	 * @return string
	 */
	public function is_theme_supports() {

		return current_theme_supports('mp-timetable');
	}

	/**
	 * Save meta data Column post type
	 *
	 */
	public function save_settings() {

		if ( current_user_can('manage_options') && !empty( $_POST['theme_mode'] ) ) {

			$options = array();
			$options['theme_mode'] = $_POST['theme_mode'];

			update_option('mp_timetable_general', $options);
		}
	}

	/**
	 * Check whether to use single room template from plugin
	 *
	 * @return bool
	 */
	public function is_plugin_template_mode() {
		return ($this->get_template_mode() === 'plugin');
	}

	/**
	 * Retrieve template mode. Possible values: plugin, theme.
	 *
	 * @return string
	 */
	public function get_template_mode() {
		$options = $this->get_settings();

		return isset($options['theme_mode']) ? $options['theme_mode'] : 'theme';
	}
}