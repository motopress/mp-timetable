<?php
function mptt_widget_template_before_content() { ?>
	<div class="widget_recent_entries  <?php echo apply_filters('mptt_widget_wrapper_class', 'upcoming-events-widget' . mptt_popular_theme_class()) ?>">
	<ul class="mptt-widget <?php echo apply_filters('mptt_events_list_class', 'events-list') ?>">
	<?php
}

function mptt_widget_template_after_content() { ?>

	</ul>
	</div>
	<div class="mptt-clearfix"></div>
	<?php
}

function mptt_widget_template_content() {
}