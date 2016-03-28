<?php

namespace mp_timetable\classes\models;

use mp_timetable\plugin_core\classes\Model as Model;
use mp_timetable\plugin_core\classes\modules\Taxonomy;


/**
 * Model Events
 */
class Events extends Model {

	protected static $instance;
	protected $wpdb;
	protected $table_name;


	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	function __construct() {
		parent::__construct();
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->table_name = $wpdb->prefix . "mp_timetable_data";
	}

	/**
	 * Getter
	 *
	 * @param $property
	 *
	 * @return mixed
	 */
	function __get($property) {
		return $this->$property;
	}

	/**
	 * Setter
	 *
	 * @param $property
	 * @param $value
	 *
	 * @return mixed
	 */
	function __set($property, $value) {
		return $this->$property = $value;
	}

	/**
	 * Render event metabox
	 *
	 * @param $post
	 * @param $metabox
	 */
	public function render_event_data($post, $metabox) {
		//$date_format = get_option('date_format');
		$time_format = get_option('time_format');
		$data['columns'] = $this->get('column')->get_all_column();
		if ($time_format === 'H:i') {
			$time_format_array = array('hours' => '0,23', 'am_pm' => false);
		} elseif ($time_format === 'g:i A') {
			$time_format_array = array('hours' => '1,12', 'am_pm' => true);
		} else {
			$time_format_array = array('hours' => '0,23', 'am_pm' => false);
		}
		$event_data = $this->get_event_data(array('field' => 'event_id', 'id' => $post->ID));

		$this->get_view()->render_html("events/metabox-event-data", array('event_data' => $event_data, 'args' => $metabox['args'], 'columns' => $data['columns'], 'date' => array('time_format' => $time_format_array)), true);
	}

	/**
	 * Render event options
	 *
	 * @param $post
	 * @param $metabox
	 */
	public function render_event_options($post, $metabox) {
		$this->get_view()->render_html("events/metabox-event-options", array('post' => $post), true);
	}

	/**
	 * Add column
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function set_event_columns($columns) {
		$columns = array_slice($columns, 0, 2, true) + array("mp-event_tag" => __('Tags', 'mp-timetable')) + array_slice($columns, 2, count($columns) - 1, true);
		$columns = array_slice($columns, 0, 2, true) + array("mp-event_category" => __('Categories', 'mp-timetable')) + array_slice($columns, 2, count($columns) - 1, true);

		return $columns;
	}

	/**
	 * Create event category admin link
	 *
	 * @param $column
	 */
	public function get_event_taxonomy($column) {
		global $post;
		if ($column === 'mp-event_category') {
			echo Taxonomy::get_instance()->get_the_term_filter_list($post, 'mp-event_category');
		}
		if ($column === 'mp-event_tag') {
			echo Taxonomy::get_instance()->get_the_term_filter_list($post, 'mp-event_tag');
		}
	}

	/**
	 * Save(insert) event  data
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public function save_event_data(array $params) {
		$rows_affected = array();
		if (!empty($params['event_data'])) {
			foreach ($params['event_data'] as $key => $event) {
				if (is_array($event['event_start']) && !empty($event['event_start'])) {
					for ($i = 0; $i < count($event['event_start']); $i++) {
						$rows_affected[] = $this->wpdb->insert($this->table_name, array(
								'column_id' => $key,
								'event_id' => $params['post']->ID,
								'event_start' => date('H:i:s', strtotime($event['event_start'][$i])),
								'event_end' => date('H:i:s', strtotime($event['event_end'][$i])),
								'user_id' => $event['user_id'][$i],
								'description' => $event['description'][$i]
							)
						);
					}
				}
			}
		}
		if (!empty($params['event_meta'])) {
			foreach ($params['event_meta'] as $meta_key => $meta) {
				update_post_meta($params['post']->ID, $meta_key, $meta);
			}
		}
		return $rows_affected;
	}

	/**
	 * Delete event data
	 *
	 * @param $id
	 *
	 * @return false|int
	 */
	public function delete_event($id) {
		return $this->wpdb->delete($this->table_name, array('id' => $id), array('%d'));
	}

	/**
	 * Get event data by post
	 *
	 * @param array $params
	 *
	 * @return array|null|object
	 */
	public function get_events_data(array $params) {
		$events = array();

		if ((!empty($params['all']) && $params['all']) || empty($params['list'])) {
			$sql_reguest = "SELECT * FROM " . $this->table_name;
		} elseif (!is_array($params['column'])) {
			if (isset($params['list']) && is_array($params['list'])) {
				$params['list'] = implode(',', $params['list']);
			}
			$sql_reguest = "SELECT * FROM `$this->table_name` WHERE " . $params['column'] . " IN (" . $params['list'] . ")";
		} elseif (is_array($params['column']) && is_array($params['column'])) {

			$sql_reguest = "SELECT * FROM `$this->table_name` WHERE ";
			$last_key = key(array_slice($params['column'], -1, 1, TRUE));
			foreach ($params['column'] as $key => $column) {
				if (isset($params['list'][$column]) && is_array($params['list'][$column])) {
					$params['list'][$column] = implode(',', $params['list'][$column]);
				}
				$sql_reguest .= $column . " IN (" . $params['list'][$column] . ")";
				$sql_reguest .= ($last_key != $key) ? ' AND ' : '';
			}

		}

		$events_data = $this->wpdb->get_results($sql_reguest);

		foreach ($events_data as $event) {
			$event->post = get_post($event->event_id);
			$event->event_start = date(get_option('time_format'), strtotime($event->event_start));
			$event->event_end = date(get_option('time_format'), strtotime($event->event_end));
			$events[] = $event;
		}
		return $events;
	}

