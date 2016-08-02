<?php

if (!empty($count)) {
	?>
	<h3 class="timeslots-title"><?php printf(__('Event Timeslots (%s)', 'mp-timetable'), $count); ?></h3>
	<?php
}
?>
<?php

	$time_format = get_option('time_format');

	foreach ($events as $event): ?>
	<p class="timeslot">

		<a class="timeslot-link"
		   href="<?php echo get_permalink($event->column_id); ?>" title="<?php the_title_attribute( array('post' => $event->event_id) ); ?>"><?php echo get_the_title($event->column_id); ?></a>

		<br/>
		<time datetime="<?php echo $event->event_start; ?>" class="timeslot-start"><?php
					echo date($time_format, strtotime($event->event_start)); ?></time><?php
		echo apply_filters('mptt_timeslot_delimiter', ' - '); ?><time datetime="<?php echo $event->event_start; ?>" class="timeslot-end"><?php
			echo date($time_format, strtotime($event->event_end)); ?></time>

		<?php if (!empty($event->post->sub_title)) { ?>
			<br/>
			<span class="timeslot-subtitle"><?php echo $event->post->sub_title; ?></span>
		<?php } ?>

		<?php if (!empty($event->description)) { ?>
			<br/>
			<span class="timeslot-description"><?php echo $event->description; ?></span>
		<?php } ?>
		<?php if (!empty($event->user)) { ?>
			<br/>
			<span class="timeslot-user vcard">
				<?php echo get_avatar($event->user->ID, apply_filters('mptt-column-user-avatar-size', 32), '', $event->user->display_name); ?>
				<?php echo $event->user->display_name; ?>
			</span>
		<?php } ?>
	</p>
<?php endforeach; ?>