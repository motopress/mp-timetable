<?php
use mp_timetable\plugin_core\classes\View;

/**
 * Sidebar
 */
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
		
		$row_cells = mptt_get_row_events($column_events, $row_index);
		
		if ($mptt_shortcode_data[ 'params' ][ 'group' ]) {
			$row_cells = mptt_group_identical_row_events($row_cells);
		}
		
		$data[ 'rows' ][ $row_index ][ 'cells' ] = $row_cells;
		$data[ 'rows' ][ $row_index ][ 'show' ] = true;
		
		if (!$mptt_shortcode_data[ 'params' ][ 'hide_hrs' ]) {
			array_unshift($data[ 'rows' ][ $row_index ][ 'cells' ], array('time_cell' => true, 'title' => date(get_option('time_format'), strtotime($table_cell_start))));
		}
		
		if ($mptt_shortcode_data[ 'params' ][ 'hide_empty_rows' ]) {
			foreach ($data[ 'rows' ][ $row_index ][ 'cells' ] as $cell_key => $cell) {
				foreach ($cell as $cell_event) {
					if (!empty($cell_event[ 'id' ]) && filter_var($cell_event[ 'id' ], FILTER_VALIDATE_INT)) {
						$data[ 'rows' ][ $row_index ][ $cell_key ][ 'show' ] = true;
						break;
					}
				}
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
	$i = 0;
	foreach ($column_events as $column_id => $events_list) {
		$empty = true;
		
		foreach ($events_list as $event_key => $item) {
			
			if ($item->start_index == $row_index) {
				
				$temp = (array)$item;
				
				if ($temp[ 'id' ]) {
					unset($temp[ 'id' ]);
					unset($temp[ 'column_id' ]);
				}
				
				$event = array(
					'id' => $item->id,
					'hash' => md5(serialize($temp)),
					'start_index' => ($item->start_index),
					'end_index' => ($item->end_index),
					'event_start' => ($item->event_start),
					'event_end' => ($item->event_end),
					'column_id' => $item->column_id,
					'event_id' => $item->event_id,
					'event' => true,
					'user_id' => $item->user_id,
					'description' => trim($item->description),
					'order' => $event_key
				);
				
				$events[ $i ][ 'events' ][ $event[ 'hash' ] ] = $event;
				$events[ $i ][ 'count' ] = $default_count;
				$events[ $i ][ 'grouped' ] = false;
				$events[ $i ][ 'column_id' ] = $column_id;
				
				$empty = false;
			}
		}
		
		if ($empty) {
			$events[ $i ][ 'events' ][] = array(
				'id' => false,
				'start_index' => $row_index,
				'end_index' => $row_index,
				'column_id' => $column_id,
				'event' => true,
			);
			$events[ $i ][ 'count' ] = $default_count;
			$events[ $i ][ 'column_id' ] = $column_id;
		}
		$i++;
	}
	
	foreach ($events as $cell => $event_data) {
		usort($event_data[ 'events' ], function ($a, $b) {
			if ($a[ 'order' ] == $b[ 'order' ]) {
				return 0;
			}
			
			return ($a[ 'order' ] < $b[ 'order' ]) ? -1 : 1;
		});
		$events[ $cell ][ 'events' ] = $event_data[ 'events' ];
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
 * @param $events_row_data
 *
 * @return array|mixed
 */
function mptt_group_identical_row_events($events_row_data) {
	
	$length = count($events_row_data);
	
	for ($i = 0; $i < $length - 1; $i++) {
		if (!isset($events_row_data[ ($i + 1) ])) {
			continue;
		}
		
		$events_current_data = $events_row_data[ $i ];
		$events_current_count = count($events_current_data[ 'events' ]);
		
		$events_next_data = $events_row_data[ ($i + 1) ];
		$events_next_count = count($events_next_data[ 'events' ]);
		
		
		if ($events_next_count > 1 || $events_current_count > 1) {
			continue;
		} else {
			if (filter_var($events_current_data[ 'events' ][ 0 ][ 'id' ], FILTER_VALIDATE_INT) && filter_var($events_next_data[ 'events' ][ 0 ][ 'id' ], FILTER_VALIDATE_INT)) {
				
				$hash_current = $events_current_data[ 'events' ][ 0 ][ 'hash' ];
				$hash_next = $events_next_data[ 'events' ][ 0 ][ 'hash' ];
				
				if (!isset($current_data)) {
					$current_data = array('key' => $i, 'hash' => $hash_current);
				}
				
				if ($hash_current === $hash_next) {
					
					if ($current_data[ 'hash' ] !== $hash_current) {
						$current_data = array('key' => $i, 'hash' => $hash_current);
					}
					
					if ($current_data[ 'key' ] === $i) {
						$events_current_data[ 'count' ]++;
						$events_current_data[ 'grouped' ] = true;
						$events_row_data[ $i ] = $events_current_data;
						$events_row_data[ $i + 1 ][ 'hide' ] = true;
					} else {
						$events_row_data[ $current_data[ 'key' ] ][ 'count' ]++;
						$events_row_data[ $i + 1 ][ 'hide' ] = true;
					}
					
				}
			}
		}
	}
	
	return $events_row_data;
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