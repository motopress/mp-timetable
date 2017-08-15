<?php

namespace mp_timetable\plugin_core\classes\modules;

use Mp_Time_Table;
use mp_timetable\plugin_core\classes\Module as Module;

class Post extends Module {

	protected static $instance;

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function pre_get_posts($query) {
		if ($query->is_author() && $query->is_main_query() && !is_admin()) {

			$post_types = $query->get('post_type');
			if ( !is_array($post_types) && !empty($post_types) ) {
				$post_types = explode(',', $post_types);
			}
			if ( empty($post_types) )
				$post_types = array('post');
			
			$post_types[] = 'mp-event';
			$query->set('post_type', $post_types);
		}

		return $query;
	}

	/**
	 * add meta _boxes
	 */
	public function add_meta_boxes() {
		add_meta_box('mp-event_data', __('Timeslots', 'mp-timetable'), array($this->get('events'), 'render_event_data'), 'mp-event', 'normal', 'high', array('post_type' => 'mp-event'));
		add_meta_box('mp_event_options', __('Settings', 'mp-timetable'), array($this->get('events'), 'render_event_options'), 'mp-event', 'normal', 'high', array('post_type' => 'mp-event'));
		add_meta_box('mp-columns', __('Column Type', 'mp-timetable'), array($this->get('column'), 'render_column_options'), 'mp-column', 'normal', 'high', array('post_type' => 'mp-column'));
	}

	/**
	 * Save custom_post
	 *
	 * @param $post_id
	 * @param $post
	 */
	public function save_custom_post($post_id, $post) {
		$request = $_REQUEST;
		if (!empty($request[Mp_Time_Table::get_plugin_name() . '_noncename'])) {
			$post_type = $request['post_type'];
			if (!wp_verify_nonce($request[Mp_Time_Table::get_plugin_name() . '_noncename'], Mp_Time_Table::get_plugin_path())) {
				return $post->ID;
			}

			// Is the user allowed to edit the post or page?
			if (!current_user_can('edit_post', $post->ID)) {
				return $post->ID;
			}
			// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return $post->ID;
			}

			//Save post by post_type
			switch ($post_type) {
				case 'mp-event':
					$this->get('events')->save_event_data(array('post' => $post,
						'event_data' => (!empty($request['event_data'])) ? $request['event_data'] : null,
						'event_meta' => (!empty($request['event_meta'])) ? $request['event_meta'] : null));
					break;
				case 'mp-column':
					$this->get('column')->save_column_data(array('post' => $post, 'data' => $request['column']));
					break;
				default:
					break;
			}
		}
	}

	/**
	 * Before delete custom post
	 *
	 * @param $post_id
	 * @param $post
	 */
	public function before_delete_custom_post($post_id) {
		global $post_type;
		if ($post_type === 'mp-column') {
			$this->get('column')->before_delete_column($post_id);
		} elseif ($post_type === 'mp-event') {
			$this->get('events')->before_delete_event($post_id);
		}

		return;
	}
}
