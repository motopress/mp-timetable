<?php
use mp_timetable\classes\models\Settings;
use mp_timetable\plugin_core\classes\View;

/**
 * Before content
 */
function mptt_shortcode_template_before_content() {
	global $mptt_shortcode_data;
	$wrapper_class = mptt_popular_theme_class();
	$id = empty($mptt_shortcode_data[ 'params' ][ 'id' ]) ? '' : $mptt_shortcode_data[ 'params' ][ 'id' ];
	?>
	<div id="<?php echo $id ?>" style="display: none" class="<?php echo apply_filters('mptt_shortcode_wrapper_class', 'mptt-shortcode-wrapper' . $wrapper_class . ($mptt_shortcode_data[ 'params' ][ 'responsive' ] == '0' ? ' mptt-table-fixed' : '')) ?>">
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
	
	$style = '';
	if (empty($mptt_shortcode_data[ 'unique_events' ]) || count($mptt_shortcode_data[ 'unique_events' ]) < 2) {
		$style = ' style="display:none;"';
	}
	
	if ($mptt_shortcode_data[ 'params' ][ 'view' ] == 'dropdown_list') { ?>
		<select class="<?php echo apply_filters('mptt_shortcode_navigation_select_class', 'mptt-menu mptt-navigation-select') ?>"<?php echo $style ?>>
			<?php if (!$mptt_shortcode_data[ 'params' ][ 'hide_label' ]): ?>
				<option value="all"><?php echo (strlen(trim($mptt_shortcode_data[ 'params' ][ 'label' ]))) ? trim($mptt_shortcode_data[ 'params' ][ 'label' ]) : __('All Events', 'mp-timetable') ?></option>
			<?php endif;
			if (!empty($mptt_shortcode_data[ 'unique_events' ])):
				foreach ($mptt_shortcode_data[ 'unique_events' ] as $event): ?>
					<option value="<?php echo $event->post->post_name ?>"><?php echo $event->post->post_title ?></option>
				<?php endforeach;
			endif; ?>
		</select>
	<?php } elseif ($mptt_shortcode_data[ 'params' ][ 'view' ] == 'tabs') { ?>
		<ul class="<?php echo apply_filters('mptt_shortcode_navigation_tabs_class', 'mptt-menu mptt-navigation-tabs') ?>" <?php echo $style ?>>
			<?php if (!$mptt_shortcode_data[ 'params' ][ 'hide_label' ]): ?>
				<li>
					<a title="<?php echo (strlen(trim($mptt_shortcode_data[ 'params' ][ 'label' ]))) ? trim($mptt_shortcode_data[ 'params' ][ 'label' ]) : __('All Events', 'mp-timetable') ?>"
					   href="#all" onclick="event.preventDefault();"><?php echo (strlen(trim($mptt_shortcode_data[ 'params' ][ 'label' ]))) ? trim($mptt_shortcode_data[ 'params' ][ 'label' ]) : __('All Events', 'mp-timetable') ?>
					</a>
				</li>
			<?php endif;
			if (!empty($mptt_shortcode_data[ 'unique_events' ])): ?>
				<?php foreach ($mptt_shortcode_data[ 'unique_events' ] as $event): ?>
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
	
	mptt_shortcode_template_event($mptt_shortcode_data);
	
	if (isset($mptt_shortcode_data[ 'unique_events' ]) && is_array($mptt_shortcode_data[ 'unique_events' ])) {
		foreach ($mptt_shortcode_data[ 'unique_events' ] as $ev) {
			mptt_shortcode_template_event($mptt_shortcode_data, $ev->post);
		}
	}
}

/**
 * Event template
 *
 * @param $mptt_shortcode_data
 * @param mixed $post
 */
function mptt_shortcode_template_event($mptt_shortcode_data, $post = 'all') {
	$params = $mptt_shortcode_data[ 'params' ];
	
	$column_events = mptt_get_columns_events($mptt_shortcode_data, $post);
	$bounds = mptt_shortcode_get_table_cell_bounds($column_events, $params);
	
	$hide_empty_rows = $params[ 'hide_empty_rows' ];
	$font_size = !empty($params[ 'font_size' ]) ? ' font-size:' . $params[ 'font_size' ] . ';' : '';
	$row_height = $params[ 'row_height' ];
	$table_class = apply_filters('mptt_shortcode_static_table_class', 'mptt-shortcode-table') . ' ' . $params[ 'custom_class' ];
	$table_class .= Settings::get_instance()->is_plugin_template_mode() ? '' : ' mptt-theme-mode';
	
	$data_grouped_by_row = mptt_make_data_shortcode($bounds, $mptt_shortcode_data, $column_events);
	
	?>
	<table class="<?php echo !empty($table_class) ? $table_class : ''; ?>" id="#<?php echo is_object($post) ? $post->post_name : $post; ?>" style="display:none; <?php echo $font_size; ?>" data-hide_empty_row="<?php echo $hide_empty_rows; ?>">
		<?php echo View::get_instance()->get_template_html('shortcodes/table-header', array('header_items' => $data_grouped_by_row[ 'table_header' ], 'params' => $params)); ?>
		<tbody>
		<?php foreach ($data_grouped_by_row[ 'rows' ] as $key => $row) {
			if (!$row[ 'show' ]) {
				continue;
			} ?>
			<tr class="mptt-shortcode-row-<?php echo $key ?>" data-index="<?php echo $key ?>">
				<?php $events = $data_grouped_by_row[ 'rows' ][ $key ][ 'events' ];
				$events = grouped_by_column($events);
				foreach ($events as $key_event => $event_item) {
					
					if (!$event_item[ 'event' ]) { ?>
						<td class="mptt-shortcode-hours" style="<?php echo 'height:' . $row_height . 'px;'; ?>"><?php echo $event_item[ 'title' ] ?></td>
						<?php continue;
					} ?>

					<td class="mptt-shortcode-event <?php echo mptt_is_grouped_event_class($event_item) ?>" data-column-id="<?php echo $event_item[ 'column_id' ] ?>" rowspan="" colspan="<?php echo !isset($event_item[ 'count' ]) ? '' : $event_item[ 'count' ] ?>" data-row_height="<?php echo $row_height; ?>" style="<?php echo 'height:' . $params[ 'row_height' ] . 'px;'; ?>">
						<?php if ($event_item[ 'id' ]) {
							
							View::get_instance()->get_template('shortcodes/event-container', array('item' => $event_item, 'params' => $params));
							
							if (!empty($event_item[ 'related_by_column' ]) && is_array($event_item[ 'related_by_column' ])) {
								foreach ($event_item[ 'related_by_column' ] as $related_event) {
									View::get_instance()->get_template('shortcodes/event-container', array('item' => $related_event, 'params' => $params));
								}
							}
							
						} ?>
					</td>
				<?php } ?>
			</tr>
		<?php } ?>
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
function mptt_shortcode_row_has_items($i, $column_events) {
	
	foreach ($column_events as $column_id => $events_list) {
		if (!empty($column_events[ $column_id ])) {
			foreach ($events_list as $key_events => $item) {
				if ($item->start_index <= $i && $i < $item->end_index) {
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
function mptt_shortcode_get_table_cell_bounds($column_events, $params) {
	$hide_empty_rows = $params[ 'hide_empty_rows' ];
	
	if ($hide_empty_rows) {
		$min = -1;
		$max = -1;
		foreach ($column_events as $events) {
			foreach ($events as $item) {
				if ($item->start_index && $item->end_index) {
					$min = ($min === -1) ? $item->start_index : $min;
					$max = ($max === -1) ? $item->end_index : $max;
					$min = ($item->start_index < $min) ? $item->start_index : $min;
					$max = ($item->end_index > $max) ? $item->end_index : $max;
				}
			}
		}
	} else {
		$min = 0;
		$max = 23 / $params[ 'increment' ];
	}
	
	return array('start' => $min, 'end' => $max);
}

/**
 * Content responsive table
 */
function mptt_shortcode_template_content_responsive_table() {
	global $mptt_shortcode_data;
	if ($mptt_shortcode_data[ 'params' ][ 'responsive' ]) { ?>
		<div class="<?php echo apply_filters('mptt_shortcode_list_view_class', 'mptt-shortcode-list') . ' ' . $mptt_shortcode_data[ 'params' ][ 'custom_class' ] ?>">
			<?php if (!empty($mptt_shortcode_data[ 'events_data' ])):
				foreach ($mptt_shortcode_data[ 'events_data' ][ 'column' ] as $column): ?>
					<div class="mptt-column">
						<h3 class="mptt-column-title"><?php echo $column->post_title ?></h3>
						<ul class="mptt-events-list">
							<?php if (!empty($mptt_shortcode_data[ 'events_data' ][ 'column_events' ][ $column->ID ])):
								foreach ($mptt_shortcode_data[ 'events_data' ][ 'column_events' ][ $column->ID ] as $event) : ?>
									<li class="mptt-list-event" data-event-id="<?php echo $event->post->post_name ?>"
										<?php
										if (!empty($event->post->color)) {
											echo 'style="border-left-color:' . $event->post->color . ';"';
										} ?>>
										<?php if ($mptt_shortcode_data[ 'params' ][ 'title' ]):
											$disable_url = (bool)$event->post->timetable_disable_url || (bool)$mptt_shortcode_data[ 'params' ][ 'disable_event_url' ];
											if (!$disable_url) { ?>
												<a title="<?php echo $event->post->post_title; ?>"
												href="<?php echo ($event->post->timetable_custom_url != "") ? $event->post->timetable_custom_url : get_permalink($event->event_id); ?>"
												class="mptt-event-title">
											<?php }
											echo $event->post->post_title;
											
											if (!$disable_url) { ?>
												</a>
											<?php }
										
										endif;
										if ($mptt_shortcode_data[ 'params' ][ 'time' ]): ?>
											<p class="timeslot">
												<time datetime="<?php echo $event->event_start; ?>" class="timeslot-start"><?php echo date(get_option('time_format'), strtotime($event->event_start)); ?></time>
												<span class="timeslot-delimiter"><?php echo apply_filters('mptt_timeslot_delimiter', ' - '); ?></span>
												<time datetime="<?php echo $event->event_end; ?>" class="timeslot-end"><?php echo date(get_option('time_format'), strtotime($event->event_end)); ?></time>
											</p>
										<?php endif;
										if ($mptt_shortcode_data[ 'params' ][ 'description' ]): ?>
											<p class="event-description">
												<?php echo $event->description ?>
											</p>
										<?php endif;
										if ($mptt_shortcode_data[ 'params' ][ 'user' ] && ($event->user_id != '-1')): ?>
											<p class="event-user"><?php $user_info = get_userdata($event->user_id);
												if ($user_info) {
													echo get_avatar($event->user_id, apply_filters('mptt-event-user-avatar-size', 24), '', $user_info->data->display_name) . ' ';
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