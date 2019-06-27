<?php

namespace mp_timetable\plugin_core\classes;

use Mp_Time_Table;
use mp_timetable\classes\models\Column;
use mp_timetable\classes\models\Events;

/**
 * Class main state
 */
class Core {
	
	protected static $instance;
	
	protected $version;

	/**
	 * Current state
	 */
	private $state;
	
	/**
	 * Core constructor.
	 */
	public function __construct() {

		$this->taxonomy_names = array(
			'mp-event_category',
			'mp-event_tag'
		);
		$this->post_types     = array(
			'mp-event',
			'mp-column'
		);
	}
	
	/**
	 * Check for ajax post
	 * @return bool
	 */
	static function is_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * @return array
	 */
	public function get_post_types() {
		return $this->post_types;
	}
	
	/**
	 * @return array
	 */
	public function get_taxonomy_names() {
		return $this->taxonomy_names;
	}
	
	/**
	 *  Init current plugin
	 *
	 * @param $name
	 */
	public function init_plugin( $name ) {
		load_plugin_textdomain( 'mp-timetable', false, Mp_Time_Table::get_plugin_path() . 'languages/' );
		
		// include template for function
		Core::include_all( Mp_Time_Table::get_plugin_part_path( 'templates-functions' ) );
		
		// include plugin models files
		Model::get_instance()->install();
		
		// include plugin controllers files
		Controller::get_instance()->install();
		
		// include plugin Preprocessors files
		Preprocessor::install();
		
		// include plugin modules
		Module::install();
		
		// install state
		$this->install_state( $name );
		
		// init all hooks
		Hooks::get_instance()->install_hooks();
		Hooks::get_instance()->register_template_action();
	}
	
	/**
	 * Include all files from folder
	 *
	 * @param string $folder
	 * @param boolean $inFolder
	 */
	static function include_all( $folder, $inFolder = true ) {
		if ( file_exists( $folder ) ) {
			$includeArr = scandir( $folder );
			foreach ( $includeArr as $include ) {
				if ( ! is_dir( $folder . "/" . $include ) ) {
					include_once( $folder . "/" . $include );
				} else {
					if ( $include != "." && $include != ".." && $inFolder ) {
						self::include_all( $folder . "/" . $include );
					}
				}
			}
		}
	}
	
	/**
	 * Install current state
	 *
	 * @param $name
	 */
	public function install_state( $name ) {
		// include plugin state
		Core::get_instance()->set_state( new State_Factory( $name ) );
	}
	
	/**
	 * @return Core
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * Include pseudo template
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function modify_single_template( $template ) {
		
		global $post;
		
		if ( ! empty( $post ) && in_array( $post->post_type, $this->post_types ) ) {
			add_action( 'loop_start', array( $this, 'setup_pseudo_template' ) );
		}
		
		return $template;
	}
	
	/**
	 * Setup pseudo template
	 *
	 * @param object $query
	 */
	public function setup_pseudo_template( $query ) {
		
		global $post;
		
		if ( $query->is_main_query() ) {
			if ( ! empty( $post ) && in_array( $post->post_type, $this->post_types ) ) {
				add_filter( 'the_content', array( $this, 'append_post_meta' ) );
			}
			remove_action( 'loop_start', array( $this, 'setup_pseudo_template' ) );
		}
	}
	
	/**
	 * Append post meta
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function append_post_meta( $content ) {
		// run only once
		remove_filter( 'the_content', array( $this, 'append_post_meta' ) );
		
		global $post;
		
		ob_start();
		switch ( $post->post_type ) {
			case 'mp-event':
				Events::get_instance()->render_event_metas();
				break;
			case 'mp-column':
				Column::get_instance()->render_column_metas();
				break;
		}
		$append  = ob_get_clean();
		$content .= $append;
		
		return $content;
	}
	
	/**
	 * Get model instace
	 *
	 * @param bool|false $type
	 *
	 * @return bool|mixed
	 */
	public function get( $type = false ) {
		$state = false;
		if ( $type ) {
			$state = $this->get_model( $type );
		}
		
		return $state;
	}
	
	/**
	 * Check and return current state
	 *
	 * @param string $type
	 *
	 * @return boolean
	 */
	public function get_model( $type = null ) {
		return Core::get_instance()->get_state()->get_model( $type );
	}
	
