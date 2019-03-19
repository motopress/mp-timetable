<?php

namespace mp_timetable\plugin_core\classes;

use Mp_Time_Table;

/**
 * Class Module
 * @package mp_timetable\plugin_core\classes
 */
class Module extends Core {

	/**
	 * Install controllers
	 */
	public static function install() {
		// include all core controllers
		Core::include_all(Mp_Time_Table::get_plugin_part_path('classes/modules'));
	}
}
