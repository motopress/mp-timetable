<?php
if (is_active_sidebar('mptt-sidebar')) {
	dynamic_sidebar('mptt-sidebar');

} elseif (get_option('template') != 'twentyfourteen') {

	the_widget('timetable\classes\widgets\Timetable_widget', apply_filters('mptt_widget_settings', array(
			'title' => __('Today upcoming events', 'mp-timetable'),
			'limit' => '3')
	), array('widget_id' => 'wp-timetable-1' . $post->ID));

	the_widget('timetable\classes\widgets\Timetable_widget', apply_filters('mptt_widget_settings', array(
		'title' => __('All upcoming events', 'mp-timetable'),
		'view_settings' => 'all',
		'limit' => '10',
		'next_days' => '5',
	)), array('widget_id' => 'wp-timetable-2' . $post->ID));

};
