<?php echo $args['before_widget'] ?>
<?php use \mp_timetable\classes\models\Events as Events;

if (!empty($instance['title'])) {
	echo $args['before_title'] . $instance['title'] . $args['after_title'];
}
do_action('mptt_widget_template_before_content');

if (!empty($events)):
	foreach ($events as $key => $event):
		$widget = false;
		$background_color = Events::get_instance()->choose_event_color(array('event_color' => $event->post->color, 'widget_color' => $instance['background_color']));
		$background_hover_color = Events::get_instance()->choose_event_color(array('event_color' => $event->post->hover_color, 'widget_color' => $instance['hover_background_color']));
		$color = Events::get_instance()->choose_event_color(array('event_color' => $event->post->text_color, 'widget_color' => $instance['text_color']));
		$hover_color = Events::get_instance()->choose_event_color(array('event_color' => $event->post->hover_text_color, 'widget_color' => $instance['hover_text_color']));
		$style = 'style="';
		if ($instance['background_color'] != ''
			|| $instance['hover_background_color'] != ''
			|| $instance['text_color'] != ''
			|| $instance['hover_text_color'] != ''
			|| $instance['item_border_color'] != ''
			|| $instance['hover_item_border_color'] !== ''
		) {
			$widget = true;
		}
		$event_class = 'event' . ($widget ? ' mptt-colorized' : '');
		?>
		<li class="<?php echo apply_filters('mptt_widget_upcoming_event_element', $event_class) ?>"
			<?php if ($widget): ?> data-type="widget"
				data-background-color="<?php echo $background_color ?>"
				data-background-hover-color="<?php echo $background_hover_color ?>"
				data-color="<?php echo $color ?>"
				data-hover-color="<?php echo $hover_color ?>"
				data-border-color="<?php echo $instance['item_border_color'] ?>"
				data-hover-border-color="<?php echo $instance['hover_item_border_color'] ?>"
				<?php

				$style .= !empty($instance['item_border_color']) ? ' border-left-color:' . $instance['item_border_color'] . ' ;' : '';
				$style .= !empty($background_color) ? ' background:' . $background_color . ' ;' : '';
				$style .= !empty($color) ? ' color:' . $color . ' ;' : '';

				?>
			<?php else:
				$style .= !empty($event->post->color) ? ' border-left-color:' . $event->post->color . ' ;' : '';
			endif;
			echo $style . '"';
			?>

		>
			<?php

			$disable_url = (bool)$event->post->timetable_disable_url || (bool)$instance['disable_url'];
			$url = ($instance['custom_url'] != "") ? $instance['custom_url'] : (($event->post->timetable_custom_url != "") ? $event->post->timetable_custom_url : get_permalink($event->event_id)); ?>
			<h4 class="event-title">
				<?php if (!$disable_url) { ?>
				<a href="<?php echo $url ?>" title="<?php echo get_the_title($event->event_id) ?>" class="event-link">
					<?php } ?>
					<?php echo get_the_title($event->event_id) ?>
					<?php if (!$disable_url) { ?>
				</a>
			<?php } ?>
			</h4>
			<p class="column-title"><?php echo get_the_title($event->column_id) ?></p>
			<p class="timeslot">
				<span class="timeslot-start"><?php echo date(get_option('time_format'), strtotime($event->event_start)); ?></span><?php echo apply_filters('mptt_timeslot_delimiter', ' - '); ?><span class="timeslot-end"><?php echo date(get_option('time_format'), strtotime($event->event_end)); ?>
			</p>

			<?php if (! empty($event->user_id) && ($event->user_id != 0)): ?>
				<p class="event-user">
					<?php $user_info = get_userdata($event->user_id);
					if( $user_info ){
						echo get_avatar( $event->user_id, apply_filters('mptt-event-user-avatar-size', 24) ) . ' ';
						echo $user_info->data->display_name;
					}?>
				</p>
			<?php endif; ?>
		</li>

	<?php endforeach;
else:
	_e('no events found', "mp-timetable");
endif;

do_action('mptt_widget_template_after_content');

echo $args['after_widget'] ?>