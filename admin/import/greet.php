<div class="narrow">
	<p><?php _e('Howdy! Upload your WordPress eXtended RSS (WXR) file and we&#8217;ll import the posts, pages, comments, custom fields, categories, and tags into this site.', 'mp-timetable') ?></p>
	<p><?php _e('Choose a WXR (.xml) file to upload, then click Upload file and import.', 'mp-timetable') ?></p>
	<?php wp_import_upload_form('admin.php?import=mptt-importer&amp;step=1') ?>
</div>