<?php echo $args['before_widget'] ?>
<?php use \mp_timetable\classes\models\Events as Events;

if (!empty($instance['title'])) {
	echo $args['before_title'] . $instance['title'] . $args['after_title'];
}
do_action('mptt_widget_template_before_content');

if (!empty($events)):
	foreach ($events as $key => $event):
		$widget = false;
		$style = 'style="';
		$event_class = 'event' . ($widget ? ' mptt-colorized' : '');
		?>
		<li class="<?php echo apply_filters('mptt_widget_upcoming_event_element', $event_class) ?>"
		    <?php
		    $style .= !empty($event->post->color) ? ' border-left-color:' . $event->post->color . ' ;' : '';
			echo $style . '"';
			?>
			>
			<?php
			$disable_url = (bool)$event->post->timetable_disable_url || (bool)$instance['disable_url'];
			$url = get_permalink($event->event_id); ?>
			<h4 class="event-title">
				<?php if (!$disable_url) { ?>
				<a href="<?php echo $url ?>" title="<?php echo get_the_title($event->event_id) ?>" class="event-link">
					<?php } ?>
					<?php echo get_the_title($event->event_id) ?>
					<?php if (!$disable_url) { ?>
				</a>
			<?php } ?>
			</h4>
			<?php if($instance['view_settings'] !== 'today'): ?><p class="column-title"><?php echo get_the_title($event->column_id) ?></p><?php endif; ?>
			<p class="timeslot">
				<span class="timeslot-start"><?php echo date(get_option('time_format'), strtotime($event->event_start)); ?></span><?php echo apply_filters('mptt_timeslot_delimiter', ' - '); ?><span class="timeslot-end"><?php echo date(get_option('time_format'), strtotime($event->event_end)); ?>
			</p>
		</li>

	<?php endforeach;
else:
	_e('no events found', "mp-timetable");
endif;

do_action('mptt_widget_template_after_content');

echo $args['after_widget'] ?>