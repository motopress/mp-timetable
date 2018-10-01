<h3><?php _e('Export', 'mp-timetable') ?></h3>
<form novalidate="novalidate" method="post" id="mptt_export">
	<input type="hidden" name="controller" value="import">
	<input type="hidden" name="mptt_action" value="export">
	<p class="submit"><input type="submit" value="<?php _e('Export', 'mp-timetable') ?>" class="button button-primary" id="submit" name="submit"></p>
</form>