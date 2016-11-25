<?php
function mptt_shortcode_template_before_content() {
	global $mptt_shortcode_data;
	$wrapper_class = mptt_popular_theme_class();
	?>
	<div class="<?php echo apply_filters('mptt_shortcode_wrapper_class', 'mptt-shortcode-wrapper' . $wrapper_class . ($mptt_shortcode_data['params']['responsive'] == '0' ? ' mptt-table-fixed' : '')) ?>">
	<?php
}

function mptt_shortcode_template_after_content() {
	?>
	</div>
<?php }

function mptt_shortcode_template_content_filter() {
	global $mptt_shortcode_data;

	$style = '';
	if (empty($mptt_shortcode_data['unique_events']) || count($mptt_shortcode_data['unique_events']) < 2) {
		$style = ' style="display:none;"';
	}

	if ($mptt_shortcode_data['params']['view'] == 'dropdown_list') { ?>
		<select class="<?php echo apply_filters('mptt_shortcode_navigation_select_class', 'mptt-menu mptt-navigation-select') ?>"<?php echo $style ?>>
			<?php if (!$mptt_shortcode_data['params']['hide_label']): ?>
				<option
					value="#all"><?php echo (strlen(trim($mptt_shortcode_data['params']['label']))) ? trim($mptt_shortcode_data['params']['label']) : __('All Events', 'mp-timetable') ?></option>
			<?php endif;
			if (!empty($mptt_shortcode_data['unique_events'])):
				foreach ($mptt_shortcode_data['unique_events'] as $event): ?>
					<option value="#<?php echo $event->post->post_name ?>">
						<?php echo $event->post->post_title ?>
					</option>
				<?php endforeach;
			endif; ?>
		</select>
	<?php } elseif ($mptt_shortcode_data['params']['view'] == 'tabs') { ?>
		<ul class="<?php echo apply_filters('mptt_shortcode_navigation_tabs_class', 'mptt-menu mptt-navigation-tabs') ?>" <?php echo $style ?>>
			<?php if (!$mptt_shortcode_data['params']['hide_label']): ?>
				<li>
					<a title="<?php echo (strlen(trim($mptt_shortcode_data['params']['label']))) ? trim($mptt_shortcode_data['params']['label']) : __('All Events', 'mp-timetable') ?>"
					   href="#all">
						<?php echo (strlen(trim($mptt_shortcode_data['params']['label']))) ? trim($mptt_shortcode_data['params']['label']) : __('All Events', 'mp-timetable') ?>
					</a>
				</li>
			<?php endif;
			if (!empty($mptt_shortcode_data['unique_events'])): ?>
				<?php foreach ($mptt_shortcode_data['unique_events'] as $event): ?>
					<li>
						<a title="<?php echo $event->post->post_title ?>" href="#<?php echo $event->post->post_name ?>">
							<?php echo $event->post->post_title ?>
						</a>
					</li>
				<?php endforeach;
			endif; ?>
		</ul>
	<?php }
}

function mptt_shortcode_template_content_static_table() {
	global $mptt_shortcode_data;
	mptt_shortcode_template_event($mptt_shortcode_data);

	if (isset($mptt_shortcode_data['unique_events']) && is_array($mptt_shortcode_data['unique_events']))
		foreach ($mptt_shortcode_data['unique_events'] as $ev) {
			mptt_shortcode_template_event($mptt_shortcode_data, $ev->post);
		}
}

