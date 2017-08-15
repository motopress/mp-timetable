<?php
use mp_timetable\classes\models\Settings;
use mp_timetable\plugin_core\classes\View;

/**
 * Before content
 */
function mptt_shortcode_template_before_content() {
	global $mptt_shortcode_data;
	$wrapper_class = mptt_popular_theme_class();
	$id            = empty( $mptt_shortcode_data[ 'params' ][ 'id' ] ) ? '' : $mptt_shortcode_data[ 'params' ][ 'id' ];
	?>
	<div <?php if ( !empty($id) ) echo 'id="' . $id . '" '; ?>class="<?php echo apply_filters( 'mptt_shortcode_wrapper_class', 'mptt-shortcode-wrapper' . $wrapper_class . ( $mptt_shortcode_data[ 'params' ][ 'responsive' ] == '0' ? ' mptt-table-fixed' : ' mptt-table-responsive' ) ) ?>">
	<?php
}

/**
 * After content
 */
function mptt_shortcode_template_after_content() { ?>
	</div>
<?php }

/**
 * Filter contents
 */
function mptt_shortcode_template_content_filter() {
	global $mptt_shortcode_data;
	$unique_events = empty( $mptt_shortcode_data[ 'unique_events' ] ) ? array() : $mptt_shortcode_data[ 'unique_events' ];
	$style         = '';
	if ( count( $mptt_shortcode_data[ 'events_data' ][ 'unique_events' ] ) < 2 ) {
		$style = ' style="display:none;"';
	}
	$display_label = $mptt_shortcode_data[ 'params' ][ 'hide_label' ] ? 'display: none' : '';
	
	if ( $mptt_shortcode_data[ 'params' ][ 'view' ] == 'dropdown_list' ) { ?>
		<select class="<?php echo apply_filters( 'mptt_shortcode_navigation_select_class', 'mptt-menu mptt-navigation-select' ) ?>"<?php echo $style ?>>
			<?php if ( ! $mptt_shortcode_data[ 'params' ][ 'hide_label' ] ) { ?>
				<option value="all"><?php echo ( strlen( trim( $mptt_shortcode_data[ 'params' ][ 'label' ] ) ) ) ? trim( $mptt_shortcode_data[ 'params' ][ 'label' ] ) : __( 'All Events', 'mp-timetable' ) ?></option>
			<?php } else { ?>
				<option value="all"></option>
			<?php }
			if ( ! empty( $unique_events ) ):
				foreach ( $unique_events as $event ): ?>
					<option value="<?php echo $event->post->post_name ?>"><?php echo $event->post->post_title ?></option>
				<?php endforeach;
			endif; ?>
		</select>
	<?php } elseif ( $mptt_shortcode_data[ 'params' ][ 'view' ] == 'tabs' ) { ?>
		<ul class="<?php echo apply_filters( 'mptt_shortcode_navigation_tabs_class', 'mptt-menu mptt-navigation-tabs' ) ?>" <?php echo $style ?>>
			<li style="<?php echo $display_label ?>">
				<a title="<?php echo ( strlen( trim( $mptt_shortcode_data[ 'params' ][ 'label' ] ) ) ) ? trim( $mptt_shortcode_data[ 'params' ][ 'label' ] ) : __( 'All Events', 'mp-timetable' ) ?>"
				   href="#all" onclick="event.preventDefault();"><?php echo ( strlen( trim( $mptt_shortcode_data[ 'params' ][ 'label' ] ) ) ) ? trim( $mptt_shortcode_data[ 'params' ][ 'label' ] ) : __( 'All Events', 'mp-timetable' ) ?>
				</a>
			</li>
			<?php if ( ! empty( $unique_events ) ): ?>
				<?php foreach ( $unique_events as $event ): ?>
					<li><a title="<?php echo $event->post->post_title ?>" href="#<?php echo $event->post->post_name ?>" onclick="event.preventDefault();"><?php echo $event->post->post_title ?></a></li>
				<?php endforeach;
			endif; ?>
		</ul>
	<?php }
}

/**
 * Content static table
 */
