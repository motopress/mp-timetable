<?php

namespace mp_timetable\plugin_core\classes;

use mp_timetable\classes\models\Column;
use mp_timetable\classes\models\Events;

/**
 * Class Shortcode
 *
 * @package mp_timetable\plugin_core\classes
 */
class Shortcode extends Core {
	
	protected static $instance;
	
	/**
	 * Shortcode constructor.
	 */
	public function __construct() {
		$this->init_plugin_version();
		parent::__construct();
	}
	
	/**
	 * Return instance
	 *
	 * @return Shortcode
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * Init
	 */
	public function init() {
		add_shortcode( 'mp-timetable', array( $this, "show_shortcode" ) );
	}
	
	/**
	 * Show shortcode
	 *
	 * @param $params
	 *
	 * @return mixed
	 */
	public function show_shortcode( $params ) {
		global $mptt_shortcode_data;

		$this->add_plugin_js( 'shortcode' );

		if ( empty( $params ) ) {
			$params = array();
		}

		$mptt_shortcode_data = array();

		$mptt_shortcode_data[ 'params' ] = $params = shortcode_atts( array(
			'events'              => "",
			'event_categ'         => "",
			'col'                 => "",
			'increment'           => "1",
			'view'                => "dropdown_list",
			'label'               => __( "All Events", 'mp-timetable' ),
			'hide_label'          => "0",
			'title'               => "0",
			'time'                => "0",
			'group'               => "0",
			'sub-title'           => "0",
			'description'         => "0",
			'user'                => "0",
			'hide_hrs'            => "0",
			'hide_empty_rows'     => "1",
			'text_align_vertical' => "default",
			'row_height'          => "45",
			'font_size'           => "",
			'disable_event_url'   => "0",
			'text_align'          => "center",
			'id'                  => "",
			'custom_class'        => "",
			'responsive'          => "1"
		), $params );

		$mptt_shortcode_data[ 'events_data' ] = $this->get_shortcode_events( $params );
		
		if ( ! empty( $mptt_shortcode_data[ 'events_data' ] ) ) {
			$mptt_shortcode_data[ 'unique_events' ] = $mptt_shortcode_data[ 'events_data' ][ 'unique_events' ];
			if ( isset( $mptt_shortcode_data[ 'events_data' ][ 'events' ] ) ) {
				unset( $mptt_shortcode_data[ 'events_data' ][ 'events' ] );
			}
		}
		
		if ( empty( $mptt_shortcode_data[ 'events_data' ][ 'events' ] ) && empty( $mptt_shortcode_data[ 'events_data' ][ 'column' ] ) ) {
			return $this->get_view()->get_template_html( 'shortcodes/empty-search-events', array() );
		} else {
			return $this->get_view()->get_template_html( 'shortcodes/index-timetable', array() );
		}
	}
	
