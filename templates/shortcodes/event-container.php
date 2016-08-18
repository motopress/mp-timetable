<div data-id="<?php echo $item->id ?>"
     data-event-id="<?php echo $item->event_id ?>"
     data-start="<?php echo empty($startIndex) ? $item->start_index : $startIndex ?>"
     data-start-item="<?php echo $item->start_index ?>"
     data-end="<?php echo $item->end_index ?>"
     class="mptt-event-container id-<?php echo $item->id; ?> mptt-colorized"
     data-type="event"
     data-bg_hover_color="<?php echo $item->post->hover_color ? $item->post->hover_color : '' ?>"
     data-bg_color="<?php echo $item->post->color ? $item->post->color : '' ?>"
     data-hover_color="<?php echo $item->post->hover_text_color ? $item->post->hover_text_color : '' ?>"
     data-color="<?php echo $item->post->text_color ? $item->post->text_color : '' ?>"
     style="<?php echo $params['text_align'] ? 'text-align:' . $params['text_align'] . ';' : '' ?>
     <?php echo $item->post->color ? 'background-color:' . $item->post->color . ';' : '' ?>
     <?php echo $item->post->text_color ? 'color:' . $item->post->text_color : '' ?>">
	<?php if ($params['title']): ?>
		<?php
		$disable_url = (bool)$item->post->timetable_disable_url || (bool)$params['disable_event_url'];
		if (!$disable_url) { ?>
			<a title="<?php echo $item->post->post_title; ?>"
			   href="<?php echo ($item->post->timetable_custom_url != "") ? $item->post->timetable_custom_url : get_permalink($item->event_id); ?>"
			   class="event-title">
				<?php echo $item->post->post_title; ?>
			</a>
		<?php } ?>
		<?php if ($disable_url) { ?>
			<span class="event-title"><?php echo $item->post->post_title; ?></span>
		<?php } ?>

	<?php endif; ?>
	<?php if ($params['time']): ?>
		<p class="timeslot">
			<time datetime="<?php echo $item->event_start; ?>"
			      class="timeslot-start"><?php echo date(get_option('time_format'), strtotime($item->event_start)); ?></time>
			<span class="timeslot-delimiter"><?php echo apply_filters('mptt_timeslot_delimiter', ' - '); ?></span>
			<time datetime="<?php echo $item->event_end; ?>"
			      class="timeslot-end"><?php echo date(get_option('time_format'), strtotime($item->event_end));; ?></time>
		</p>
	<?php endif; ?>
	<?php if ($params['sub-title'] && !empty($item->post->sub_title)): ?>
		<p class="event-subtitle"><?php echo $item->post->sub_title; ?></p>
	<?php endif; ?>

	<?php

	if ($params['description'] && !empty($item->description)): ?>
		<p class="event-description"><?php echo $item->description; ?></p>
	<?php endif; ?>

	<?php if ($params['user'] && $item->user_id != '-1'): ?>
		<p class="event-user"><?php $user_info = get_userdata($item->user_id);
		if ($user_info) {
			echo get_avatar($item->user_id, apply_filters('mptt-event-user-avatar-size', 24), '', $user_info->data->display_name);
			echo $user_info->data->display_name;
		} ?>
		</p><?php endif; ?>
</div>