function mptt_shortcode_template_event($mptt_shortcode_data, $post = 'all') {
	$amount_rows = 23 / $mptt_shortcode_data['params']['increment'];

	if ($post === 'all') {
		$event_id = $post;
		$column_events = $mptt_shortcode_data['events_data']['column_events'];
	} else {
		$column_events = array();

		foreach ($mptt_shortcode_data['events_data']['column_events'] as $col_id => $col_events) {
			$column_events[$col_id] = array_filter(
				$col_events,
				function ($val) use ($post) {
					return $post->ID == $val->event_id;
				});
		}

		$event_id = $post->post_name;
	}
	$bounds = mptt_shortcode_get_table_cell_bounds($column_events, $mptt_shortcode_data['params']);
	$hide_empty_rows = $mptt_shortcode_data['params']['hide_empty_rows'];
	$show_hrs = !$mptt_shortcode_data['params']['hide_hrs'];
	$font_size = !empty($mptt_shortcode_data['params']['font_size']) ? ' font-size:' . $mptt_shortcode_data['params']['font_size'] . ';' : '';
	$row_height = $mptt_shortcode_data['params']['row_height'];
	$table_class = apply_filters('mptt_shortcode_static_table_class', 'mptt-shortcode-table');
	$table_class .= \mp_timetable\classes\models\Settings::get_instance()->is_plugin_template_mode() ? '' : ' mptt-theme-mode';

	?>
	<table <?php echo !empty($table_class) ? 'class="' . $table_class . '"' : ''; ?>
		id="#<?php echo $event_id; ?>"
		style="display:none; <?php echo $font_size; ?>"
		data-hide_empty_row="<?php echo $hide_empty_rows; ?>">
		<thead>
		<tr class="mptt-shortcode-row">
			<?php if ($show_hrs): ?>
				<th></th>
			<?php endif; ?>
			<?php foreach ($mptt_shortcode_data['events_data']['column'] as $column): ?>
				<th data-column-id="<?php echo $column->ID ?>"><?php echo $column->post_title ?></th>
			<?php endforeach; ?>
		</tr>
		</thead>
		<tbody>
		<?php for ($i = $bounds['start']; $i <= $bounds['end']; $i++): ?>
			<?php
			$tm = $i * $mptt_shortcode_data['params']['increment'];
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

			$row_has_items = mptt_shortcode_row_has_items($i, $column_events);

			?>
			<?php if ($hide_empty_rows && !$row_has_items): ?>
				<?php continue; ?>
			<?php else: ?>
				<tr class="mptt-shortcode-row-<?php echo $i ?>" data-index="<?php echo $i ?>">

					<?php if ($show_hrs) { ?>
						<td class="mptt-shortcode-hours"
						    style="<?php echo 'height:' . $row_height . 'px;'; ?>"><?php echo date(get_option('time_format'), strtotime($table_cell_start)); ?></td>
					<?php } ?>

					<?php foreach ($column_events as $column_id => $events_list): ?>
						<td class="mptt-shortcode-event"
						    data-column-id="<?php echo $column_id ?>"
						    rowspan=""
						    data-row_height="<?php echo $row_height; ?>"
						    style="<?php echo 'height:' . $mptt_shortcode_data['params']['row_height'] . 'px;'; ?>">
							<?php if (!empty($column_events[$column_id])) {
								foreach ($events_list as $key_events => $item) :
									if ($item->start_index == $i) {
										\mp_timetable\plugin_core\classes\View::get_instance()->get_template('shortcodes/event-container',
											array(
												'item' => $item,
												'params' => $mptt_shortcode_data['params']
											));
									}
								endforeach;
							} ?>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endif; ?>
		<?php endfor; ?>
		</tbody>
	</table>
	<?php
}

function mptt_shortcode_row_has_items($i, $column_events) {

	foreach ($column_events as $column_id => $events_list) {
		if (!empty($column_events[$column_id])) {
			foreach ($events_list as $key_events => $item) {
				if ($item->start_index <= $i && $i < $item->end_index) {
					return true;
				}
			}
		}
	}

	return false;
}


function mptt_shortcode_get_table_cell_bounds($column_events, $params) {
	$hide_empty_rows = $params['hide_empty_rows'];

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
		$max = 23 / $params['increment'];
	}

	return array('start' => $min, 'end' => $max);
}

function mptt_shortcode_template_content_responsive_table() {
	global $mptt_shortcode_data;
	if ($mptt_shortcode_data['params']['responsive']) {
		?>
		<div class="<?php echo apply_filters('mptt_shortcode_list_view_class', 'mptt-shortcode-list') ?>">
			<?php if (!empty($mptt_shortcode_data['events_data'])): ?>
				<?php foreach ($mptt_shortcode_data['events_data']['column'] as $column): ?>
					<div class="mptt-column">
						<h3 class="mptt-column-title"><?php echo $column->post_title ?></h3>
						<ul class="mptt-events-list">
							<?php if (!empty($mptt_shortcode_data['events_data']['column_events'][$column->ID])): ?>
								<?php foreach ($mptt_shortcode_data['events_data']['column_events'][$column->ID] as $event) : ?>
									<li class="mptt-list-event" data-event-id="#<?php echo $event->post->post_name ?>"
										<?php
										if (!empty($event->post->color)) {
											echo 'style="border-left-color:' . $event->post->color . ';"';
										} ?> >
										<?php if ($mptt_shortcode_data['params']['title']): ?>
											<?php
											$disable_url = (bool)$event->post->timetable_disable_url || (bool)$mptt_shortcode_data['params']['disable_event_url'];
											if (!$disable_url) { ?>
												<a title="<?php echo $event->post->post_title; ?>"
												href="<?php echo ($event->post->timetable_custom_url != "") ? $event->post->timetable_custom_url : get_permalink($event->event_id); ?>"
												class="mptt-event-title">
											<?php } ?>
											<?php echo $event->post->post_title; ?>
											<?php if (!$disable_url) { ?>
												</a>
											<?php } ?>
										<?php endif; ?>
										<?php if ($mptt_shortcode_data['params']['time']): ?>
											<p class="timeslot">
												<time datetime="<?php echo $event->event_start; ?>"
												      class="timeslot-start"><?php echo date(get_option('time_format'), strtotime($event->event_start)); ?></time>
												<span
													class="timeslot-delimiter"><?php echo apply_filters('mptt_timeslot_delimiter', ' - '); ?></span>
												<time datetime="<?php echo $event->event_end; ?>"
												      class="timeslot-end"><?php echo date(get_option('time_format'), strtotime($event->event_end)); ?></time>
											</p>
										<?php endif; ?>
										<?php if ($mptt_shortcode_data['params']['description']): ?>
											<p class="event-description">
												<?php echo $event->description ?>
											</p>
										<?php endif; ?>
										<?php if ($mptt_shortcode_data['params']['user'] && ($event->user_id != '-1')): ?>
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
		<?php
	}
} ?>