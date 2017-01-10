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
	
	$amount_rows = 23 / $mptt_shortcode_data[ 'params' ][ 'increment' ];
	
	$data[ 'table_header' ] = mptt_get_header_row($mptt_shortcode_data);
	
	for ($row_index = $bounds[ 'start' ]; $row_index <= $bounds[ 'end' ]; $row_index++) {
		
		$tm = $row_index * $mptt_shortcode_data[ 'params' ][ 'increment' ];
		
		if (floor($tm) == $tm) {
			$table_cell_start = $tm . ':00';
		} else {
			if ($amount_rows == 46) {
				$table_cell_start = floor($tm) . ':30';
			} else {
				$tm_position = explode('.', $tm);
				
				if ($tm_position[ 1 ] == 25) {
					$mnts = ':15';
				} elseif ($tm_position[ 1 ] == 5) {
					$mnts = ':30';
				} else {
					$mnts = ':45';
				}
				
				$table_cell_start = floor($tm) . $mnts;
			}
		}
		$row_events = mptt_get_row_events($column_events, $row_index);
		
		if ($mptt_shortcode_data[ 'params' ][ 'group' ]) {
			
			//group event in cell( column and row intersection )
			//$row_events = mptt_group_identical_event($row_events);
			// group events by row
			$row_events = mptt_group_identical_row_events($row_events);
		}
		
		$data[ 'rows' ][ $row_index ][ 'events' ] = $row_events;
		$data[ 'rows' ][ $row_index ][ 'show' ] = true;
		
		if (!$mptt_shortcode_data[ 'params' ][ 'hide_hrs' ]) {
			array_unshift($data[ 'rows' ][ $row_index ][ 'events' ], array('event' => false, 'title' => date(get_option('time_format'), strtotime($table_cell_start))));
		}
		
		if ($mptt_shortcode_data[ 'params' ][ 'hide_empty_rows' ]) {
			$show = false;
			
			foreach ($data[ 'rows' ][ $row_index ][ 'events' ] as $event) {
				if (isset($event[ 'id' ]) && $event[ 'id' ]) {
					$show = true;
					break;
				}
			}
			
			$data[ 'rows' ][ $row_index ][ 'show' ] = $show;
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
		
		if (!empty($events_array[ $column_id ])) {
			
			foreach ($events_list as $key_events => $item) {
				
				if ($bounds[ 'end' ] <= $item->end_index && $bounds[ 'start' ] >= $item->start_index) {
					continue;
				} else {
					$key_count = $item->start_index . '_' . $item->event_id . '_' . $item->end_index;
					
					if (!isset($output_array[ $key_count ])) {
						$output_array[ $key_count ] = array('count' => 0, 'output' => false);
						$output_array[ $key_count ][ 'count' ]++;
					} else {
						$output_array[ $key_count ][ 'count' ]++;
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
	$show_hrs = !$mptt_shortcode_data[ 'params' ][ 'hide_hrs' ];
	
	if ($show_hrs):
		$header_array[ 0 ] = array('output' => true, 'id' => '', 'title' => '');
	endif;
	
	foreach ($mptt_shortcode_data[ 'events_data' ][ 'column' ] as $column):
		$header_array[] = array('output' => true, 'id' => $column->ID, 'title' => $column->post_title);
	endforeach;
	
	return $header_array;
}

/**
 * Row events
 *
 * @param $column_events
 * @param $row_index
 *
 * @return array
 */
function mptt_get_row_events($column_events, $row_index) {
	$events = array();
	$default_count = 1;
	foreach ($column_events as $column_id => $events_list) {
		$empty = true;
		$cell = $row_index . '_' . $column_id;
		foreach ($events_list as $key_events => $item) {
			
			if ($item->start_index == $row_index) {
				
				$temp = (array)$item;
				if ($temp[ 'id' ]) {
					unset($temp[ 'id' ]);
					unset($temp[ 'column_id' ]);
				}
				$events[ $cell ][] = array(
					'id' => $item->id,
					'hash' => md5(serialize($temp)),
					'count' => $default_count,
					'start_index' => ($item->start_index),
					'end_index' => ($item->end_index),
					'event_start' => ($item->event_start),
					'event_end' => ($item->event_end),
					'column_id' => $item->column_id,
					'event_id' => $item->event_id,
					'event' => true,
					'user_id' => $item->user_id,
					'description' => trim($item->description)
				);
				$empty = false;
			}
		}
		if ($empty) {
			$events[ $cell ][] = array(
				'id' => false,
				'start_index' => $row_index,
				'end_index' => $row_index,
				'column_id' => $column_id,
				'event' => true,
			);
		}
	}
	
	return $events;
}

/**
 * @param $mptt_shortcode_data
 *
 * @param $post
 *
 * @return array
 */
function mptt_get_columns_events($mptt_shortcode_data, $post) {
	if ($post === 'all') {
		$column_events = $mptt_shortcode_data[ 'events_data' ][ 'column_events' ];
		
		return $column_events;
	} else {
		$column_events = array();
		
		foreach ($mptt_shortcode_data[ 'events_data' ][ 'column_events' ] as $col_id => $col_events) {
			$column_events[ $col_id ] = array_filter(
				$col_events,
				function ($val) use ($post) {
					return $post->ID == $val->event_id;
				});
		}
		
		return $column_events;
	}
}

/**
 * Checking identical event in cell
 *
 * @param $needle
 * @param $events
 *
 * @return bool
 */
function mptt_check_exists_column($needle, $events) {
	$exist = false;
	$const_available_difference = 1;
	
	foreach ($events as $key => $event) {
		$difference_data = array_diff($needle, $event);
		if (isset($difference_data[ 'id' ]) && (count($difference_data) === $const_available_difference)) {
			$exist = true;
			break;
		}
	}
	
	return $exist;
}

/**
 * Group event in row
 *
 * @param $events
 *
 * @return array|mixed
 */
function mptt_group_identical_row_events($events) {
	$data = array();
	$default_count = 1;
//	foreach ($column_events as $column_id => $events_list) {
	foreach ($events as $cell => $events_in_cell) {
		$events[ $cell ] = mptt_group_identical_event($events_in_cell);
//		$temp = $event;

//		if ($temp[ 'id' ]) {
//			unset($temp[ 'id' ]);
//			unset($temp[ 'column_id' ]);
//		}
//		$events[ $key ][ 'hash' ] = md5(serialize($temp));
//		$events[ $key ][ 'order' ] = $key;
//		$events[ $key ][ 'end_row_index' ] = $key;
//		$events[ $key ][ 'count' ] = $default_count;
//		$events[ $key ][ 'start_row_index' ] = $key;
		
	}
//	}


//	$events = grouped_by_column($events);

//	usort($events, function ($a, $b) {
//		if ($a[ 'order' ] == $b[ 'order' ]) {
//			return 0;
//		}
//
//		return ($a[ 'order' ] < $b[ 'order' ]) ? -1 : 1;
//	});
//
//	end($events);
//	$last_key = key($events);
//	$data = $events;
//
//	foreach ($events as $key => $event) {
//
//		if (!isset($current_hash)) {
//			$current_hash = $events[ $key ][ 'hash' ];
//			$first_hash_element_key = $key;
//		}
//
//		if ($current_hash === $events[ $key ][ 'hash' ]) {
//
//			$next_key = $key + 1;
//
//			if (isset($events[ $next_key ])) {
//
//				if ($current_hash === $events[ $next_key ][ 'hash' ]) {
//
//					$data[ $first_hash_element_key ][ 'count' ]++;
//					$data[ $first_hash_element_key ][ 'end_row_index' ] = $key;
//					$data[ $first_hash_element_key ][ 'grouped' ] = true;
//					unset($data[ $next_key ]);
//
//				}
//			}
//
//		} else {
//			$current_hash = $events[ $key ][ 'hash' ];
//			$first_hash_element_key = $key;
//		}
//	}
//
	
	return $events;
}

/**
 * Check exists grouped attribute
 *
 * @param $event_item
 *
 * @return string
 */
function mptt_is_grouped_event_class($event_item) {
	return (isset($event_item[ 'grouped' ]) && $event_item[ 'grouped' ]) ? 'mptt-grouped-event' : '';
}

/**
 *
 * @param $events
 *
 * @return array
 */
function grouped_by_column($events) {
	$data = array();
	
	foreach ($events as $key => $event) {
		if (!$event[ 'event' ]) {
			$data[] = $event;
			continue;
		}
		if (isset($data[ $event[ 'column_id' ] ])) {
			$data[ $event[ 'column_id' ] ][ 'related_by_column' ][] = $event;
		} else {
			$data[ $event[ 'column_id' ] ] = $event;
		}
		
	}
	
	return $data;
}