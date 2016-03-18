<?php

namespace mp_timetable\plugin_core\classes;

use mp_timetable\classes\models\Column;
use Mp_Time_Table;

class Shortcode extends Core {

	protected static $instance;

	public function __construct() {
		$this->init_plugin_version();
		parent::__construct();
	}

	/**
	 * Return instance
	 * @return Shortcode
	 */
	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Init
	 */
	public function init() {
		add_shortcode('mp-timetable', array($this, "show_shortcode"));
	}

	/**
	 * Show shortcode
	 *
	 * @param $params
	 *
	 * @return mixed
	 */
	public function show_shortcode($params) {
		global $mptt_shortcode_data;
		$this->add_plugin_js('shortcode');
		$this->add_plugin_css('shortcode');
		if (empty($params)) {
			$params = array();
		}
		$mptt_shortcode_data = array();
		$mptt_shortcode_data['params'] = shortcode_atts(array(
			'events' => "",
			'event_categ' => "",
			'col' => "",
			'increment' => "1",
			'view' => "dropdown_list",
			'label' => __("All Events", "mp-timetable"),
			'hide_label' => "0",
			'title' => "0",
			'time' => "0",
			'sub-title' => "0",
			'description' => "0",
			'user' => "0",
			'hide_hrs' => "0",
			'hide_empty_rows' => "1",
			'row_height' => "31",
			'disable_event_url' => "0",
			'text_align' => "center",
			'id' => "",
			'responsive' => "1"
		), $params);

		$mptt_shortcode_data['events_data'] = $this->get_shortcode_events($params);

		if (!empty($mptt_shortcode_data['events_data'])) {
			foreach ($mptt_shortcode_data['events_data']['events'] as $event) {
				$mptt_shortcode_data['unique_events'][$event->event_id] = $event;
			}
		}
		return $this->get_view()->render_html('shortcodes/index-timetable', array(), false);
	}

	/**
	 * Get shortcode events
	 *
	 * @param $params
	 *
	 * @return array
	 */
	public function get_shortcode_events($params) {
		$events_data = array('events' => array(), 'column' => array());
		$columns_ids = array();
		$step = $params['increment'] === '1' ? 60 : (60 * $params['increment']);
		//get event by id
		if (!empty($params['events'])) {
			$events_data['events'] = $events_data['events'] + $this->get('events')->get_events_data(array('column' => 'event_id', 'list' => $params['events']));
		}
		// get event by category
		if (!empty($params['event_categ'])) {
			$category_events = $this->get('events')->get_events_data_by_category($params['event_categ']);
			$events_data['events'] = $events_data['events'] + $category_events;
		}
		// get event by column
		if (!empty($params['col']) && empty($params['event_categ'])) {
			$column_events = $this->get('events')->get_events_data(array('column' => 'column_id', 'list' => $params['col']));
			$events_data['events'] = $events_data['events'] + $column_events;
		}
		//if all params empty
		if (empty($params['col']) && empty($params['event_categ']) && empty($params['events'])) {
			$events_data['events'] = $events_data['events'] + $this->get('events')->get_events_data(array('column' => 'event_id', 'all' => true));
			$events_data['events'] = $events_data['events'] + $this->get('events')->get_events_data_by_category('');
			$events_data['events'] = $events_data['events'] + $this->get('events')->get_events_data(array('column' => 'column_id', 'all' => true));
		}

		//Create column array;
		if (!empty($events_data['events'])) {

			if (empty($params['col'])) {
				foreach ($events_data['events'] as $event) {
					$columns_ids[] = $event->column_id;
				}
				$columns_ids = array_unique($columns_ids);
			} else {
				$columns_ids = explode(',', $params['col']);
			}

			//Sort column by menu order
			$events_data['column'] = Column::get_instance()->get_all_column(array('post__in' => $columns_ids));

			foreach ($events_data['column'] as $key => $column) {
				$column_events = array();
				// add to column  events
				foreach ($events_data['events'] as $event_key => $event) {
					if ($column->ID == $event->column_id) {
						$start_index = date('G', strtotime($event->event_start)) / $params['increment'] + floor(date('i', strtotime($event->event_start)) / $step);
						$end_index = date('G', strtotime($event->event_end)) / $params['increment'] + ceil(date('i', strtotime($event->event_end)) / $step) + (date('i', strtotime($event->event_end)) == $step ? 1 : 0);
						$event->output = false;
						$event->start_index = $start_index;
						$event->end_index = $end_index;
						$column_events[$event->id] = $event;
					}
				}
				//sort by start date
				usort($column_events, function ($a, $b) {
					if (strtotime($a->event_start) == strtotime($b->event_start)) {
						return 0;
					}
					return (strtotime($a->event_start) < strtotime($b->event_start)) ? -1 : 1;

				});
				$events_data['column_events'][$column->ID] = $column_events;
			}

		}
		return $events_data;
	}
}