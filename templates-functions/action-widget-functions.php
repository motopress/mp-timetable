<?php
function mptt_widget_template_before_content() {
	$wrapper_class = mptt_popular_theme_class();
	if (\mp_timetable\classes\models\Settings::get_instance()->is_plugin_template_mode()) {
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
	<?php if(\mp_timetable\classes\models\Settings::get_instance()->is_plugin_template_mode()):?>
		<div class="mptt-clearfix"></div><?php
	endif;
}

function mptt_widget_template_content() {
}