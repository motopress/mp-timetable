<?php

namespace mp_timetable\plugin_core\classes;

use \Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor register widgets.
 */
class Widgets_Manager {

    /**
     * WidgetsManager constructor.
     */
    public function __construct() {
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ], 12 );
    }

    public function register_widgets() {
        $path = \Mp_Time_Table::get_plugin_path() . 'classes/widgets/class-mp-timetable-elementor-widget.php';

        if ( file_exists( $path ) ) {
            require_once( $path );
        }
    }
}