function mptt_shortcode_template_content_static_table() {
	global $mptt_shortcode_data;
	
	mptt_shortcode_template_event( $mptt_shortcode_data );
	
	if ( isset( $mptt_shortcode_data[ 'unique_events' ] ) && is_array( $mptt_shortcode_data[ 'unique_events' ] ) ) {
		foreach ( $mptt_shortcode_data[ 'unique_events' ] as $ev ) {
			mptt_shortcode_template_event( $mptt_shortcode_data, $ev->post );
		}
	}
}

/**
 * Event template
 *
 * @param $mptt_shortcode_data
 * @param mixed $post
 */
function mptt_shortcode_template_event( $mptt_shortcode_data, $post = 'all' ) {
	$params = $mptt_shortcode_data[ 'params' ];
	
	$column_events = mptt_get_columns_events( $mptt_shortcode_data, $post );
	
	// Get  start / end  row index
	$bounds = mptt_shortcode_get_table_cell_bounds( $column_events, $params );
	
	$hide_empty_rows = $params[ 'hide_empty_rows' ];
	$font_size       = ! empty( $params[ 'font_size' ] ) ? ' font-size:' . $params[ 'font_size' ] . ';' : '';
	$row_height      = $params[ 'row_height' ];
	$table_class     = apply_filters( 'mptt_shortcode_static_table_class', 'mptt-shortcode-table' ) . ' ' . $params[ 'custom_class' ];
	$table_class     .= Settings::get_instance()->is_plugin_template_mode() ? '' : ' mptt-theme-mode';
	
	$data_grouped_by_row = mptt_make_data_shortcode( $bounds, $mptt_shortcode_data, $column_events );
	
	?>
	<table class="<?php echo ! empty( $table_class ) ? $table_class : ''; ?>" id="#<?php echo is_object( $post ) ? $post->post_name : $post; ?>" style="display:none; <?php echo $font_size; ?>" data-hide_empty_row="<?php echo $hide_empty_rows; ?>">
		<?php echo View::get_instance()->get_template_html( 'shortcodes/table-header', array( 'header_items' => $data_grouped_by_row[ 'table_header' ], 'params' => $params ) ); ?>
		<tbody>
		<?php if ( isset( $data_grouped_by_row[ 'rows' ] ) && is_array( $data_grouped_by_row[ 'rows' ] ) ) {
			
			foreach ( $data_grouped_by_row[ 'rows' ] as $row_key => $row ) {
				if ( ! $row[ 'show' ] && $params[ 'hide_empty_rows' ] ) {
					continue;
				} ?>
				<tr class="mptt-shortcode-row-<?php echo $row_key ?>" data-index="<?php echo $row_key ?>">
					<?php $cells = $data_grouped_by_row[ 'rows' ][ $row_key ][ 'cells' ];
					
					foreach ( $cells as $key_event => $cell ) {
						
						if ( isset( $cell[ 'time_cell' ] ) && filter_var( $cell[ 'time_cell' ], FILTER_VALIDATE_BOOLEAN, array( 'options' => array( 'default' => false ) ) ) ) { ?>
							<td class="mptt-shortcode-hours" style="<?php echo 'height:' . $row_height . 'px;'; ?>"><?php echo $cell[ 'title' ] ?></td>
							<?php continue;
						}
						
						if ( ! $cell[ 'hide' ] ) { ?>
							<td class="mptt-shortcode-event <?php echo mptt_is_grouped_event_class( $cell ) ?> mptt-event-vertical-<?php echo $params[ 'text_align_vertical' ] ?>" data-column-id="<?php echo $cell[ 'column_id' ] ?>" colspan="<?php echo ! isset( $cell[ 'count' ] ) ? '' : $cell[ 'count' ] ?>" data-row_height="<?php echo $row_height; ?>" style="<?php echo 'height:' . $row_height . 'px;'; ?>">
								<?php
								$height = 100 / count( $cell[ 'events' ] );
								$top    = 0;
								foreach ( $cell[ 'events' ] as $event ) {
									
									if ( ! empty( $event[ 'id' ] ) && filter_var( $event[ 'id' ], FILTER_VALIDATE_INT ) ) {
										View::get_instance()->get_template( 'shortcodes/event-container', array( 'item' => $event, 'params' => $params, 'height' => $height, 'top' => $top ) );
										$top += $height;
									}
									
								} ?>
							</td>
						<?php }
					} ?>
				</tr>
			<?php }
			
		} ?>
		</tbody>
	</table>
	<?php
}

