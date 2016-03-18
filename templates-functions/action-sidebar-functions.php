<?php
use mp_timetable\plugin_core\classes\View;

function mptt_sidebar() {
	global $post;
	View::get_instance()->render_html('templates-actions/action-sidebar', array('post' => $post));
}

