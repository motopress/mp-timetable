<?php use mp_timetable\classes\models\Settings;
use mp_timetable\plugin_core\classes\Core;

function mptt_event_template_content_title() { ?>
	<h1 class="event-title"><?php the_title() ?></h1>
	<?php
}

function mptt_event_template_content_thumbnail() { ?>
	<div class="thumbnail-wrapper">
		<?php if (has_post_thumbnail()) {
			the_post_thumbnail(apply_filters('mptt_event_template_content_thumbnail_size', 'large'), array('class' => "event-thumbnail"));
		} ?>
	</div>
	<?php
}

function mptt_event_template_content_post_content() { ?>
	<div class="event-content"><?php the_content(); ?></div>
	<?php
}

function mptt_event_template_content_time_title() {
	$count = count(mptt_get_event_data());
	if (!empty($count)) {
		?>
		<h3 class="timeslots-title"><?php printf(__('Event Timeslots (%s)', 'mp-timetable'), $count); ?></h3>
		<?php
	}
}

function mptt_event_template_content_time_list() {
	$events = mptt_get_event_data();
	do_action('mptt-before-timeslots', $events);
	?>
	<ul class="mptt-event <?php echo apply_filters('mptt_events_list_class', 'events-list') ?>">
		<?php foreach ($events as $event): ?>
			<li class="event mptt-colorized" id="event_hours_<?php echo $event->event_id ?>">

				<h4 class="event-title">
					<a class="event-link" href="<?php echo get_permalink($event->column_id); ?>" title="<?php the_title_attribute(array('post' => $event->event_id)); ?>"><?php echo get_the_title($event->column_id); ?></a>
				</h4>
				<p class="timeslot">
					<time datetime="<?php echo $event->event_start; ?>" class="timeslot-start"><?php
						echo date(get_option('time_format'), strtotime($event->event_start)); ?></time><?php
					echo apply_filters('mptt_timeslot_delimiter', ' - '); ?>
					<time datetime="<?php echo $event->event_end; ?>" class="timeslot-end"><?php
						echo date(get_option('time_format'), strtotime($event->event_end)); ?></time>
				</p>
				<?php if (!empty($event->post->sub_title)) { ?>
					<p class="event-subtitle"><?php echo $event->post->sub_title; ?></p>
				<?php } ?>

				<?php if (!empty($event->description)) { ?>
					<p class="event-description"><?php echo $event->description; ?></p>
				<?php } ?>
				<?php if (!empty($event->user)) { ?>
					<p class="event-user"><a href="<?php echo get_author_posts_url($event->user->ID); ?>" title="<?php echo $event->user->display_name; ?>"><?php echo get_avatar($event->user->ID, apply_filters('mptt-column-user-avatar-size', 32), '', $event->user->display_name) . ' ';
							echo $event->user->display_name ?></a></p>
				<?php } ?>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
	do_action('mptt-after-timeslots', $events);
}

function mptt_event_template_content_comments() {
	// If comments are open or we have at least one comment, load up the comment template.
	if (comments_open() || get_comments_number()) {
		comments_template();
	}
}

/**
 * Get post event data
 *
 * @return array
 */
function mptt_get_event_data() {
	global $post;
	$data = Core::get_instance()->get_controller('events')->get_all_event_by_post($post);
	return !empty($data) ? $data : array();
}

function mptt_theme_wrapper_before() {
	$template = get_option('template');
	switch ($template) {
		case 'twentyeleven' :
			echo '<div id="primary"><div id="content" role="main" class="twentyeleven">';
			break;
		case 'twentytwelve' :
			echo '<div id="primary" class="site-content"><div id="content" role="main" class="twentytwelve">';
			break;
		case 'twentythirteen' :
			echo '<div id="primary" class="site-content"><div id="content" role="main" class="entry-content twentythirteen">';
			break;
		case 'twentyfourteen' :
			echo '<div id="primary" class="content-area"><div id="content" role="main" class="site-content twentyfourteen"><div class="tfmp">';
			break;
		case 'twentyfifteen' :
			echo '<div id="primary" role="main" class="content-area twentyfifteen"><div id="main" class="site-main t15mp">';
			break;
		case 'twentysixteen' :
			echo '<div id="primary" class="content-area twentysixteen"><main id="main" class="site-main" role="main">';
			break;
		default :
			echo '<div id="container"><div id="content" role="main">';
			break;
	}
}

/**
 * Theme popular class
 * @return string
 */
function mptt_popular_theme_class() {
	$template = get_option('template');

	switch ($template) {
		case 'twentyeleven' :
			$class = ' twentyeleven';
			break;
		case 'twentytwelve' :
			$class = ' twentytwelve';
			break;
		case 'twentythirteen' :
			$class = ' twentythirteen';
			break;
		case 'twentyfourteen' :
			$class = ' twentyfourteen';
			break;
		case 'twentyfifteen' :
			$class = ' twentyfifteen';
			break;
		case 'twentysixteen' :
			$class = ' twentysixteen';
			break;
		default :
			$class = '';
			break;
	}
	return $class;
}

/**
 * Filter post class
 *
 * @param $classes
 * @param string $class
 * @param string $post_id
 *
 * @return mixed
 */
function mptt_post_class($classes, $class = '', $post_id = '') {

	if (!$post_id || !in_array(get_post_type($post_id), Core::get_instance()->post_types)) {
		return $classes;
	}
	if ('mp-column' == get_post_type($post_id)) {
		$classes[] = 'mp-column-item';
	} elseif ('mp-event' == get_post_type($post_id)) {
		$classes[] = 'mp-event-item';
	}
	if (!is_search() && is_single() && Settings::get_instance()->is_plugin_template_mode()) {
		if (false !== ($key = array_search('hentry', $classes))) {
			unset($classes[$key]);
		}
	}

	return $classes;
}

function mptt_theme_wrapper_after() {

	$template = get_option('template');

	switch ($template) {
		case 'twentyeleven' :
			echo '</div></div>';
			break;
		case 'twentytwelve' :
			echo '</div></div>';
			break;
		case 'twentythirteen' :
			echo '</div></div>';
			break;
		case 'twentyfourteen' :
			echo '</div></div></div>';
			get_sidebar('content');
			break;
		case 'twentyfifteen' :
			echo '</div></div>';
			break;
		case 'twentysixteen' :
			echo '</div></main>';
			break;
		default :
			echo '</div></div>';
			break;
	}
}