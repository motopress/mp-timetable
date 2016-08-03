<?php

namespace mp_timetable\classes\controllers;

use mp_timetable\classes\models\Settings;
use mp_timetable\plugin_core\classes\Controller as Controller;
use mp_timetable\plugin_core\classes\View;

/**
 * Class Controller_Settings
 * @package mp_timetable\classes\controllers
 */
class Controller_Settings extends Controller {

	protected static $instance;

	/**
	 * @return Controller_Settings
	 */
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
		$data = Settings::get_instance()->get_settings();
		$theme_supports = $this->get('Settings')->is_theme_supports();

		View::get_instance()->render_html('../templates/settings/general', array('settings' => $data, 'theme_supports' => $theme_supports));
	}

	/**
	 * Save settings
	 */
	public function action_save() {
		$redirect = Settings::get_instance()->save_settings();

		if ($redirect) {
			wp_redirect(add_query_arg(array('page' => $_GET['page'], 'settings-updated' => 'true')));
			die();
		}

		/**
		 * Show success message
		 */
		if (isset($_GET['settings-updated']) && ($_GET['settings-updated'] == TRUE)) {
			$_GET['settings-updated'] = false;
			add_settings_error('mpTimetableSettings', esc_attr('settings_updated'), __('Settings saved.', 'mp-timetable'), 'updated');
		}
	}
}