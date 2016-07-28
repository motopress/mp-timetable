<?php
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