	/**
	 * Get shortcode events
	 *
	 * @param $params
	 *
	 * @return array
	 */
	public function get_shortcode_events( $params ) {
		$columns_ids = $events_categ = $events = array();
		
		$step = $params[ 'increment' ] === '1' ? 60 : ( 60 * $params[ 'increment' ] );
		
		$events_data = array( 'events' => array(), 'column' => array() );
		
		//get event by id
		if ( ! empty( $params[ 'events' ] ) && empty( $params[ 'col' ] ) && empty( $params[ 'event_categ' ] ) ) {
			$events = $this->get( 'events' )->get_events_data( array( 'column' => 'event_id', 'list' => $params[ 'events' ] ) );
		}
		
		// get event by category
		if ( ! empty( $params[ 'event_categ' ] ) ) {
			$events_categ = $this->get( 'events' )->get_events_data_by_category( $params[ 'event_categ' ] );
		}
		
		// get event by column
		if ( ! empty( $params[ 'col' ] ) && empty( $params[ 'event_categ' ] ) && empty( $params[ 'events' ] ) ) {
			$events = $this->get( 'events' )->get_events_data( array( 'column' => 'column_id', 'list' => $params[ 'col' ] ) );
		}
		
		//Columns + events
		if ( ! empty( $params[ 'events' ] ) && ! empty( $params[ 'col' ] ) && empty( $params[ 'event_categ' ] ) ) {
			$events = $this->get( 'events' )->get_events_data( array( 'column' => array( 'column_id', 'event_id' ), 'list' => array( 'column_id' => $params[ 'col' ], 'event_id' => $params[ 'events' ] ) ) );
		}
		
		//Events +  Categories
		if ( ! empty( $params[ 'events' ] ) && ! empty( $params[ 'event_categ' ] ) && empty( $params[ 'col' ] ) ) {
			$events = $this->get( 'events' )->get_events_data( array( 'column' => 'event_id', 'list' => $params[ 'events' ] ) );
		}
		
		//if all params empty
		if ( empty( $params[ 'col' ] ) && empty( $params[ 'event_categ' ] ) && empty( $params[ 'events' ] ) ) {
			$events       = $this->get( 'events' )->get_events_data( array( 'column' => 'event_id', 'all' => true ) );
			$events_categ = $this->get( 'events' )->get_events_data_by_category( '' );
		}
		// select all event option
		if ( ! empty( $params[ 'col' ] ) && ! empty( $params[ 'event_categ' ] ) && ! empty( $params[ 'events' ] ) ) {
			$events = $this->get( 'events' )->get_events_data( array( 'column' => 'event_id', 'list' => $params[ 'events' ] ) );
		}
		
		$events_data[ 'events' ] = array_merge( $events_data[ 'events' ], $events_categ, $events );
		
		//Create column array;
		if ( empty( $params[ 'col' ] ) ) {
			foreach ( $events_data[ 'events' ] as $event ) {
				$columns_ids[] = $event->column_id;
			}
			$columns_ids = array_unique( $columns_ids );
		} else {
			$columns_ids = explode( ',', $params[ 'col' ] );
		}
		
		//Sort column by menu order
		$events_data[ 'column' ] = $this->get( 'column' )->get_all_column( array( 'post__in' => $columns_ids ) );
		
		if ( ! empty( $events_data[ 'column' ] ) ) {
			foreach ( $events_data[ 'column' ] as $key => $column ) {
				$column_events = array();
				// add to column  events
				
				foreach ( $events_data[ 'events' ] as $event_key => $event ) {
					if ( $column->ID == $event->column_id ) {
						$start_index                                        = $this->get_event_index( $params[ 'increment' ], $event->event_start, $step, 'start' );
						$end_index                                          = $this->get_event_index( $params[ 'increment' ], $event->event_end, $step, 'end' );
						$event->output                                      = false;
						$event->start_index                                 = $start_index;
						$event->end_index                                   = ( $end_index < $start_index ) ? $this->get_event_index( $params[ 'increment' ], '23:59', $step, 'end' ) : $end_index;
						$column_events[ $event->id ]                        = $event;
						$events_data[ 'unique_events' ][ $event->event_id ] = $event;
					}
				}
				
				//sort by start date
				$column_events = $this->get_model( 'events' )->sort_by_param( $column_events );
				
				$events_data[ 'column_events' ][ $column->ID ] = $column_events;
			}
		} else {
			$events_data[ 'events' ] = array();
		}
		
		return $events_data;
	}
	
	/**
	 * @param $increment
	 * @param $time
	 * @param $step
	 * @param $type
	 *
	 * @return float|int
	 */
	protected function get_event_index( $increment, $time, $step, $type ) {
		if ( $type == 'start' ) {
			$index = date( 'G', strtotime( $time ) ) / $increment + floor( date( 'i', strtotime( $time ) ) / $step );
		} else {
			$index = date( 'G', strtotime( $time ) ) / $increment + ceil( date( 'i', strtotime( $time ) ) / $step );
		}
		
		return $index;
	}
	
	/**
	 * Get events Id
	 *
	 * @param array $data
	 *
	 * @return string
	 */
	public function get_event_ids( array $data = array() ) {
		$ids_list = array();
		if ( empty( $data ) ) {
			return '';
		} else {
			foreach ( $data as $event ) {
				$ids_list[] = $event->id;
			}
			
			return implode( ',', $ids_list );
		}
	}
	
