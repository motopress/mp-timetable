<h3><?php _e('Export', 'mp-timetable') ?></h3>
<form novalidate="novalidate" method="post" id="mptt_export">
	<input type="hidden" name="controller" value="import">
	<input type="hidden" name="mptt_action" value="export">
	<?php
		wp_nonce_field( 'mptt_export_xml' );
	?>
	<p class="submit"><input type="submit" value="<?php _e('Export', 'mp-timetable') ?>" class="button button-primary" id="submit" name="submit"></p>
</form>