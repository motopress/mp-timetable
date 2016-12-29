<?php
use mp_timetable\plugin_core\classes\View;

function mptt_sidebar() {
	global $post;
	View::get_instance()->get_template('templates-actions/action-sidebar', array('post' => $post));
}

/**
 * Make data shortcode
 *
 * @param $bounds
 * @param $mptt_shortcode_data
 * @param $column_events
 *
 * @return array
 */
function mptt_make_data_shortcode($bounds, $mptt_shortcode_data, $column_events) {
	$data = array();
	$mptt_shortcode_data['params']['group'] = true;
	$amount_rows = 23 / $mptt_shortcode_data['params']['increment'];

	$data['table_header'] = mptt_get_header_row($mptt_shortcode_data);

	for ($row_index = $bounds['start']; $row_index <= $bounds['end']; $row_index++) {

		$tm = $row_index * $mptt_shortcode_data['params']['increment'];

		if (floor($tm) == $tm) {
			$table_cell_start = $tm . ':00';
		} else {
			if ($amount_rows == 46) {
				$table_cell_start = floor($tm) . ':30';
			} else {
				$tm_position = explode('.', $tm);

				if ($tm_position[1] == 25) {
					$mnts = ':15';
				} elseif ($tm_position[1] == 5) {
					$mnts = ':30';
				} else {
					$mnts = ':45';
				}

				$table_cell_start = floor($tm) . $mnts;
			}
		}
		$row_events = mptt_get_row_events($column_events, $row_index);

		if ($mptt_shortcode_data['params']['group']) {
			$row_events = mptt_group_identical_event($row_events);
		}

		if (!empty($row_events)) {
			$data[$row_index] = $row_events;

			if (!$mptt_shortcode_data['params']['hide_hrs']) {
				array_unshift($data[$row_index], array('event' => false, 'title' => date(get_option('time_format'), strtotime($table_cell_start))));
			}
		} else {
			if (!$mptt_shortcode_data['params']['hide_hrs']) {
				$data[$row_index][] = array('0' => array('event' => false, 'title' => date(get_option('time_format'), strtotime($table_cell_start))));
			}
		}
	}

	return $data;
}

/**
 * Add group attribute to event
 *
 * @param $bounds
 * @param $events_array
 *
 * @return mixed
 */
function group_events($bounds, $events_array) {
	$output_array = array();

	foreach ($events_array as $column_id => $events_list) {

		if (!empty($events_array[$column_id])) {

			foreach ($events_list as $key_events => $item) {

				if ($bounds['end'] <= $item->end_index && $bounds['start'] >= $item->start_index) {
					continue;
				} else {
					$key_count = $item->start_index . '_' . $item->event_id . '_' . $item->end_index;

					if (!isset($output_array[$key_count])) {
						$output_array[$key_count] = array('count' => 0, 'output' => false);
						$output_array[$key_count]['count']++;
					} else {
						$output_array[$key_count]['count']++;
					}

				}
			}
		}
	}

	return $output_array;
}

/**
 * Header row
 *
 * @param $mptt_shortcode_data
 *
 * @return array
 */
function mptt_get_header_row($mptt_shortcode_data) {
	$header_array = array('0' => array('output' => false));
	$show_hrs = !$mptt_shortcode_data['params']['hide_hrs'];

	if ($show_hrs):
		$header_array[0] = array('output' => true);
	endif;

	foreach ($mptt_shortcode_data['events_data']['column'] as $column):
		$header_array[] = array('output' => true, 'id' => $column->ID, 'title' => $column->post_title);
	endforeach;

	return $header_array;
}

function mptt_get_row_events($column_events, $row_index) {
	$events = array();
	foreach ($column_events as $column_id => $events_list) {
		foreach ($events_list as $key_events => $item) {
			if ($item->start_index == $row_index) {
				$events[] = array(
					'start_index' => ($item->start_index),
					'end_index' => ($item->end_index),
					'item' => $item,
					'column_id' => $item->column_id,
					'event_id' => $item->event_id,
					'count' => 1,
					'start_group_index' => $key_events,
					'end_group_index' => $key_events,
					'event' => true
				);
			}
		}
	}
	return $events;
}

/**
 * @param $mptt_shortcode_data
 * @param $post
 *
 * @return array
 */
function mptt_get_columns_events($mptt_shortcode_data, $post) {
	if ($post === 'all') {
		$column_events = $mptt_shortcode_data['events_data']['column_events'];
		return $column_events;
	} else {
		$column_events = array();

		foreach ($mptt_shortcode_data['events_data']['column_events'] as $col_id => $col_events) {
			$column_events[$col_id] = array_filter(
				$col_events,
				function ($val) use ($post) {
					return $post->ID == $val->event_id;
				});
		}
		return $column_events;
	}
}

function mptt_group_identical_event($row_events) {
	$events = array();



	foreach ($row_events as $key => $event) {
		if (!$event['event']) {
			continue;
		} else {

		}

	}

	return $events;
}