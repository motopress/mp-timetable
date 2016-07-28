<?php

namespace mp_timetable\classes\controllers;

use mp_timetable\plugin_core\classes\Controller as Controller;
use mp_timetable\plugin_core\classes\View;

/**
 * Class Controller_Settings
 * @package mp_timetable\classes\controllers
 */
class Controller_Settings extends Controller {

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
		$data = get_option('mp_timetable_general', array('source_id' => 'plugin'));
		View::get_instance()->render_html('../templates/settings/general', array('settings' => $data));
	}

	/**
	 * Save settings
	 */
	public function action_save() {
		$redirect = false;
		$options = array();

		if (isset($_POST['mp-timetable-save-settings']) &&
			wp_verify_nonce($_POST['mp-timetable-save-settings'], 'mp_timetable_nonce_settings')
		) {
			if (isset($_POST)) {
				if (!empty($_POST['source_id'])) {
					$options['source_id'] = $_POST['source_id'];
					$redirect = true;
				}
				update_option('mp_timetable_general', $options);
			}
		}

		if ($redirect) {
			wp_redirect(add_query_arg(array('page' => $_GET['page'], 'settings-updated' => 'true')));
			die();
		}

		/**
		 * Show success message
		 */
		if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
			$_GET['settings-updated'] = false;
			$_GET['settings-updated'] = false;
			add_settings_error('mpTimetableSettings', esc_attr('settings_updated'), __('Settings saved.', 'mp-timetable'), 'updated');
		}
	}

}