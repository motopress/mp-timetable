<?php use mp_timetable\classes\models\Settings;

function mptt_widget_template_before_content() {
	$wrapper_class = mptt_popular_theme_class();
	if (Settings::get_instance()->is_plugin_template_mode()) {
		?>
		<div class="<?php echo apply_filters('mptt_widget_wrapper_class', 'upcoming-events-widget' . $wrapper_class) ?>">
		<ul class="mptt-widget <?php echo apply_filters('mptt_events_list_class', 'events-list') ?>">
		<?php
	} else {
		?>
		<div class="widget_recent_entries  <?php echo apply_filters('mptt_widget_theme_wrapper_class', 'theme-upcoming-events-widget' . $wrapper_class) ?>">
		<ul class="mptt-widget <?php echo apply_filters('mptt_events_list_class', '') ?>">
		<?php
	}
}

function mptt_widget_template_after_content() { ?>
	</ul>
	</div>
	<?php if(Settings::get_instance()->is_plugin_template_mode()):?>
		<div class="mptt-clearfix"></div><?php
	endif;
}

function mptt_widget_template_content() {
}

/**
 * Widget settings
 *
 * @param $params
 *
 * @return array
 */
function mptt_widget_settings($params) {
	
	$params = shortcode_atts(array(
		'title' => '',
		'limit' => '3',
		'view_settings' => 'today',
		'next_days' => '1',
		'time_settings' => '',
		'mp_categories' => '',
		'custom_url' => '',
		'disable_url' => '',
		'background_color' => '',
		'hover_background_color' => '',
		'text_color' => '',
		'hover_text_color' => '',
		'item_border_color' => '',
		'hover_item_border_color' => '',
	), $params);
	
	return $params;
}