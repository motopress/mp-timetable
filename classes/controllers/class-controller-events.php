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

		check_ajax_referer( 'timeslot_delete_nonce', 'nonce' );

		$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

		$event_id = 0;
		$timeslot = $this->get('events')->get_timeslot_by_id( $id );

		if ( $timeslot ) {
			$event_id = (int) $timeslot->event_id;
		}

		if ( $event_id && current_user_can( 'edit_post', $event_id ) ) {

			$result = $this->get('events')->delete_event( $id );

			if ($result === false) {
				wp_send_json_error(array('status' => $result));
			} else {
				wp_send_json_success(array('status' => $result));
			}
		} else {
			wp_die( sprintf( 'Access denied, %s, %s', __FUNCTION__, basename( __FILE__ ) ) );
		}
	}

	/**
	 * Get single event data
	 */
	public function action_get_event_data() {

		if ( current_user_can('edit_posts') ) {

			$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
			$result = $this->get('events')->get_event_data(array('field' => 'id', 'id' => $id), 'event_start', false);

			if (!empty($result)) {
				wp_send_json_success($result[ 0 ]);
			} else {
				wp_send_json_error(array('status' => false));
			}
		} else {
			wp_die( sprintf( 'Access denied, %s, %s', __FUNCTION__, basename( __FILE__ ) ) );
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

		check_ajax_referer( 'timeslot_update_nonce', 'nonce' );

		$data = $_REQUEST[ 'data' ];

		$event_id = 0;
		$id = (int) $data[ 'id' ];
		$timeslot = $this->get('events')->get_timeslot_by_id( $id );

		if ( $timeslot ) {
			$event_id = (int) $timeslot->event_id;
		}

		if ( $event_id && current_user_can( 'edit_post', $event_id ) ) {

			$result = $this->get('events')->update_event_data( $data );

			if ($result === false) {
				wp_send_json_error(array('status' => false));
			} else {
				wp_send_json_success(array('data' => $result));
			}
		} else {
			wp_die( sprintf( 'Access denied, %s, %s', __FUNCTION__, basename( __FILE__ ) ) );
		}
	}
}