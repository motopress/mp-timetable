<?php
function mptt_shortcode_template_before_content() {
	global $mptt_shortcode_data;
	?>
	<div class="<?php echo apply_filters('mptt_shortcode_wrapper_class', 'mptt-shortcode-wrapper' . mptt_popular_theme_class() . ($mptt_shortcode_data['params']['responsive'] == '0' ? ' mptt-table-fixed' : '')) ?>">
	<?php
}

function mptt_shortcode_template_after_content() {
	?>
	</div>
<?php }

function mptt_shortcode_template_content_filter() {
	global $mptt_shortcode_data;
	if ($mptt_shortcode_data['params']['view'] == 'dropdown_list') { ?>
		<select class="<?php echo apply_filters('mptt_shortcode_navigation_select_class', 'mptt-menu mptt-navigation-select') ?>">
			<?php if (!$mptt_shortcode_data['params']['hide_label']): ?>
				<option value="all"><?php echo (strlen(trim($mptt_shortcode_data['params']['label']))) ? trim($mptt_shortcode_data['params']['label']) : __('All Events', 'mp-timetable') ?></option>
			<?php endif;
			if (!empty($mptt_shortcode_data['unique_events'])):
				foreach ($mptt_shortcode_data['unique_events'] as $event): ?>
					<option value="<?php echo $event->event_id ?>">
						<?php echo $event->post->post_title ?>
					</option>
				<?php endforeach;
			endif; ?>
		</select>
	<?php } elseif ($mptt_shortcode_data['params']['view'] == 'tabs') { ?>
		<ul class="<?php echo apply_filters('mptt_shortcode_navigation_tabs_class', 'mptt-menu mptt-navigation-tabs') ?>">
			<?php if (!$mptt_shortcode_data['params']['hide_label']): ?>
				<li data-event-id="all">
					<a title="<?php echo (strlen(trim($mptt_shortcode_data['params']['label']))) ? trim($mptt_shortcode_data['params']['label']) : __('All Events', 'mp-timetable') ?>"
					   href="#<?php echo (strlen(trim($mptt_shortcode_data['params']['label']))) ? trim($mptt_shortcode_data['params']['label']) : __('All Events', 'mp-timetable') ?>">
						<?php echo (strlen(trim($mptt_shortcode_data['params']['label']))) ? trim($mptt_shortcode_data['params']['label']) : __('All Events', 'mp-timetable') ?>
					</a>
				</li>
			<?php endif;
			if (!empty($mptt_shortcode_data['unique_events'])): ?>
				<?php foreach ($mptt_shortcode_data['unique_events'] as $event):
					?>
					<li data-event-id="<?php echo $event->event_id ?>">
						<a title="<?php echo $event->post->post_title ?>" href="#<?php echo $event->post->post_title ?>">
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
	$amount_rows = 23 / $mptt_shortcode_data['params']['increment'];
	$increment = $mptt_shortcode_data['params']['increment'] === '1' ? "+1 hour" : "+" . (60 * $mptt_shortcode_data['params']['increment']) . " minutes";
	?>
	<input type="hidden" name="hide_empty_rows" value="<?php echo $mptt_shortcode_data['params']['hide_empty_rows'] ?>"/>


	<table class="<?php echo apply_filters('mptt_shortcode_static_table_class', 'mptt-shortcode-table') ?>" data-amout-rows="<?php echo $amount_rows ?>"
	       data-increment="<?php echo $mptt_shortcode_data['params']['increment'] ?>"
	       data-table-id="<?php echo $mptt_shortcode_data['params']['id'] ?>">
		<thead>
		<tr class="mptt-shortcode-row">
			<th style=" <?php echo (bool)($mptt_shortcode_data['params']['hide_hrs']) ? 'display:none;' : '' ?>"></th>
			<?php foreach ($mptt_shortcode_data['events_data']['column'] as $column): ?>
				<th data-column-id="<?php echo $column->ID ?>"><?php echo $column->post_title ?></th>
			<?php endforeach; ?>
		</tr>
		</thead>
		<tbody>

		<?php for ($i = 0; $i <= $amount_rows; $i++): ?>
			<tr class="mptt-shortcode-row-<?php echo $i ?>" data-index="<?php echo $i ?>">
				<?php $tm = $i * $mptt_shortcode_data['params']['increment'];
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
				} ?>
				<td class="mptt-shortcode-hours" style="<?php echo (bool)$mptt_shortcode_data['params']['hide_hrs'] ? 'display:none;' : '';
				echo 'height:' . $mptt_shortcode_data['params']['row_height'] . 'px;'; ?>"><?php echo date(get_option('time_format'), strtotime($table_cell_start)); ?></td>
				<?php foreach ($mptt_shortcode_data['events_data']['column'] as $columns): ?>
					<td class="mptt-shortcode-event" data-column-id="<?php echo $columns->ID ?>" rowspan="" style="<?php echo 'height:' . $mptt_shortcode_data['params']['row_height'] . 'px;'; ?>">
						<?php if (!empty($mptt_shortcode_data['events_data']['column_events'][$columns->ID])) {
							foreach ($mptt_shortcode_data['events_data']['column_events'][$columns->ID] as $key_events => $item) :
								if ($item->output) {
									continue;
								}
								$endTime = 0;
								if ($endTime < strtotime($item->event_end)) {
									$endTime = strtotime($item->event_end);
								}
								if ($item->start_index == $i) {
									$mptt_shortcode_data['events_data']['column_events'][$columns->ID][$key_events]->output = true;

									\mp_timetable\plugin_core\classes\View::get_instance()->render_html('shortcodes/event-container',
										array(
											'item' => $item,
											'params' => $mptt_shortcode_data['params']
										), true);

									foreach ($mptt_shortcode_data['events_data']['column_events'][$columns->ID] as $key_sub_events => $sub_item) :
										if ($sub_item->output) {
											continue;
										}
										if (strtotime($sub_item->event_start) < $endTime) {
											$mptt_shortcode_data['events_data']['column_events'][$columns->ID][$key_sub_events]->output = true; ?>
											<?php
											\mp_timetable\plugin_core\classes\View::get_instance()->render_html('shortcodes/event-container',
												array(
													'item' => $sub_item,
													'params' => $mptt_shortcode_data['params'],
													'startIndex' => $item->start_index
												)
												, true);
											if ($endTime < strtotime($sub_item->event_end)) {
												$endTime = strtotime($sub_item->event_end);
											}
										}
									endforeach;
								}
							endforeach;
						} ?>
					</td>
				<?php endforeach; ?>
			</tr>
		<?php endfor; ?>

		</tbody>
	</table>

<?php }

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
							<?php foreach ($mptt_shortcode_data['events_data']['column_events'][$column->ID] as $event) : ?>
								<li class="mptt-list-event" data-event-id="<?php echo $event->event_id ?>"
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
											<span class="timeslot-start"><?php echo date(get_option('time_format'), strtotime($event->event_start)); ?></span>
											<span class="timeslot-delimiter"><?php echo apply_filters('mptt_timeslot_delimiter', ' - '); ?></span>
											<span class="timeslot-end"><?php echo date(get_option('time_format'), strtotime($event->event_end)); ?></span>
										</p>
									<?php endif; ?>
									<?php if ($mptt_shortcode_data['params']['description']): ?>
										<p class="event-description">
											<?php echo $event->description ?>
										</p>
									<?php endif; ?>
									<?php if ($mptt_shortcode_data['params']['user'] && ( $event->user_id != '-1' ) ): ?>
										<p class="event-user">
											<?php $user_info = get_userdata($event->user_id);
											if( $user_info ){
												echo $user_info->data->display_name;
											}?>
										</p>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach;
			endif; ?>
		</div>
		<?php
	}
} ?>