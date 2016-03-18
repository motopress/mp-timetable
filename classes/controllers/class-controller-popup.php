<?php

namespace mp_timetable\classes\controllers;

use mp_timetable\plugin_core\classes\Controller as Controller;
use mp_timetable\plugin_core\classes\Model as Model;

/**
 * Class Controller_Popup
 * @package mp_timetable\classes\controllers
 */
class Controller_Popup extends Controller {

	protected static $instance;
	private $data;

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Action template
	 */
	public function action_get_popup_html_content() {
		$this->data['column'] = $this->get('column')->get_all_column();
		$this->data['events'] = $this->get('events')->get_all_events();
		$this->data['category'] = get_terms('mp-event_category', 'orderby=count&hide_empty=0');
		$data["html"] = $this->get_view()->render_html("popup/index", $this->data, false);
		$this->send_json(Model::get_instance()->get_arr($data, true));
	}


} 