	/**
	 * Integration in motopress
	 *
	 * @param $motopressCELibrary
	 */
	public function integration_motopress( $motopressCELibrary ) {
		$columns = $this->create_list_motopress( Column::get_instance()->get_all_column() );
		$events  = $this->create_list_motopress( Events::get_instance()->get_all_events() );
		
		$categories = get_terms( 'mp-event_category', 'orderby=count&hide_empty=0' );
		$categories = $this->create_list_motopress( $categories, 'term' );
		
		$attributes   = array(
			'col'               => array(
				'type'  => 'select-multiple',
				'label' => __( 'Column', 'mp-timetable' ),
				'list'  => $columns
			),
			'events'            => array(
				'type'  => 'select-multiple',
				'label' => __( 'Events', 'mp-timetable' ),
				'list'  => $events
			),
			'event_categ'       => array(
				'type'  => 'select-multiple',
				'label' => __( 'Event categories', 'mp-timetable' ),
				'list'  => $categories
			),
			'increment'         => array(
				'type'  => 'select',
				'label' => __( 'Hour measure', 'mp-timetable' ),
				'list'  => array( '1' => __( 'Hour (1h)', 'mp-timetable' ), '0.5' => __( 'Half hour (30min)', 'mp-timetable' ), '0.25' => __( 'Quarter hour (15min)', 'mp-timetable' ) )
			),
			'view'              => array(
				'type'  => 'select',
				'label' => __( 'Filter style', 'mp-timetable' ),
				'list'  => array( 'dropdown_list' => __( 'Dropdown list', 'mp-timetable' ), 'tabs' => __( 'Tabs', 'mp-timetable' ) )
			),
			'label'             => array(
				'type'    => 'text',
				'label'   => __( 'Filter label', 'mp-timetable' ),
				'default' => __( 'All Events', 'mp-timetable' )
			),
			'hide_label'        => array(
				'type'  => 'select',
				'label' => __( "Hide 'All Events' view", 'mp-timetable' ),
				'list'  => array( '0' => __( 'No', 'mp-timetable' ), '1' => __( 'Yes', 'mp-timetable' ) )
			),
			'hide_hrs'          => array(
				'type'  => 'select',
				'label' => __( 'Hide first (hours) column', 'mp-timetable' ),
				'list'  => array( '0' => __( 'No', 'mp-timetable' ), '1' => __( 'Yes', 'mp-timetable' ) )
			),
			'hide_empty_rows'   => array(
				'type'    => 'select',
				'label'   => __( 'Hide empty rows', 'mp-timetable' ),
				'list'    => array( '1' => __( 'Yes', 'mp-timetable' ), '0' => __( 'No', 'mp-timetable' ) ),
				'default' => 1
			),
			'title'             => array(
				'type'    => 'radio-buttons',
				'label'   => __( 'Title', 'mp-timetable' ),
				'default' => 1,
				'list'    => array( '1' => __( 'Yes', 'mp-timetable' ), '0' => __( 'No', 'mp-timetable' ) ),
			),
			'time'              => array(
				'type'    => 'radio-buttons',
				'label'   => __( 'Time', 'mp-timetable' ),
				'default' => 1,
				'list'    => array( '1' => __( 'Yes', 'mp-timetable' ), '0' => __( 'No', 'mp-timetable' ) ),
			),
			'sub-title'         => array(
				'type'    => 'radio-buttons',
				'label'   => __( 'Subtitle', 'mp-timetable' ),
				'default' => 1,
				'list'    => array( '1' => __( 'Yes', 'mp-timetable' ), '0' => __( 'No', 'mp-timetable' ) ),
			),
			'description'       => array(
				'type'    => 'radio-buttons',
				'label'   => __( 'Description', 'mp-timetable' ),
				'default' => 0,
				'list'    => array( '1' => __( 'Yes', 'mp-timetable' ), '0' => __( 'No', 'mp-timetable' ) ),
			),
			'user'              => array(
				'type'    => 'radio-buttons',
				'label'   => __( 'User', 'mp-timetable' ),
				'default' => 0,
				'list'    => array( '1' => __( 'Yes', 'mp-timetable' ), '0' => __( 'No', 'mp-timetable' ) ),
			),
			'disable_event_url' => array(
				'type'  => 'select',
				'label' => __( 'Disable event URL', 'mp-timetable' ),
				'list'  => array( '0' => __( 'No', 'mp-timetable' ), '1' => __( 'Yes', 'mp-timetable' ) )
			),
			'text_align'        => array(
				'type'  => 'select',
				'label' => __( 'Text align', 'mp-timetable' ),
				'list'  => array( 'center' => __( 'center', 'mp-timetable' ), 'left' => __( 'left', 'mp-timetable' ), 'right' => __( 'right', 'mp-timetable' ) )
			),
			'id'                => array(
				'type'  => 'text',
				'label' => __( 'Id', 'mp-timetable' )
			),
			'row_height'        => array(
				'type'    => 'text',
				'label'   => __( 'Row height (in px)', 'mp-timetable' ),
				'default' => 45
			),
			'font_size'         => array(
				'type'    => 'text',
				'label'   => __( 'Base Font Size', 'mp-timetable' ),
				'default' => ''
			),
			'responsive'        => array(
				'type'    => 'select',
				'label'   => __( 'Responsive', 'mp-timetable' ),
				'list'    => array( '1' => __( 'Yes', 'mp-timetable' ), '0' => __( 'No', 'mp-timetable' ) ),
				'default' => 1,
			)
		);
		$mp_timetable = new \MPCEObject( 'mp-timetable', __( 'Timetable', 'mp-timetable' ), '', $attributes );
		
		$motopressCELibrary->addObject( $mp_timetable, 'other' );
	}
	
	/**
	 * @param array $data_array
	 * @param string $type
	 *
	 * @return array
	 */
	public function create_list_motopress( $data_array = array(), $type = 'post' ) {
		$list_array = array();
		switch ( $type ) {
			case "post":
				foreach ( $data_array as $item ) {
					$list_array[ $item->ID ] = $item->post_title;
				}
				break;
			case "term":
				foreach ( $data_array as $item ) {
					$list_array[ $item->term_id ] = $item->name;
				}
				break;
			default:
				break;
		}
		
		return $list_array;
	}
}