	/**
	 * Get State
	 * @return bool
	 */
	public function get_state() {
		if ( $this->state ) {
			return $this->state;
		} else {
			return false;
		}
	}
	
	/**
	 * Set state
	 *
	 * @param  $state
	 */
	public function set_state( $state ) {
		$this->state = $state;
	}
	
	/**
	 * Get version
	 * @return mixed
	 */
	public function get_version() {
		if ( empty( $this->version ) ) {
			$this->init_plugin_version();
		}
		
		return $this->version;
	}
	
	/**
	 * Init plugin version
	 */
	public function init_plugin_version() {
		
		$filePath = Mp_Time_Table::get_plugin_path() . Mp_Time_Table::get_plugin_name() . '.php';
		
		if ( ! function_exists( 'get_plugin_data' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		
		$pluginObject  = get_plugin_data( $filePath );
		$this->version = $pluginObject[ 'Version' ];
	}
	
	/**
	 * Get controller
	 *
	 * @param $type
	 *
	 * @return mixed
	 */
	public function get_controller( $type ) {
		return Core::get_instance()->get_state()->get_controller( $type );
	}
	
	/**
	 * Get view
	 *
	 * @return View
	 */
	public function get_view() {
		return View::get_instance();
	}
	
	/**
	 * Get preprocessor
	 *
	 * @param $type
	 *
	 * @return mixed
	 */
	public function get_preprocessor( $type = null ) {
		return Core::get_instance()->get_state()->get_preprocessor( $type );
	}
	
	/**
	 * Route plugin url
	 */
	public function wp_ajax_route_url() {
		$controller = isset( $_REQUEST[ "controller" ] ) ? $_REQUEST[ "controller" ] : null;
		$action     = isset( $_REQUEST[ "mptt_action" ] ) ? $_REQUEST[ "mptt_action" ] : null;
		
		if ( ! empty( $action ) ) {
			// call controller
			Preprocessor::get_instance()->call_controller( $action, $controller );
			die();
		}
	}
	
	/**
	 * Register taxonomies
	 */
	public function register_all_taxonomies() {
		
		$permalinks = Core::get_instance()->get_permalink_structure();
		
		do_action( 'mptt_before_register_taxonomies' );
		
		$event_category_labels = array(
			'name'               => __( 'Event categories', 'mp-timetable' ),
			'singular_name'      => __( 'Event category', 'mp-timetable' ),
			'add_new'            => __( 'Add New Event category', 'mp-timetable' ),
			'add_new_item'       => __( 'Add New Event category', 'mp-timetable' ),
			'edit_item'          => __( 'Edit Event category', 'mp-timetable' ),
			'new_item'           => __( 'New Event category', 'mp-timetable' ),
			'all_items'          => __( 'All Event categories', 'mp-timetable' ),
			'view_item'          => __( 'View Event category', 'mp-timetable' ),
			'search_items'       => __( 'Search Event category', 'mp-timetable' ),
			'not_found'          => __( 'No Event categories found', 'mp-timetable' ),
			'not_found_in_trash' => __( 'No Event categories found in Trash', 'mp-timetable' ),
			'menu_name'          => __( 'Event categories', 'mp-timetable' )
		);
		
		$event_category_args = array(
			'label'                 => __( 'Event categories', 'mp-timetable' ),
			'labels'                => $event_category_labels,
			'show_in_rest' 			=> true,
			'public'                => true,
			'show_in_nav_menus'     => true,
			'show_ui'               => true,
			'show_in_menu'          => false,
			'show_tagcloud'         => true,
			'hierarchical'          => true,
			'update_count_callback' => '',
			'rewrite'               => array(
				'slug'         => $permalinks['event_category_base'], //'timetable/category'
				'with_front'   => false,
				'hierarchical' => true
			),
			'capabilities'          => array(),
			'meta_box_cb'           => null,
			'show_admin_column'     => false,
			'_builtin'              => false,
			'show_in_quick_edit'    => null,
		);

		register_taxonomy(
			'mp-event_category',
			apply_filters( 'mptt_taxonomy_objects_event_category', array( 'mp-event' ) ),
			apply_filters( 'mptt_taxonomy_args_event_category', $event_category_args)
		);
		
		$event_tag_labels = array(
			'name'               => __( 'Event tags', 'mp-timetable' ),
			'singular_name'      => __( 'Event tag', 'mp-timetable' ),
			'add_new'            => __( 'Add New Event tag', 'mp-timetable' ),
			'add_new_item'       => __( 'Add New Event tag', 'mp-timetable' ),
			'edit_item'          => __( 'Edit Event tag', 'mp-timetable' ),
			'new_item'           => __( 'New Event tag', 'mp-timetable' ),
			'all_items'          => __( 'All Event tags', 'mp-timetable' ),
			'view_item'          => __( 'View Event tag', 'mp-timetable' ),
			'search_items'       => __( 'Search Event tag', 'mp-timetable' ),
			'not_found'          => __( 'No Event tags found', 'mp-timetable' ),
			'not_found_in_trash' => __( 'No Event tags found in Trash', 'mp-timetable' ),
			'menu_name'          => __( 'Event tags', 'mp-timetable' )
		);
		
		$event_tag_args = array(
			'label'                 => __( 'Event tags', 'mp-timetable' ),
			'labels'                => $event_tag_labels,
			'public'                => true,
			'show_in_nav_menus'     => true,
			'show_ui'               => true,
			'show_in_menu'          => false,
			'show_tagcloud'         => true,
			'hierarchical'          => false,
			'update_count_callback' => '',
			'rewrite'               => array(
				'slug'         => $permalinks['event_tag_base'], //'timetable/tag'
				'with_front'   => false,
				'hierarchical' => true
			),
			'capabilities'          => array(),
			'meta_box_cb'           => null,
			'show_admin_column'     => false,
			'_builtin'              => false,
			'show_in_quick_edit'    => null,
		);
		
		register_taxonomy(
			'mp-event_tag',
			apply_filters( 'mptt_taxonomy_objects_event_tag', array( 'mp-event' ) ),
			apply_filters( 'mptt_taxonomy_args_event_tag', $event_tag_args)
		);
		
		do_action( 'mptt_after_register_taxonomies' );
	}
	
	/**
	 * Register custom post type
	 */
	public function register_all_post_type() {
		
		$permalinks = Core::get_instance()->get_permalink_structure();
		
		do_action( 'mptt_before_register_post_types' );

		register_post_type(
			'mp-event',
			apply_filters(
				'mptt_register_post_type_event',
				array(
					'labels'            => array(
						'name'               => __( 'Events', 'mp-timetable' ),
						'singular_name'      => __( 'Event', 'mp-timetable' ),
						'add_new'            => __( 'Add New Event', 'mp-timetable' ),
						'add_new_item'       => __( 'Add New Event', 'mp-timetable' ),
						'edit_item'          => __( 'Edit Event', 'mp-timetable' ),
						'new_item'           => __( 'New Event', 'mp-timetable' ),
						'all_items'          => __( 'All Events', 'mp-timetable' ),
						'view_item'          => __( 'View Event', 'mp-timetable' ),
						'search_items'       => __( 'Search Event', 'mp-timetable' ),
						'not_found'          => __( 'No Events found', 'mp-timetable' ),
						'not_found_in_trash' => __( 'No Events found in Trash', 'mp-timetable' ),
						'menu_name'          => __( 'Events', 'mp-timetable' )
					),
					'public'            => true,
					'show_in_rest'      => true,
					'show_ui'           => true,
					'show_in_menu'      => false,
					'show_in_nav_menus' => true,
					'capability_type'   => 'post',
					'menu_position'     => 21,
					'hierarchical'      => false,
					'has_archive'       => true,
					'rewrite'           => array(
						'slug'         => $permalinks['event_base'], //'timetable/event'
						'with_front'   => false,
						'hierarchical' => true
					),
					'supports'          => array( 'title', 'editor', 'comments', 'excerpt', 'author', 'thumbnail', 'page-attributes' ),
					'show_in_admin_bar' => true
				)
			)
		);

		register_post_type(
			'mp-column',
			apply_filters(
				'mptt_register_post_type_column',
				array(
					'labels'            => array(
						'name'               => __( 'Columns', 'mp-timetable' ),
						'singular_name'      => __( 'Column', 'mp-timetable' ),
						'add_new'            => __( 'Add New Column', 'mp-timetable' ),
						'add_new_item'       => __( 'Add New Column', 'mp-timetable' ),
						'edit_item'          => __( 'Edit Column', 'mp-timetable' ),
						'new_item'           => __( 'New Column', 'mp-timetable' ),
						'all_items'          => __( 'All Columns', 'mp-timetable' ),
						'view_item'          => __( 'View Column', 'mp-timetable' ),
						'search_items'       => __( 'Search Column', 'mp-timetable' ),
						'not_found'          => __( 'No Columns found', 'mp-timetable' ),
						'not_found_in_trash' => __( 'No Columns found in Trash', 'mp-timetable' ),
						'menu_name'          => __( 'Columns', 'mp-timetable' )
					),
					'public'            => true,
					'show_in_rest'      => true,
					'show_ui'           => true,
					'show_in_menu'      => false,
					'show_in_nav_menus' => true,
					'capability_type'   => 'post',
					'menu_position'     => 21,
					'hierarchical'      => false,
					'has_archive'       => true,
					'rewrite'           => array(
						'slug'         => $permalinks['column_base'], //'timetable/column'
						'with_front'   => false,
						'hierarchical' => true
					),
					'supports'          => array( 'title', 'editor', 'page-attributes' ),
					'show_in_admin_bar' => true
				)
			)
		);
		
		do_action( 'mptt_after_register_post_types' );
		
	}
	
	/**
	 * Create Plugin table if not exists
	 */
	public function create_table() {

		global $wpdb;
		
		$charset_collate = $wpdb->get_charset_collate();
		
		$table_name = Mp_Time_Table::get_datatable();
		
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `column_id` int(11) NOT NULL,
				  `event_id` int(11) NOT NULL,
				  `event_start` time NOT NULL,
				  `event_end` time NOT NULL,
				  `user_id` int(11) NOT NULL,
				  `description` text NOT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `id` (`id`)
				) $charset_collate";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
	
	/**
	 * Hook admin_enqueue_scripts
	 */
	public function admin_enqueue_scripts() {
		global $current_screen;
		$this->current_screen( $current_screen );
	}
	
	/**
	 * Load script by current screen
	 *
	 * @param \WP_Screen $current_screen
	 */
	public function current_screen( \WP_Screen $current_screen ) {
		wp_register_script( 'mptt-event-object', Mp_Time_Table::get_plugin_url( 'media/js/events/event' . $this->get_prefix() . '.js' ), array( 'jquery' ), $this->version );
		wp_localize_script(
			'mptt-event-object',
			'MPTT',
			array( 'table_class' => apply_filters( 'mptt_shortcode_static_table_class', 'mptt-shortcode-table' ) )
		);
		
		wp_enqueue_script( 'underscore' );
		wp_enqueue_style( 'mptt-admin-style', Mp_Time_Table::get_plugin_url( 'media/css/admin.css' ), array(), $this->version );
		
		wp_enqueue_script( 'mptt-functions', Mp_Time_Table::get_plugin_url( 'media/js/mptt-functions' . $this->get_prefix() . '.js' ), array(), $this->version );		
		
		if ( ! empty( $current_screen ) ) {
			switch ( $current_screen->id ) {
				case 'mp-event':
					wp_enqueue_script( 'spectrum', Mp_Time_Table::get_plugin_url( 'media/js/lib/spectrum' . $this->get_prefix() . '.js' ), array( 'jquery' ), '1.8.0' );
					wp_enqueue_script( 'mptt-event-object' );
					wp_enqueue_script( 'jquery-ui-timepicker', Mp_Time_Table::get_plugin_url( 'media/js/lib/jquery.ui.timepicker' . $this->get_prefix() . '.js' ), '0.3.3' );
					
					wp_enqueue_style( 'jquery-ui-core', Mp_Time_Table::get_plugin_url( 'media/css/jquery-ui-1.10.0.custom.min.css' ), array(), '1.10.0' );
					wp_enqueue_style( 'spectrum', Mp_Time_Table::get_plugin_url( 'media/css/spectrum.css' ), array(), '1.8.0' );
					wp_enqueue_style( 'jquery-ui-timepicker', Mp_Time_Table::get_plugin_url( 'media/css/jquery.ui.timepicker.css' ), array(), '0.3.3' );
					break;

				case 'mp-column':
					wp_enqueue_script( 'jquery-ui-datepicker' );
					wp_enqueue_script( 'mptt-event-object' );
					
					wp_enqueue_style( 'jquery-ui-core', Mp_Time_Table::get_plugin_url( 'media/css/jquery-ui-1.10.0.custom.min.css' ), array(), '1.10.0' );
					break;

				case 'customize':
				case 'widgets':
					wp_enqueue_script( 'spectrum', Mp_Time_Table::get_plugin_url( 'media/js/lib/spectrum' . $this->get_prefix() . '.js' ), array( 'jquery' ), '1.8.0' );
					wp_enqueue_script( 'mptt-event-object' );
					
					wp_enqueue_style( 'jquery-ui-core', Mp_Time_Table::get_plugin_url( 'media/css/jquery-ui-1.10.0.custom.min.css' ), array(), '1.10.0' );
					wp_enqueue_style( 'spectrum', Mp_Time_Table::get_plugin_url( 'media/css/spectrum.css' ), array(), '1.8.0' );
					break;
			}

			switch ( $current_screen->base ) {
				case 'post':
				case 'page':
					wp_enqueue_script( 'jquery-ui-tabs' );
					wp_enqueue_script( 'jBox', Mp_Time_Table::get_plugin_url( 'media/js/lib/jBox' . $this->get_prefix() . '.js' ), array( 'jquery' ), '0.2.1' );
					
					wp_enqueue_style( 'jBox', Mp_Time_Table::get_plugin_url( 'media/css/jbox/jBox.css' ), array(), '1.8.0' );
					break;

				case 'options-permalink':
					if ( apply_filters('mptt_permalinks_enabled', true) ) {
						$permalinks = new Permalinks();
					}
				break;

				default:
					break;
			}
		}
	}
	
	/**
	 *  Get prefix
	 *
	 * @return string
	 */
	public function get_prefix() {
		global $is_IE;

		$prefix = ! MP_TT_DEBUG ? '.min' : '';

		if ($is_IE){
			$prefix =  '';
		}

		return $prefix;
	}
	
	/**
	 * Hook wp_enqueue_scripts
	 */
	public function wp_enqueue_scripts() {
		if ( ! empty( $_GET[ 'motopress-ce' ] ) ) {
			$this->add_plugin_js( 'shortcode' );
		}
	}
	
	/**
	 * Add plugin js
	 *
	 * @param bool $type
	 */
	public function add_plugin_js( $type = false ) {
		wp_register_script( 'mptt-event-object', Mp_Time_Table::get_plugin_url( 'media/js/events/event' . $this->get_prefix() . '.js' ), array( 'jquery', 'mptt-functions' ), $this->version );
		wp_localize_script(
			'mptt-event-object',
			'MPTT',
			array( 'table_class' => apply_filters( 'mptt_shortcode_static_table_class', 'mptt-shortcode-table' ) )
		);
		
		switch ( $type ) {
			case 'shortcode':
			case 'widget':
				wp_enqueue_script( 'underscore' );
				wp_enqueue_script( 'mptt-functions', Mp_Time_Table::get_plugin_url( 'media/js/mptt-functions' . $this->get_prefix() . '.js' ), array( 'jquery' ), $this->version );
				wp_enqueue_script( 'mptt-event-object' );
				break;
		}
	}
	
	/**
	 * Add plugin css
	 */
	public function add_plugin_css() {
		wp_enqueue_style( 'mptt-style', Mp_Time_Table::get_plugin_url( 'media/css/style.css' ), array(), $this->version );
	}
	
	/**
	 * Fix fatal error for earlier WP versions
	 *
	 * @return bool
	 */
	public function is_embed() {
		global $wp_version;
		
		if ( ! function_exists( 'is_embed' ) ) {
			return false;
		}
		
		if ( version_compare( $wp_version, '4.4', '<' ) ) {
			if ( ! function_exists( 'is_embed' ) ) {
				return false;
			}
		}
		
		return is_embed();
	}
	
	/**
	 * Get permalink settings
	 *
	 * @return array
	 */
	public function get_permalink_structure() {

		$saved_permalinks = (array) get_option( 'mp_timetable_permalinks', array() );

		$permalinks = wp_parse_args(
			array_filter( $saved_permalinks ), array(
				'column_base'	=> 'timetable/column',
				'event_base'	=> 'timetable/event',
				'event_category_base'	=> 'timetable/category',
				'event_tag_base'		=> 'timetable/tag',
			)
		);

		if ( $saved_permalinks !== $permalinks ) {
			update_option( 'mp_timetable_permalinks', $permalinks );
		}

		return $permalinks;
	}
}