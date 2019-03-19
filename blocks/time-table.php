<?php

if (! function_exists("array_key_last")) {
    function array_key_last($array) {
        if (!is_array($array) || empty($array)) {
            return NULL;
        }
        
        return array_keys($array)[count($array)-1];
    }
}

function create_shortcode($attributes) {

    $last_key = array_key_last($attributes);

    $shortcode = '[mp-timetable ';
    foreach ($attributes as $key=>$value) {

        if (is_array($value)) {

            if ($key == 'col' && !count($value)) {
                continue;
            } else {
                $shortcode .= $key . '="';
                foreach($value as $key=>$data) {

                    $shortcode .= $data;
                    $shortcode .= ($data == end($value)) ? '" ' : ',';
                }
            }
        } else {
            if ($key == "sub_title") {
                $key = "sub-title";
            }
            $shortcode .= $key . '="';
            $shortcode .= ($key == $last_key) ? ($value . '"]') : ($value . '" ');
        }
    }

    return $shortcode;
}

function is_empty_events($attributes) {
    $is_empty = function($array) { return !count($array); };
    foreach ($attributes as $key=>$value) {
        
        if ($key == 'events') {            
            if ($is_empty($value)) return false;
        } elseif ($key == 'event_categ') {
            if ($is_empty($value)) return false;
        }
    }

    return true;
}

function render_getwid_timetable( $attributes ) {

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    $block_name = 'wp-block-getwid-time-table';

    $class = $block_name;
    if ( isset( $attributes['align'] ) ) {
        $class .= ' align' . $attributes['align'];
    }

    ob_start();
    ?>

    <div class="<?php echo esc_attr( $class ); ?>">
        <?php
            if ( is_plugin_active( 'mp-timetable/mp-timetable.php' ) ) {
                if (is_empty_events($attributes)) {
                    echo do_shortcode( create_shortcode($attributes) );
                }
            } else {
                echo esc_html__('Install plug-in', 'getwid')." <a href='https://motopress.com/products/timetable-event-schedule/'>Timetable & Event Schedule</a>";
            }
        ?>
    </div>
    <?

    $result = ob_get_clean();
    return $result;
}

wp_register_script(
    'mptt-time-table-js',
    plugins_url( '../media/js/blocks/dist/index.js', __FILE__ ),
    array( 'wp-i18n', 'wp-editor', 'wp-element', 'wp-blocks', 'wp-components', 'wp-api', 'wp-api-fetch', 'mptt-functions', 'mptt-event-object')
);

wp_register_style(
    'mptt-time-table-css',
    plugins_url( '../media/css/style.css', __FILE__ ),
    array()
);

register_block_type(
    'mp-timetable/time-table',
    array(
        'attributes' => array(
            'align' => array(
                'type' => 'string',
            ),
            'col' => array(
                'type' => 'array',
                'default' => [],
            ),
            'events' => array(
                'type' => 'array',
                'default' => [],
            ),
            'event_categ' => array(
                'type' => 'array',
                'default' => [],
            ),
            'increment' => array(
                'type' => 'string',
                'default' => '1',
            ),
            'view' => array(
                'type' => 'string',
                'default' => 'dropdown_list',
            ),
            'label' => array(
                'type' => 'string',
                'default' => 'All Events',
            ),
            'hide_label' => array(
                'type' => 'string',
                'default' => '0',
            ),
            'hide_hrs' => array(
                'type' => 'string',
                'default' => '0',
            ),
            'hide_empty_rows' => array(
                'type' => 'string',
                'default' => '1',
            ),
            'title' => array(
                'type' => 'string',
                'default' => '1',
            ),
            'time' => array(
                'type' => 'string',
                'default' => '1',
            ),
            'sub_title' => array(
                'type' => 'string',
                'default' => '1',
            ),
            'description' => array(
                'type' => 'string',
                'default' => '1',
            ),
            'user' => array(
                'type' => 'string',
                'default' => '1',
            ),
            'disable_event_url' => array(
                'type' => 'string',
                'default' => '0',
            ),
            'text_align' => array(
                'type' => 'string',
                'default' => 'center',
            ),
            'id' => array(
                'type' => 'string',
                'default' => '',
            ),
            'row_height' => array(
                'type' => 'string',
                'default' => '45',
            ),
            'font_size' => array(
                'type' => 'string',
                'default' => '1',
            ),            
            'responsive' => array(
                'type' => 'string',
                'default' => '1',
            ),            
            'text_align_vertical' => array(
                'type' => 'string',
                'default' => 'default',
            ),
            'group' => array(
                'type' => 'string',
                'default' => '0',
            ),
            'custom_class' => array(
                'type' => 'string',
                'default' => '',
            ),            
        ),
        'render_callback' => 'render_getwid_timetable',
        'style'           => 'mptt-time-table-css',
        'editor_style'    => 'mptt-time-table-css',
        'script'          => 'mptt-time-table-js',
        'editor_script'   => 'mptt-time-table-js',
    )
);