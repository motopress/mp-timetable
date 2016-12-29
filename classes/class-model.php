<?php

namespace mp_timetable\plugin_core\classes;

/**
 * Model class
 */
class Model extends Core {

	protected static $instance;

	/**
	 * @return Model
	 */
	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Install models by type
	 */
	public function install() {
		// include all core models
		Core::include_all(\Mp_Time_Table::get_plugin_part_path('classes/models'));
	}

	/**
	 * Get return Array
	 *
	 * @param array $data
	 * @param bool|false $success
	 *
	 * @return array
	 */
	public function get_arr($data = array(), $success = false) {
		return array('success' => $success, 'data' => $data);
	}
}
