<?php
use mp_timetable\classes\controllers\Controller_Column;

function mptt_column_template_content_title() {
	?>
	<h1 class="column-title"><?php the_title(); ?></h1>
<?php }

function mptt_column_template_content_post_content() {
	?>
	<div class="column-content">
		<?php the_content(); ?>
	</div>
<?php }

function mptt_column_template_content_events_list() { ?>

	<ul class="mptt-column <?php echo apply_filters('mptt_events_list_class', 'events-list') ?>">
		<?php foreach (mptt_get_column_events() as $event): ?>
			<li class="event" id="event_columns_<?php echo $event->event_id ?>">

				<?php if (has_post_thumbnail($event->event_id)) {

					echo wp_get_attachment_image(get_post_thumbnail_id($event->event_id), apply_filters('mptt_event_thumbnail_size', 'thumbnail'), false, array('class' => "event-thumbnail"));
				} else { ?>
					<img class="event-thumbnail event-thumbnail-default" src="<?php echo Mp_Time_Table::get_plugin_url() . 'media/css/images/column_icon.png' ?>">
				<?php } ?>

				<h4 class="event-title">
					<a href="<?php echo $event->post->timetable_disable_url == '1' ? '#' : ($event->post->timetable_custom_url != "" ? $event->post->timetable_custom_url : get_permalink($event->event_id)) ?>" class="event-link">
						<?php echo get_the_title($event->event_id); ?>
					</a>
				</h4>

				<p class="timeslot">
					<span class="timeslot-start"><?php echo $event->event_start ?></span><?php echo apply_filters('mptt_timeslot_delimiter', ' - '); ?><span class="timeslot-end"><?php echo $event->event_end; ?></span>
				</p>

				<?php if (!empty($event->post->sub_title)) { ?>
					<p class="event-subtitle"><?php echo $event->post->sub_title ?></p>
				<?php } ?>

				<?php if (!empty($event->description)) { ?>
					<p class="event-description"><?php echo $event->description; ?></p>
				<?php } ?>

				<?php if (!empty($event->user)) { ?>
					<p class="event-user"><a href="<?php echo get_author_posts_url($event->user->ID); ?>">
							<?php echo get_avatar( $event->user->ID, apply_filters('mptt-column-user-avatar-size', 32) ); ?>
							<?php echo $event->user->display_name ?>
						</a></p>
					<div class="mptt-clearfix"></div>
				<?php } ?>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
}

function mptt_get_column_events() {
	global $post;
	$data = Controller_Column::get_instance()->action_page_view($post);
	if (!empty($data)) {
		return $data;
	} else {
		return array();
	}
}