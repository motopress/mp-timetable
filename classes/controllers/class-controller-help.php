<?php

namespace mp_timetable\classes\controllers;

use mp_timetable\plugin_core\classes\Controller as Controller;
use mp_timetable\plugin_core\classes\View;

/**
 * Class Controller_Help
 * @package mp_timetable\classes\controllers
 */
class Controller_Help extends Controller {

	protected static $instance;

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Action template
	 */
	public function action_content() {
		View::get_instance()->render_html('../admin/help/index');
	}
}