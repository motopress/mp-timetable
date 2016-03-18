<?php

namespace mp_timetable\plugin_core\classes\modules;

class Menu {

	protected static $instance;

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Add menu page
	 *
	 * @param  $params
	 */
	public function add_menu_page(array $params) {
		add_menu_page($params['page_title'], $params['menu_title'], $params['capability'], $params['menu_slug'], $params['function'], $params['icon_url'], $params['position']);
	}

	/**
	 * Add submenu page
	 *
	 * @param $params
	 */
	public function add_submenu_page(array $params) {
		add_submenu_page($params['parent_slug'], $params['page_title'], $params['menu_title'], $params['capability'], $params['menu_slug'], $params['function']);
	}
}
