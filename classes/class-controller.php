<?php

namespace mp_timetable\plugin_core\classes;

/**
 * Controller class
 */
class Controller extends Core {

	protected static $instance;

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Install controllers
	 */
	public function install() {
		// include all core controllers
		Core::include_all(\Mp_Time_Table::get_plugin_part_path('classes/controllers'));
	}

	/**
	 * Send json data
	 *
	 * @param array $data
	 */
	public function send_json($data) {
		if (is_array($data) && isset($data['success']) && !$data['success']) {
			wp_send_json_error($data);
		} else {
			wp_send_json_success($data['data']);
		}
	}

}
