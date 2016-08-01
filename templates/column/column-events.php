<?php
foreach ($events as $event): ?>
	<p class="event" id="event_columns_<?php echo $event->event_id ?>">

		<?php if (has_post_thumbnail($event->event_id)) {
			echo get_the_post_thumbnail( $event->event_id, apply_filters('mptt_event_thumbnail_size', 'thumbnail'), array('class' => "alignleft event-thumbnail") );
		} else { ?>
			<img class="alignleft event-thumbnail event-thumbnail-default" src="<?php echo \Mp_Time_Table::get_plugin_url() . 'media/css/images/column_icon.png' ?>">
		<?php } ?>

		<a href="<?php echo $event->post->timetable_disable_url == '1' ? '#' : ($event->post->timetable_custom_url != "" ? $event->post->timetable_custom_url : get_permalink($event->event_id)) ?>" class="event-link">
			<?php echo get_the_title($event->event_id); ?>
		</a>

		<br />
		<span class="timeslot-start"><?php echo date(get_option('time_format'), strtotime($event->event_start)); ?></span><?php echo apply_filters('mptt_timeslot_delimiter', ' - '); ?><span class="timeslot-end"><?php echo date(get_option('time_format'), strtotime($event->event_end)); ?></span>

		<?php if (!empty($event->post->sub_title)) { ?>
			<br />
			<span class="event-subtitle"><?php echo $event->post->sub_title ?></span>
		<?php } ?>

		<?php if (!empty($event->description)) { ?>
			<br />
			<span class="event-description"><?php echo $event->description; ?></span>
		<?php } ?>

		<?php if (!empty($event->user)) { ?>
			<br />
			<span class="event-user"><a href="<?php echo get_author_posts_url($event->user->ID); ?>"><?php echo get_avatar( $event->user->ID, apply_filters('mptt-column-user-avatar-size', 32) ); ?>
					<?php echo $event->user->display_name ?></a></span>
		<?php } ?>
	</p>
<?php endforeach;