<?php

namespace mp_timetable\classes\controllers;

use mp_timetable\plugin_core\classes\Controller as Controller;

/**
 * Created by PhpStorm.
 * User: newmind
 * Date: 12/9/2015
 * Time: 5:34 PM
 */
class Controller_Events extends Controller {
	
	protected static $instance;
	private $data;
	
	/**
	 * @return Controller_Events
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
	public function action_template() {
		$this->data = $_REQUEST;
		$this->get_view()->render_html("events/index", $this->data);
	}
	
	/**
	 * Delete event data by ID
	 */
	public function action_delete() {
		$result = $this->get('events')->delete_event(filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT));
		if ($result === false) {
			wp_send_json_error(array('status' => $result));
		} else {
			wp_send_json_success(array('status' => $result));
		}
	}
	
	/**
	 * Get single event data
	 */
	public function action_get_event_data() {
		$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
		$result = $this->get('events')->get_event_data(array('field' => 'id', 'id' => $id), 'event_start', false);
		if (!empty($result)) {
			wp_send_json_success($result[ 0 ]);
		} else {
			wp_send_json_error(array('status' => false));
		}
	}
	
	/**
	 * Get events by column id
	 *
	 * @param $post
	 *
	 * @return mixed
	 */
	public function get_all_event_by_post($post) {
		// Show draft timeslots on preview
		$show_public_only = ((get_post_status($post->ID) == 'draft') && is_preview()) ? false : true;
		
		$result = $this->get('events')->get_event_data(array('field' => 'event_id', 'id' => $post->ID), 'event_start', $show_public_only);
		
		return $result;
	}
	
	/**
	 * Update Single Event data
	 */
	public function action_update_event_data() {
		$request = $_REQUEST;
		$result = $this->get('events')->update_event_data($request[ 'data' ]);
		if ($result === false) {
			wp_send_json_error(array('status' => false));
		} else {
			wp_send_json_success(array('data' => $result));
		}
	}
}