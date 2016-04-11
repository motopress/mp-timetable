<?php

namespace mp_timetable\classes\models;

use mp_timetable\plugin_core\classes\Model as Model;

/**
 * Model Events
 */
class Column extends Model {

	protected static $instance;

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * @param array $params
	 *
	 * @return array
	 */
	public function get_all_column($params = array()) {
		$args = array(
			'post_type' => 'mp-column',
			'post_status' => 'publish',
			'order' => 'ASC',
			'orderby' => 'menu_order date',
			'posts_per_page' => -1,
			'post__in' => ''
		);

		$args = array_merge($args, $params);
		return get_posts($args);
	}

	public function get_columns_by_event_category($terms) {
		$columns = array();
		if (!is_array($terms)) {
			$terms = explode(',', $terms);
		}
		$posts_array = get_posts(
			array(
				'fields' => 'ids',
				'posts_per_page' => -1,
				'post_type' => 'mp-event',
				'tax_query' => array(
					array(
						'taxonomy' => 'mp-event_category',
						'field' => 'term_id',
						'terms' => $terms,
					)
				)
			)
		);
		$ids = implode(',', $posts_array);
		$event_data = $this->get('events')->get_events_data(array('column' => 'event_id', 'list' => $ids));
		if (!empty($event_data)) {
			foreach ($event_data as $event) {
				if (in_array($event->event_id, $posts_array)) {
					$columns[] = $event->column_id;
				}
			}
		}
		return array_unique($columns);
	}

	/**
	 * Render column metabox
	 *
	 * @param $post
	 */
	public function render_column_options($post) {
		$this->get_view()->render_html("column/metabox-column-options", array('post' => $post), true);
	}

	/**
	 * Save meta data Column post type
	 *
	 * @param array $params
	 */
	public function save_column_data(array $params) {
		if (!empty($params['data'])) {
			foreach ($params['data'] as $meta_key => $meta) {
				if (!empty($meta)) {
					update_post_meta($params['post']->ID, $meta_key, $meta);
				} else {
					delete_post_meta($params['post']->ID, $meta_key, $meta);
				}
			}
		}
	}
}