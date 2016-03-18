<?php
use mp_timetable\plugin_core\classes\View as View;

View::get_instance()->render_html('../admin/import/header', $data);

View::get_instance()->render_html('../admin/import/export', $data);

View::get_instance()->render_html('../admin/import/import', $data);

View::get_instance()->render_html('../admin/import/footer', $data);
