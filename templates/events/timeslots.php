<?php

if (!empty($count)) {
	?>
	<h3 class="timeslots-title"><?php printf(__('Event Timeslots (%s)', 'mp-timetable'), $count); ?></h3>
	<?php
}
?>
<?php foreach ($events as $event): ?>
	<p class="event mptt-colorized" id="event_hours_<?php echo $event->event_id ?>">

		<a class="event-link"
		   href="<?php echo get_permalink($event->column_id); ?>"><?php echo get_the_title($event->column_id); ?></a>

		<br/>
		<span class="timeslot-start"><?php
					echo date(get_option('time_format'), strtotime($event->event_start)); ?></span><?php
		echo apply_filters('mptt_timeslot_delimiter', ' - '); ?><span class="timeslot-end"><?php
			echo date(get_option('time_format'), strtotime($event->event_end)); ?></span>

		<?php if (!empty($event->post->sub_title)) { ?>
			<br/>
			<span class="event-subtitle"><?php echo $event->post->sub_title; ?></span>
		<?php } ?>

		<?php if (!empty($event->description)) { ?>
			<br/>
			<span class="event-description"><?php echo $event->description; ?></span>
		<?php } ?>
		<?php if (!empty($event->user)) { ?>
			<br/>
			<span class="event-user"><a href="<?php echo get_author_posts_url($event->user->ID); ?>"><?php echo get_avatar($event->user->ID, apply_filters('mptt-column-user-avatar-size', 32)) . ' ';
				echo $event->user->display_name ?></a></span>
		<?php } ?>
	</p>
<?php endforeach; ?>