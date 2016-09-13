<?php get_header();

do_action('mptt-single-mp-column-before-wrapper');

do_action('mptt-single-before-wrapper');

while (have_posts()) : the_post();
	?>
	<div <?php post_class(apply_filters('mptt_main_wrapper_class', 'mptt-main-wrapper')) ?>>
		<?php
		/**
		 * add_action('mptt_single_column_template_content', 'mptt_column_template_content_title', 10);
		 * add_action('mptt_single_column_template_content', 'mptt_column_template_content_post_content', 20);
		 * add_action('mptt_single_column_template_content', 'mptt_column_template_content_events_list', 30);
		 */
		do_action('mptt_single_column_template_content');
		?>
		<div class="mptt-clearfix"></div>
	</div>

	<?php
endwhile;

do_action('mptt_after_main_wrapper'); ?>
	<div class="mptt-clearfix"></div>
<?php
do_action('mptt-single-mp-column-after-wrapper');
get_footer(); ?>