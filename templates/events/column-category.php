<?php
foreach ($terms as $term) {
	?>
	<a href="<?php echo get_term_link($term->term_id) ?>" rel="tag" title="<?php echo $term->name; ?>"><?php echo $term->name ?></a><?php echo ($term !== end($terms)) ? ', ' : '' ?>
	<?php
}
