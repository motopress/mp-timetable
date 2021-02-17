<?php echo $args['before_widget'];

if (!empty($instance['title'])) {
	echo $args['before_title'] . $instance['title'] . $args['after_title'];
}

do_action('mptt_widget_upcoming_before_content', $events);

$time_format = get_option('time_format');

$events_group_by_categories = array();

foreach ($events as $event) {
	if (!isset($temp[$event->column_id])) {
		$events_group_by_categories[$event->column_id][] = $event;
	} else {
		$events_group_by_categories[$event->column_id][] = $event;
	}
}

if (!empty($events)): ?>
	<?php foreach ($events_group_by_categories as $key_category => $cat_events) { ?>
		<ul>
			<?php foreach ($cat_events as $key => $event):
				$event_class = 'event';
				?>
				<li class="<?php echo apply_filters('mptt_widget_upcoming_event_class', $event_class) ?>">
					<?php

					$disable_url = (bool)$event->post->timetable_disable_url || (bool)$instance['disable_url'];
					$url = ($instance['custom_url'] != "") ? $instance['custom_url'] : (($event->post->timetable_custom_url != "") ? $event->post->timetable_custom_url : get_permalink($event->event_id));

					if (!$disable_url) { ?>
					<a href="<?php echo $url ?>" title="<?php echo get_the_title($event->event_id) ?>" class="event-link">
						<?php }
						echo get_the_title($event->event_id);
						if (!$disable_url) { ?>
					</a><br/>
				<?php } ?>
					<span class="post-date">
				<?php if ($instance['view_settings'] !== 'today' && $instance['view_settings'] !== 'current'): ?><?php echo get_the_title($event->column_id) ?>
					<br/>
				<?php endif; ?>
						<time datetime="<?php echo $event->event_start; ?>" class="timeslot-start"><?php echo date($time_format, strtotime($event->event_start)); ?></time>
						<?php echo apply_filters('mptt_timeslot_delimiter', ' - '); ?>
						<time datetime="<?php echo $event->event_end; ?>" class="timeslot-end"><?php echo date($time_format, strtotime($event->event_end)); ?></time>
				</span>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}
else:
	_e('no events found', 'mp-timetable');
endif;

do_action('mptt_widget_upcoming_after_content', $events);

echo $args['after_widget'] ?>