	/**
	 * @param $event_category
	 *
	 * @return array|null|object
	 */
	public function get_events_data_by_category($event_category) {
		$terms = explode(',', $event_category);
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
		$event_data = $this->get_events_data(array('column' => 'event_id', 'list' => $ids));
		return $event_data;
	}

	/**
	 * Get widget events
	 *
	 * @param $instance
	 *
	 * @return array
	 */
	public function get_widget_events($instance) {
		$events = array();
		$weekday = strtolower(date('l', time()));
		$current_date = date('d/m/Y', time());
		$curent_time = date('H:i:s', current_time('timestamp'));
		if (!empty($instance['mp_categories'])) {
			$category_columns_ids = $this->get('column')->get_columns_by_event_category($instance['mp_categories']);
		}
		$args = array(
			'post_type' => 'mp-column',
			'fields' => 'ids',
			'post__in' => !empty($category_columns_ids) ? $category_columns_ids : '',
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'weekday',
					'value' => $weekday
				),
				array(
					'key' => 'option_day',
					'value' => $current_date
				)
			)
		);

		switch ($instance['view_settings']) {
			case'today':
			case 'current':
				$column_post_ids = get_posts($args);
				if (!empty($column_post_ids)) {
					$events = $this->get_events_data(array('column' => 'column_id', 'list' => $column_post_ids));
				}
				$events = $this->filter_events(array('events' => $events, 'view_settings' => $instance['view_settings'], 'time' => $curent_time));
				break;
			case 'all':
				if (!empty($instance['next_days']) && $instance['next_days'] > 0) {
					for ($i = 0; $i <= $instance['next_days']; $i++) {
						// set new day week
						$time = strtotime("+$i days");
						$args['meta_query'][0]['value'] = strtolower(date('l', $time));
						//set new date
						$args['meta_query'][1]['value'] = date('d/m/Y', $time);

						$column_post_ids = get_posts($args);
						if (!empty($column_post_ids)) {
							$day_events = $this->get_events_data(array('column' => 'column_id', 'list' => $column_post_ids));
							$events = array_merge($events, $day_events);
						}
						if ($i === 0) {
							$events = $this->filter_events(array('events' => $events, 'view_settings' => 'today', 'time' => $curent_time));
						}
					}


				}
				break;
			default:
				$column_post_ids = get_posts($args);
				if (!empty($column_post_ids)) {
					$events = $this->get_events_data(array('column' => 'column_id', 'list' => $column_post_ids));
				}
				$events = $this->filter_events(array('events' => $events, 'view_settings' => 'today', 'time' => $curent_time));
				break;
		}
		if ($instance['limit'] > 0) {
			$events = array_slice($events, 0, $instance['limit']);
		}
		return $events;
	}

	/**
	 * Get single event data by id
	 *
	 * @param array $params
	 *
	 * @return array|null|object|void
	 */
	public function get_event_data($params) {
		$event_data = $this->wpdb->get_results("SELECT * FROM $this->table_name WHERE {$params["field"]} = {$params['id']}");
		foreach ($event_data as $key => $event) {
			$event_data[$key]->event_start = date(get_option('time_format'), strtotime($event_data[$key]->event_start));
			$event_data[$key]->event_end = date(get_option('time_format'), strtotime($event_data[$key]->event_end));
			$event_data[$key]->user = get_user_by('id', $event_data[$key]->user_id);
			$event_data[$key]->post = get_post($event_data[$key]->event_id);
		}
		return $event_data;
	}

	/**
	 * Update event data
	 *
	 * @param $data
	 *
	 * @return false|int
	 */
	public function update_event_data($data) {
		$result = $this->wpdb->update(
			$this->table_name,
			array(
				'event_start' => date('H:i:s', strtotime($data['event_start'])),
				'event_end' => date('H:i:s', strtotime($data['event_end'])),
				'description' => $data['description'],
				'column_id' => $data['weekday_ids'],
				'user_id' => $data['user_id'],
			),
			array('id' => $data['id']),
			array(
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
			),
			array('%d')
		);
		return $result;
	}

	/**
	 * Get all events
	 * @return array
	 */
	public function get_all_events() {
		$args = array(
			'post_type' => 'mp-event',
			'post_status' => 'any',
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1
		);
		return get_posts($args);
	}

	/**
	 * Filtered events by view settings
	 *
	 * @param $params
	 *
	 * @return array
	 */
	protected function filter_events($params) {
		$events = array();
		if (!empty($params['events'])) {
			foreach ($params['events'] as $key => $event) {
				if ($params['view_settings'] === 'today' || $params['view_settings'] === 'all') {
					if (strtotime($event->event_end) <= strtotime($params['time'])) {
						continue;
					}
				} elseif ($params['view_settings'] === 'current') {
					if ((strtotime($event->event_end) >= strtotime($params['time']) && strtotime($params['time']) <= strtotime($event->event_start)) || strtotime($event->event_end) <= strtotime($params['time'])) {
						continue;
					}
				}
				$events[$key] = $event;
			}
		}
		return $events;
	}

	/**
	 * Choose color widget or event
	 *
	 * @param $params
	 *
	 * @return string
	 */

	public function choose_event_color($params) {
		if (!empty($params['widget_color'])) {
			return $params['widget_color'];
		} elseif (!empty($params['event_color'])) {
			return $params['event_color'];
		} else {
			return '';
		}
	}
}