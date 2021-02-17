<?php

namespace timetable\classes\widgets;

use \Elementor\Controls_Manager;
use \Elementor\Plugin;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;

use \mp_timetable\plugin_core\classes\Shortcode;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Mp_Timetable_Widget extends Widget_Base {

    public function get_name() {
        return 'timetable';
    }

	public function get_title() {
		return __( 'Timetable', 'mp-timetable' );
	}

	public function get_icon() {
		return 'eicon-table';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

    public function get_posts_type( $post_type ) {
        $args   = [ 'post_type' => $post_type, 'numberposts' => '-1' ];
        $posts  = get_posts( $args );
    	$return = [];

    	if ( ! empty( $posts ) ) {
    		foreach ( $posts as $post ) {
    			$return[ $post->ID ] = $post->post_title;
    		}
    	}

    	return $return;
    }

    public function get_terms_type( $term_type ) {
        $terms = get_terms( $term_type, 'orderby=count&hide_empty=0' );

        $return = [];

        if ( ! empty( $terms ) ) {
            foreach ( $terms as $term ) {
                $return[ $term->term_id ] = $term->name;
            }
        }

        return $return;
    }

    private static function show_shortcode( $attributes ) {
        foreach ( $attributes as $key => $value ) {
            // [] -> '1,2,3'
            if ( is_array( $value ) ) {
                $attributes[ $key ] = implode( ',', $value );
            }
            // 'sub_title' -> 'sub-title'
            if ( $key == 'sub_title' ) {
                $attributes[ 'sub-title' ] = $attributes[ $key ];
                unset( $attributes[ $key ] );
            }
        }

        echo Shortcode::get_instance()->show_shortcode( $attributes );
    }

    public static function elementor_render_timetable( $attributes ) {

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        self::show_shortcode( $attributes );
    }

    protected function _register_controls() {
        $controls = $this;

        $controls->start_controls_section(
            'content_settings',
            [
                'label' => __( 'Settings', 'mp-timetable' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

            $controls->add_control(
                'label',
                [
                    'label'       => esc_html__( 'Filter title to display all events', 'mp-timetable' ),
                    'label_block' => true,
                    'type'        => Controls_Manager::TEXT,
                    'default'     => esc_html__( 'All Events', 'mp-timetable' ),
                ]
            );

            $controls->add_control(
                'hide_label',
                [
                    'label'     => esc_html__( 'Hide \'All Events\' option', 'mp-timetable' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'no',
                ]
            );

            $controls->add_control(
                'col',
                [
                    'label'       => esc_html__( 'Columns', 'mp-timetable' ),
                    'label_block' => true,
                    'type'        => Controls_Manager::SELECT2,
                    'multiple'    => true,
                    'options'     => $this->get_posts_type( 'mp-column' ),
                    'separator'   => 'before',
                ]
            );

            $controls->add_control(
                'events',
                [
                    'label'       => esc_html__( 'Specific events', 'mp-timetable' ),
                    'label_block' => true,
                    'type'        => Controls_Manager::SELECT2,
                    'multiple'    => true,
                    'options'     => $this->get_posts_type( 'mp-event' ),
                ]
            );

            $controls->add_control(
                'event_categ',
                [
                    'label'       => esc_html__( 'Event categories', 'mp-timetable' ),
                    'label_block' => true,
                    'type'        => Controls_Manager::SELECT2,
                    'multiple'    => true,
                    'options'     => $this->get_terms_type( 'mp-event_category' ),
                    'separator'   => 'after',
                ]
            );

            $controls->add_control(
                'title',
                [
                    'label'     => esc_html__( 'Title', 'mp-timetable' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'label_off' => esc_html__( 'Hide', 'mp-timetable' ),
                    'label_on'  => esc_html__( 'Show', 'mp-timetable' ),
                    'default'   => 'yes',
                ]
            );

            $controls->add_control(
                'time',
                [
                    'label'     => esc_html__( 'Time', 'mp-timetable' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'label_off' => esc_html__( 'Hide', 'mp-timetable' ),
                    'label_on'  => esc_html__( 'Show', 'mp-timetable' ),
                    'default'   => 'yes',
                ]
            );

            $controls->add_control(
                'sub_title',
                [
                    'label'     => esc_html__( 'Subtitle', 'mp-timetable' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'label_off' => esc_html__( 'Hide', 'mp-timetable' ),
                    'label_on'  => esc_html__( 'Show', 'mp-timetable' ),
                    'default'   => 'no',
                ]
            );

            $controls->add_control(
                'description',
                [
                    'label'     => esc_html__( 'Description', 'mp-timetable' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'label_off' => esc_html__( 'Hide', 'mp-timetable' ),
                    'label_on'  => esc_html__( 'Show', 'mp-timetable' ),
                    'default'   => 'yes',
                ]
            );

            $controls->add_control(
                'user',
                [
                    'label'     => esc_html__( 'Event Head', 'mp-timetable' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'label_off' => esc_html__( 'Hide', 'mp-timetable' ),
                    'label_on'  => esc_html__( 'Show', 'mp-timetable' ),
                    'default'   => 'no',
                ]
            );

            $controls->add_control(
                'hide_hrs',
                [
                    'label'     => esc_html__( 'Hide column with hours', 'mp-timetable' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'no',
                ]
            );

            $controls->add_control(
                'hide_empty_rows',
                [
                    'label'     => esc_html__( 'Do not display empty rows', 'mp-timetable' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'yes',
                ]
            );

            $controls->add_control(
                'group',
                [
                    'label'     => esc_html__( 'Merge cells with common events', 'mp-timetable' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'no',
                ]
            );

            $controls->add_control(
                'disable_event_url',
                [
                    'label'     => esc_html__( 'Disable event link', 'mp-timetable' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'no',
                ]
            );

            $controls->add_control(
                'font_size',
                [
                    'label'       => esc_html__( 'Base font size', 'mp-timetable' ),
                    'label_block' => true,
                    'type'        => Controls_Manager::TEXT,
                    'description' => __( 'Base font size for the table. Example 12px, 2em, 80%.', 'mp-timetable' ),
                    'separator' => 'before',
                ]
            );

            $controls->add_control(
                'row_height',
                [
                    'label'     => esc_html__( 'Block height in pixels', 'mp-timetable' ),
                    'type'      => Controls_Manager::NUMBER,
                    'default'   => 45,
                ]
            );

            $controls->add_control(
                'increment',
                [
                    'label'       => esc_html__( 'Time frame for event', 'mp-timetable' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '1',
                    'label_block' => false,
                    'options'     => [
                        '1'       => esc_html__( 'Hour (1h)', 'mp-timetable' ),
                        '0.5'     => esc_html__( 'Half hour (30min)', 'mp-timetable' ),
                        '0.25'    => esc_html__( 'Quarter hour (15min)', 'mp-timetable' ),
                    ],
                ]
            );

            $controls->add_control(
                'view',
                [
                    'label'       => esc_html__( 'Filter events style', 'mp-timetable' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'dropdown_list',
                    'label_block' => false,
                    'options'     => [
                        'dropdown_list' => esc_html__( 'Dropdown', 'mp-timetable' ),
                        'tabs'          => esc_html__( 'Tabs', 'mp-timetable' ),
                    ],
                ]
            );

            $controls->add_control(
                'view_sort',
                [
                    'label'       => esc_html__( 'Order of items in filter', 'mp-timetable' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '',
                    'label_block' => false,
                    'options'     => [
                        ''           => esc_html__( 'Default', 'mp-timetable' ),
                        'menu_order' => esc_html__( 'Menu Order', 'mp-timetable' ),
                        'post_title' => esc_html__( 'Title', 'mp-timetable' ),
                    ],
                ]
            );

            $controls->add_control(
                'column_width',
                [
                    'label'       => esc_html__( 'Column width', 'mp-timetable' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '',
                    'label_block' => false,
                    'options'     => [
                        ''        => esc_html__( 'Default', 'mp-timetable' ),
                        'auto'    => esc_html__( 'Auto', 'mp-timetable' ),
                        'fixed'   => esc_html__( 'Fixed', 'mp-timetable' ),
                    ],
                ]
            );

            $controls->add_control(
                'responsive',
                [
                    'label'       => esc_html__( 'Mobile behavior', 'mp-timetable' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '0',
                    'label_block' => false,
                    'options'     => [
                        '0'       => esc_html__( 'Table', 'mp-timetable' ),
                        '1'       => esc_html__( 'List', 'mp-timetable' ),
                    ],
                    'separator' => 'before',
                ]
            );

            $controls->add_control(
                'text_align_horizontal',
                [
                    'label'     => esc_html__( 'Horizontal align', 'mp-timetable' ),
                    'type'      => Controls_Manager::CHOOSE,
                    'default'   => 'center',
                    'toggle'    => false,
                    'options'   => [
                        'left'      => [
                            'title' => esc_html__( 'left', 'mp-timetable' ),
                            'icon'  => 'fa fa-align-left',
                        ],
                        'center'    => [
                            'title' => esc_html__( 'center', 'mp-timetable' ),
                            'icon'  => 'fa fa-align-center',
                        ],
                        'right'     => [
                            'title' => esc_html__( 'right', 'mp-timetable' ),
                            'icon'  => 'fa fa-align-right',
                        ],
                    ],
                    'separator' => 'before',
                ]
            );

            $controls->add_control(
                'text_align_vertical',
                [
                    'label'     => esc_html__( 'Vertical align', 'mp-timetable' ),
                    'type'      => Controls_Manager::CHOOSE,
                    'default'   => 'default',
                    'toggle'    => false,
                    'options'   => [
                        'default'   => [
                            'title' => esc_html__( 'default', 'mp-timetable' ),
                            'icon'  => 'eicon-v-align-stretch',
                        ],
                        'top'       => [
                            'title' => esc_html__( 'top', 'mp-timetable' ),
                            'icon'  => 'eicon-v-align-top',
                        ],
                        'middle'    => [
                            'title' => esc_html__( 'middle', 'mp-timetable' ),
                            'icon'  => 'eicon-v-align-middle',
                        ],
                        'bottom'    => [
                            'title' => esc_html__( 'bottom', 'mp-timetable' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                    ],
                ]
            );

            $controls->add_control(
                'unique_id',
                [
                    'label'       => esc_html__( 'Unique ID', 'mp-timetable' ),
                    'label_block' => true,
                    'type'        => Controls_Manager::TEXT,
                    'description' => __( 'If you use more than one table on a page specify the unique ID for a timetable. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'mp-timetable' ),
                    'dynamic'     => [ 'active' => true ],
                    'separator' => 'before',
                ]
            );

            $controls->add_control(
                'custom_class',
                [
                    'label'       => esc_html__( 'CSS class', 'mp-timetable' ),
                    'label_block' => true,
                    'type'        => Controls_Manager::TEXT,
                    'dynamic'     => [ 'active' => true ]
                ]
            );

        $controls->end_controls_section();
    }

    protected function render() {
        $settings              = $this->get_settings();

        $label                 = $settings[ 'label' ];
        $font_size             = $settings[ 'font_size' ];
        $col                   = $settings[ 'col' ];
        $events                = $settings[ 'events' ];
        $event_categ           = $settings[ 'event_categ' ];
        $title                 = $settings[ 'title' ] === 'yes' ? '1' : '0';
        $time                  = $settings[ 'time' ] === 'yes' ? '1' : '0';
        $subtitle              = $settings[ 'sub_title' ] === 'yes' ? '1' : '0';
        $description           = $settings[ 'description' ] === 'yes' ? '1' : '0';
        $user                  = $settings[ 'user' ] === 'yes' ? '1' : '0';
        $hide_label            = $settings[ 'hide_label' ] === 'yes' ? '1' : '0';
        $hide_hrs              = $settings[ 'hide_hrs' ] === 'yes' ? '1' : '0';
        $hide_empty_rows       = $settings[ 'hide_empty_rows' ] === 'yes' ? '1' : '0';
        $group                 = $settings[ 'group' ] === 'yes' ? '1' : '0';
        $disable_event_url     = $settings[ 'disable_event_url' ] === 'yes' ? '1' : '0';
        $row_height            = $settings[ 'row_height' ];
        $view_sort             = $settings[ 'view_sort' ];
        $increment             = $settings[ 'increment' ];
        $view                  = $settings[ 'view' ];
        $text_align_horizontal = $settings[ 'text_align_horizontal' ];
        $text_align_vertical   = $settings[ 'text_align_vertical' ];
        $column_width          = $settings[ 'column_width' ];
        $responsive            = $settings[ 'responsive' ];
        $id                    = $settings[ 'unique_id' ];
        $custom_class          = $settings[ 'custom_class' ];

        $attributes            = array(
            'col'                 => $col,
            'events'              => $events,
            'event_categ'         => $event_categ,
            'label'               => $label,
            'font_size'           => $font_size,
            'time'                => $time,
            'custom_class'        => $custom_class,
            'id'                  => $id,
            'table_layout'        => $column_width,
            'increment'           => $increment,
            'view'                => $view,
            'view_sort'           => $view_sort,
            'hide_label'          => $hide_label,
            'hide_hrs'            => $hide_hrs,
            'hide_empty_rows'     => $hide_empty_rows,
            'title'               => $title,
            'sub_title'           => $subtitle,
            'description'         => $description,
            'user'                => $user,
            'group'               => $group,
            'disable_event_url'   => $disable_event_url,
            'text_align'          => $text_align_horizontal,
            'row_height'          => $row_height,
            'responsive'          => $responsive,
            'text_align_vertical' => $text_align_vertical,
        );

        $this->elementor_render_timetable( $attributes );
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Mp_Timetable_Widget() );