/**
 * Check row has items
 *
 * @param $i
 *
 * @param $column_events
 *
 * @return bool
 */
function mptt_shortcode_row_has_items( $i, $column_events ) {
	
	foreach ( $column_events as $column_id => $events_list ) {
		
		if ( ! empty( $column_events[ $column_id ] ) ) {
			foreach ( $events_list as $key_events => $item ) {
				if ( $item->start_index <= $i && $i < $item->end_index ) {
					return true;
				}
			}
		}
		
	}
	
	return false;
}

/**
 * Get table cell bounds
 *
 * @param $column_events
 * @param $params
 *
 * @return array
 */
function mptt_shortcode_get_table_cell_bounds( $column_events, $params ) {
	$hide_empty_rows = $params[ 'hide_empty_rows' ];
	
	if ( $hide_empty_rows ) {
		$min = - 1;
		$max = - 1;
		foreach ( $column_events as $events ) {
			foreach ( $events as $item ) {
				if ( isset($item->start_index) && isset($item->end_index) ) {
					$min = ( $min === - 1 ) ? $item->start_index : $min;
					$max = ( $max === - 1 ) ? $item->end_index : $max;
					$min = ( $item->start_index < $min ) ? $item->start_index : $min;
					$max = ( $item->end_index > $max ) ? $item->end_index : $max;
				}
			}
		}
	} else {
		$min = 0;
		$max = 23 / $params[ 'increment' ];
	}
	
	return array( 'start' => $min, 'end' => $max );
}

/**
 * Content responsive table
 */
function mptt_shortcode_template_content_responsive_table() {
	global $mptt_shortcode_data;
	if ( $mptt_shortcode_data[ 'params' ][ 'responsive' ] ) { ?>
		<div class="<?php echo apply_filters( 'mptt_shortcode_list_view_class', 'mptt-shortcode-list' ) . ' ' . $mptt_shortcode_data[ 'params' ][ 'custom_class' ] ?>">
			<?php if ( ! empty( $mptt_shortcode_data[ 'events_data' ] ) ):
				foreach ( $mptt_shortcode_data[ 'events_data' ][ 'column' ] as $column ): ?>
					<div class="mptt-column">
						<h3 class="mptt-column-title"><?php echo $column->post_title ?></h3>
						<ul class="mptt-events-list">
							<?php if ( ! empty( $mptt_shortcode_data[ 'events_data' ][ 'column_events' ][ $column->ID ] ) ):
								foreach ( $mptt_shortcode_data[ 'events_data' ][ 'column_events' ][ $column->ID ] as $event ) : ?>
									<li class="mptt-list-event" data-event-id="<?php echo $event->post->post_name ?>"
										<?php if ( ! empty( $event->post->color ) ) {
											echo 'style="border-left-color:' . $event->post->color . ';"';
										} ?>>
										<?php if ( $mptt_shortcode_data[ 'params' ][ 'title' ] ):
											$disable_url = (bool) $event->post->timetable_disable_url || (bool) $mptt_shortcode_data[ 'params' ][ 'disable_event_url' ];
											if ( ! $disable_url ) { ?>
												<a title="<?php echo $event->post->post_title; ?>"
												href="<?php echo ( $event->post->timetable_custom_url != "" ) ? $event->post->timetable_custom_url : get_permalink( $event->event_id ); ?>"
												class="mptt-event-title">
											<?php }
											echo $event->post->post_title;
											
											if ( ! $disable_url ) { ?>
												</a>
											<?php }
										
										endif;
										if ( $mptt_shortcode_data[ 'params' ][ 'time' ] ): ?>
											<p class="timeslot">
												<time datetime="<?php echo $event->event_start; ?>" class="timeslot-start"><?php echo date( get_option( 'time_format' ), strtotime( $event->event_start ) ); ?></time>
												<span class="timeslot-delimiter"><?php echo apply_filters( 'mptt_timeslot_delimiter', ' - ' ); ?></span>
												<time datetime="<?php echo $event->event_end; ?>" class="timeslot-end"><?php echo date( get_option( 'time_format' ), strtotime( $event->event_end ) ); ?></time>
											</p>
										<?php endif;
										if ( $mptt_shortcode_data[ 'params' ][ 'description' ] ): ?>
											<p class="event-description">
												<?php echo $event->description ?>
											</p>
										<?php endif;
										if ( $mptt_shortcode_data[ 'params' ][ 'user' ] && ( $event->user_id != '-1' ) ): ?>
											<p class="event-user"><?php $user_info = get_userdata( $event->user_id );
												if ( $user_info ) {
													echo get_avatar( $event->user_id, apply_filters( 'mptt-event-user-avatar-size', 24 ), '', $user_info->data->display_name ) . ' ';
													echo $user_info->data->display_name;
												} ?></p>
										<?php endif; ?>
									</li>
								<?php endforeach;
							endif; ?>
						</ul>
					</div>
				<?php endforeach;
			endif; ?>
		</div>
	<?php }
}

