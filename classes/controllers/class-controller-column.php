<?php

namespace mp_timetable\classes\controllers;

use mp_timetable\plugin_core\classes\Controller as Controller;

/**
 * Created by PhpStorm.
 * User: newmind
 * Date: 12/9/2015
 * Time: 5:34 PM
 */
class Controller_Column extends Controller {

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
	public function action_template() {
		$this->data = $_REQUEST;
		$this->get_view()->render_html("events/index", $this->data);
	}

	/**
	 * @param $post
	 */
	public function action_page_view($post) {
		$events = $this->get('events')->get_event_data(array('field' => 'column_id', 'id' => $post->ID));
		return $events;
	}

} 