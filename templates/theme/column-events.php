<?php
$time_format = get_option('time_format');

do_action('mptt_column_events_before_events', $events);

if ( !empty($events) ) {

	foreach ($events as $event): ?>
		<p class="event mptt-theme-mode-event" id="event_<?php echo $event->event_id ?>">

			<?php if (has_post_thumbnail($event->event_id)) {
				echo wp_get_attachment_image( get_post_thumbnail_id($event->event_id), apply_filters('mptt_event_thumbnail_size', 'thumbnail'), false, array('class' => "alignleft event-thumbnail", 'alt' => get_the_title($event->event_id)));
			} else { ?>
				<img class="alignleft event-thumbnail event-thumbnail-default" src="<?php echo \Mp_Time_Table::get_plugin_url() . 'media/css/images/column_icon.png' ?>">
			<?php } ?>

			<a href="<?php echo $event->post->timetable_disable_url == '1' ? '#' : ($event->post->timetable_custom_url != "" ? $event->post->timetable_custom_url : get_permalink($event->event_id)) ?>" class="event-link">
				<?php echo get_the_title($event->event_id); ?>
			</a>

			<br/>

			<time datetime="<?php echo $event->event_start; ?>" class="timeslot-start"><?php echo date($time_format, strtotime($event->event_start)); ?></time><?php echo apply_filters('mptt_timeslot_delimiter', ' - '); ?>
			<time datetime="<?php echo $event->event_end; ?>" class="timeslot-end"><?php echo date($time_format, strtotime($event->event_end)); ?></time>

			<?php if (!empty($event->post->sub_title)) { ?>
				<br/>
				<span class="event-subtitle"><?php echo $event->post->sub_title ?></span>
			<?php } ?>

			<?php if (!empty($event->description)) { ?>
				<br/>
				<span class="event-description"><?php echo $event->description; ?></span>
			<?php } ?>

			<?php if (!empty($event->user)) { ?>
				<br/>
				<span class="event-user vcard">
					<?php echo get_avatar($event->user->ID, apply_filters('mptt_column_events_avatar_size', 32), '', $event->user->display_name); ?>
					<?php echo $event->user->display_name ?>
				</span>
			<?php } ?>
		</p>
	<?php endforeach;

}

do_action('mptt_column_events_after_events', $events);