/**
 * Sidebar
 */
function mptt_sidebar() {
	global $post;
	View::get_instance()->get_template( 'templates-actions/action-sidebar', array( 'post' => $post ) );
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
function mptt_make_data_shortcode( $bounds, $mptt_shortcode_data, $column_events ) {
	$data        = array( 'rows' => array() );
	$amount_rows = 23 / $mptt_shortcode_data[ 'params' ][ 'increment' ];
	
	$data[ 'table_header' ] = mptt_get_header_row( $mptt_shortcode_data );
	
	foreach ( $column_events as $column_id => $events_list ) {
		
		foreach ( $events_list as $event_key => $item ) {
			if ( isset( $item->resolve ) ) {
				unset( $item->resolve );
			}
		}
	}
	
	for ( $row_index = $bounds[ 'start' ]; $row_index <= $bounds[ 'end' ]; $row_index ++ ) {
		
		$table_time_cell = get_time_cell( $row_index, $amount_rows, $mptt_shortcode_data[ 'params' ][ 'increment' ] );
		
		list( $row_cells, $column_events ) = mptt_get_row_events( $column_events, $row_index );
		
		if ( $mptt_shortcode_data[ 'params' ][ 'group' ] ) {
			$row_cells = mptt_group_identical_row_events( $row_cells );
		}
		
		$data[ 'rows' ][ $row_index ][ 'cells' ] = $row_cells;
		$data[ 'rows' ][ $row_index ][ 'show' ]  = mptt_shortcode_row_has_items( $row_index, $column_events );
		
		if ( ! $mptt_shortcode_data[ 'params' ][ 'hide_hrs' ] ) {
			array_unshift( $data[ 'rows' ][ $row_index ][ 'cells' ], array( 'time_cell' => true, 'title' => date( get_option( 'time_format' ), strtotime( $table_time_cell ) ) ) );
		}
	}
	
	return $data;
}

/**
 * Get time cell
 *
 * @param $row_index
 * @param $amount_rows
 * @param $increment
 *
 * @return string
 */
function get_time_cell( $row_index, $amount_rows, $increment ) {
	$tm = $row_index * $increment;
	
	if ( floor( $tm ) == $tm ) {
		$time = $tm . ':00';
		
		return $time;
	} else {
		if ( $amount_rows == 46 ) {
			$time = floor( $tm ) . ':30';
			
			return $time;
		} else {
			$tm_position = explode( '.', $tm );
			
			if ( $tm_position[ 1 ] == 25 ) {
				$mnts = ':15';
			} elseif ( $tm_position[ 1 ] == 5 ) {
				$mnts = ':30';
			} else {
				$mnts = ':45';
			}
			
			$time = floor( $tm ) . $mnts;
			
			return $time;
		}
	}
}

/**
 * Add group attribute to event
 *
 * @param $bounds
 * @param $events_array
 *
 * @return mixed
 */
function group_events( $bounds, $events_array ) {
	$output_array = array();
	
	foreach ( $events_array as $column_id => $events_list ) {
		
		if ( ! empty( $events_array[ $column_id ] ) ) {
			
			foreach ( $events_list as $key_events => $item ) {
				
				if ( $bounds[ 'end' ] <= $item->end_index && $bounds[ 'start' ] >= $item->start_index ) {
					continue;
				} else {
					$key_count = $item->start_index . '_' . $item->event_id . '_' . $item->end_index;
					
					if ( ! isset( $output_array[ $key_count ] ) ) {
						$output_array[ $key_count ] = array( 'count' => 0, 'output' => false );
						$output_array[ $key_count ][ 'count' ] ++;
					} else {
						$output_array[ $key_count ][ 'count' ] ++;
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
function mptt_get_header_row( $mptt_shortcode_data ) {
	$header_array = array( '0' => array( 'output' => false ) );
	$show_hrs     = ! $mptt_shortcode_data[ 'params' ][ 'hide_hrs' ];
	
	if ( $show_hrs ):
		$header_array[ 0 ] = array( 'output' => true, 'id' => '', 'title' => '' );
	endif;
	
	if ( ! empty( $mptt_shortcode_data[ 'events_data' ][ 'column' ] ) ) {
		foreach ( $mptt_shortcode_data[ 'events_data' ][ 'column' ] as $column ):
			$header_array[] = array( 'output' => true, 'id' => $column->ID, 'title' => $column->post_title );
		endforeach;
	}
	
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
function mptt_get_row_events( $column_events, $row_index ) {
	$events        = array();
	$default_count = 1;
	$i             = 0;
	
	foreach ( $column_events as $column_id => $events_list ) {
		$empty = true;
		
		foreach ( $events_list as $event_key => $item ) {
			if ( isset( $item->resolve ) && $item->resolve ) {
				
				continue;
			}
			$group = false;
			if ( ! empty( $current ) ) {
				if ( ( $item->end_index <= $current[ 'end_index' ] ) ) {
					$group = true;
				}
			}
			
			if ( $item->start_index == $row_index || $group ) {
				
				//create temp event data for generate hash
				$temp = (array) $item;
				
				//clear data before   make hash to compare
				if ( $temp[ 'id' ] ) {
					unset( $temp[ 'id' ] );
					unset( $temp[ 'column_id' ] );
				}
				
				$event = array(
					'id'          => $item->id,
					'hash'        => md5( serialize( $temp ) ),
					'start_index' => $item->start_index,
					'end_index'   => $item->end_index,
					'event_start' => $item->event_start,
					'event_end'   => $item->event_end,
					'column_id'   => $item->column_id,
					'event_id'    => $item->event_id,
					'event'       => true,
					'user_id'     => $item->user_id,
					'description' => trim( $item->description ),
					'order'       => $event_key,
					'hide'        => $group ? true : false
				);
				
				if ( ! $group ) {
					$events[ $i ][ 'events' ][ $event[ 'hash' ] ] = $event;
					$events[ $i ][ 'count' ]                      = $default_count;
					$events[ $i ][ 'grouped' ]                    = false;
					$events[ $i ][ 'column_id' ]                  = $column_id;
					$events[ $i ][ 'hide' ]                       = false;
				} else {
					
					$events[ $i ][ 'hide' ]                       = false;
					$events[ $i ][ 'column_id' ]                  = $column_id;
					$events[ $i ][ 'events' ][ $event[ 'hash' ] ] = $event;
					
				}
				
				$column_events[ $column_id ][ $event_key ]->resolve = true;
				
				$empty = false;
				
				if ( empty( $current ) ) {
					$current = array( 'start_index' => $item->start_index, 'end_index' => $item->end_index );
				} else {
					$current[ 'start_index' ] = $current[ 'start_index' ] >= $item->start_index ? $item->start_index : $current[ 'start_index' ];
					$current[ 'end_index' ]   = $current[ 'end_index' ] <= $item->end_index ? $item->end_index : $current[ 'end_index' ];
				}
			}
		}
		
		
		if ( $empty ) {
			
			$events[ $i ][ 'events' ][] = array(
				'id'          => false,
				'start_index' => $row_index,
				'end_index'   => $row_index,
				'column_id'   => $column_id,
				'event'       => true,
			);
			
			$events[ $i ][ 'count' ]     = $default_count;
			$events[ $i ][ 'column_id' ] = $column_id;
			$events[ $i ][ 'hide' ]      = false;
			
		}
		unset( $current );
		$i ++;
	}
	
	$events = sort_events_data_by_order( $events );
	
	return array( $events, $column_events );
}

/**
 * Sort events by order
 *
 * @param $events
 *
 * @return mixed
 */
function sort_events_data_by_order( $events ) {
	foreach ( $events as $cell => $event_data ) {
		usort( $event_data[ 'events' ], function ( $a, $b ) {
			if ( $a[ 'order' ] == $b[ 'order' ] ) {
				return 0;
			}
			
			return ( $a[ 'order' ] < $b[ 'order' ] ) ? - 1 : 1;
		} );
		$events[ $cell ][ 'events' ] = $event_data[ 'events' ];
	}
	
	return $events;
}

/**
 * Get columns events
 *
 * @param $mptt_shortcode_data
 *
 * @param $post
 *
 * @return array
 */
function mptt_get_columns_events( $mptt_shortcode_data, $post ) {
	if ( $post === 'all' ) {
		$column_events = $mptt_shortcode_data[ 'events_data' ][ 'column_events' ];
		
		return $column_events;
	} else {
		$column_events = array();
		
		foreach ( $mptt_shortcode_data[ 'events_data' ][ 'column_events' ] as $col_id => $col_events ) {
			$column_events[ $col_id ] = array_filter(
				$col_events,
				function ( $val ) use ( $post ) {
					return $post->ID == $val->event_id;
				} );
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
function mptt_check_exists_column( $needle, $events ) {
	$exist                      = false;
	$const_available_difference = 1;
	
	foreach ( $events as $key => $event ) {
		$difference_data = array_diff( $needle, $event );
		if ( isset( $difference_data[ 'id' ] ) && ( count( $difference_data ) === $const_available_difference ) ) {
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
function mptt_group_identical_row_events( $events_row_data ) {
	
	$length = count( $events_row_data );
	
	for ( $i = 0; $i < $length - 1; $i ++ ) {
		if ( ! isset( $events_row_data[ ( $i + 1 ) ] ) ) {
			continue;
		}
		
		$events_current_data  = $events_row_data[ $i ];
		$events_current_count = count( $events_current_data[ 'events' ] );
		
		$events_next_data  = $events_row_data[ ( $i + 1 ) ];
		$events_next_count = count( $events_next_data[ 'events' ] );
		
		
		if ( $events_next_count > 1 || $events_current_count > 1 ) {
			continue;
		} else {
			if ( filter_var( $events_current_data[ 'events' ][ 0 ][ 'id' ], FILTER_VALIDATE_INT ) && filter_var( $events_next_data[ 'events' ][ 0 ][ 'id' ], FILTER_VALIDATE_INT ) ) {
				
				$hash_current = $events_current_data[ 'events' ][ 0 ][ 'hash' ];
				$hash_next    = $events_next_data[ 'events' ][ 0 ][ 'hash' ];
				
				if ( ! isset( $current_data ) ) {
					$current_data = array( 'key' => $i, 'hash' => $hash_current );
				}
				
				if ( $hash_current === $hash_next ) {
					
					if ( $current_data[ 'hash' ] !== $hash_current ) {
						$current_data = array( 'key' => $i, 'hash' => $hash_current );
					}
					
					if ( $current_data[ 'key' ] === $i ) {
						$events_current_data[ 'count' ] ++;
						$events_current_data[ 'grouped' ]    = true;
						$events_row_data[ $i ]               = $events_current_data;
						$events_row_data[ $i + 1 ][ 'hide' ] = true;
					} else {
						$events_row_data[ $current_data[ 'key' ] ][ 'count' ] ++;
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
function mptt_is_grouped_event_class( $event_item ) {
	return ( isset( $event_item[ 'grouped' ] ) && $event_item[ 'grouped' ] ) ? 'mptt-grouped-event' : '';
}

/**
 * Grouped by column
 *
 * @param $events
 *
 * @return array
 */
function grouped_by_column( $events ) {
	$data = array();
	
	foreach ( $events as $key => $event ) {
		if ( ! $event[ 'event' ] ) {
			$data[] = $event;
			continue;
		}
		if ( isset( $data[ $event[ 'column_id' ] ] ) ) {
			$data[ $event[ 'column_id' ] ][ 'related_by_column' ][] = $event;
		} else {
			$data[ $event[ 'column_id' ] ] = $event;
		}
	}
	
	return $data;
}