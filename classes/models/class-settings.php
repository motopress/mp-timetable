<?php

namespace mp_timetable\classes\models;

use mp_timetable\plugin_core\classes\Model as Model;

/**
 * Model Events
 */
class Settings extends Model {

	protected static $instance;

	public function __construct() {
		parent::__construct();
	}

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Create event category admin link
	 *
	 * @param $column
	 */
	public function get_settings() {
		$default = current_theme_supports('mptt-templates') ? 'plugin' : 'theme';
		$data = get_option('mp_timetable_general', array('source_id' => $default));

		return $data;
	}

	/**
	 * Render settings
	 *
	 * @param $post
	 */
	public function render_settings($post) {
		$data = $this->get_settings();

		$this->get_view()->render_html("settings/general", array('settings' => $data), true);
	}

	/**
	 * Save meta data Column post type
	 *
	 * @param array $params
	 */
	public function save_settings() {
		$saved = false;

		if (isset($_POST['mp-timetable-save-settings']) &&
				wp_verify_nonce($_POST['mp-timetable-save-settings'], 'mp_timetable_nonce_settings')
		) {
			if (isset($_POST)) {
				if (!empty($_POST['source_id'])) {
					$options['source_id'] = $_POST['source_id'];
					$saved = true;
				}
				update_option('mp_timetable_general', $options);
			}
		}

		return $saved;
	}

	/**
	 * Check whether to use single room template from plugin
	 *
	 * @return bool
	 */
	public function is_plugin_template_mode(){
		return $this->get_template_mode() === 'plugin';
	}

	/**
	 * Retrieve template mode. Possible values: plugin, theme.
	 *
	 * @return string
	 */
	public function get_template_mode(){
		$option = $this->get_settings();

		return $option['source_id'];
	}
}