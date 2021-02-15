<?php get_header();

do_action('mptt-single-mp-event-before-wrapper');

do_action('mptt_before_main_wrapper');

while (have_posts()) : the_post();
?>
	<div <?php post_class(apply_filters('mptt_main_wrapper_class', 'mptt-main-wrapper')) ?>>
		<div class="<?php echo apply_filters('mptt_event_template_content_class', 'mptt-content') ?>">
			<?php
				/**
				 * add_action('mptt_event_item_content', 'mptt_event_template_content_title', 10);
				 * add_action('mptt_event_item_content', 'mptt_event_template_content_thumbnail', 20);
				 * add_action('mptt_event_item_content', 'mptt_event_template_content_post_content', 30);
				 * add_action('mptt_event_item_content', 'mptt_event_template_content_title', 40);
				 * add_action('mptt_event_item_content', 'mptt_event_template_content_time_list', 50);
				 */
				do_action('mptt_event_item_content');
			?>
		</div>
		<div class="<?php echo apply_filters('mptt_sidebar_class', 'mptt-sidebar') ?>">
			<?php
				do_action('mptt_sidebar');
			?>
		</div>
		<div class="mptt-clearfix"></div>
	</div>

	<?php
endwhile;

do_action('mptt_after_main_wrapper');

do_action('mptt-single-mp-event-after-wrapper');

get_footer(); ?>