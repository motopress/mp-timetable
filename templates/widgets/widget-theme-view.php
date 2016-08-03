<?php echo $args['before_widget'] ?>
<?php

if (!empty($instance['title'])) {
	echo $args['before_title'] . $instance['title'] . $args['after_title'];
}
do_action('mptt_widget_template_before_content');
$time_format = get_option('time_format');

if (!empty($events)):
	foreach ($events as $key => $event):
		$event_class = 'event';
		?>
		<li class="widget_recent_entries  <?php echo apply_filters('mptt_widget_upcoming_event_element', $event_class) ?>">
			<?php

			$disable_url = (bool)$event->post->timetable_disable_url || (bool)$instance['disable_url'];
			$url = ($instance['custom_url'] != "") ? $instance['custom_url'] : (($event->post->timetable_custom_url != "") ? $event->post->timetable_custom_url : get_permalink($event->event_id)); ?>

			<?php if (!$disable_url) { ?>
			<a href="<?php echo $url ?>" title="<?php echo get_the_title($event->event_id) ?>" class="event-link">
				<?php } ?>
				<?php echo get_the_title($event->event_id) ?>
				<?php if (!$disable_url) { ?>
			</a>
		<?php } ?>
			<span class="post-date">
				<?php if ($instance['view_settings'] !== 'today' && $instance['view_settings'] !== 'current'): ?><?php echo get_the_title($event->column_id) ?>
					<br><?php endif; ?>
				<time datetime="<?php echo $event->event_start; ?>"
				      class="timeslot-start"><?php echo date($time_format, strtotime($event->event_start));?></time><?php
				echo apply_filters('mptt_timeslot_delimiter', ' - '); ?>
				<time datetime="<?php echo $event->event_end; ?>"
				      class="timeslot-end"><?php echo date($time_format, strtotime($event->event_end)); ?></time>
			</span>
		</li>
	<?php endforeach;
else:
	_e('no events found', "mp-timetable");
endif;

do_action('mptt_widget_template_after_content');

echo $args['after_widget'] ?>