<?php
use mp_timetable\plugin_core\classes\View as View;

View::get_instance()->render_html('../admin/import/header', $data);

?>
<div class="motopress-offer-primary">
<?php

if ( current_user_can('export') ) {
	View::get_instance()->render_html('../admin/import/export', $data);
}

if ( current_user_can('import') ) {
	View::get_instance()->render_html('../admin/import/import', $data);
}
?>
</div>
<?php
/*
 * Offer free plugins
 *
 */
require_once Mp_Time_Table::get_plugin_path() . 'classes/class-offer.php';
$plugins_offer = new \Plugins_Offer();
$plugins_offer->render();

View::get_instance()->render_html('../admin/import/footer', $data);
