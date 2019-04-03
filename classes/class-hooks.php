<?php

namespace mp_timetable\plugin_core\classes;

use Mp_Time_Table;
use mp_timetable\classes\models\Import;
use mp_timetable\classes\models\Settings;
use mp_timetable\plugin_core\classes\modules\Post;
use mp_timetable\classes\blocks\Timetable_Block;

/**
 * Class Hooks
 * @package mp_timetable\plugin_core\classes
 */
class Hooks extends Core {
	
	protected static $instance;
	
	/**
	 * @return Hooks
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * Install hooks
	 */
	public function install_hooks() {
		// register custom post type and taxonomies
		add_action( 'init', array( $this, "init" ) );
		add_action( 'wp_enqueue_scripts', array( Core::get_instance(), "add_plugin_css" ) );
		add_action( 'wp_head', array( $this, "set_html_js_class" ) );
		
		add_action( 'admin_init', array( $this->get_controller( 'settings' ), 'action_save' ) );
		add_action( "admin_init", array( $this, "admin_init" ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'manage_posts_custom_column', array( $this->get( 'events' ), 'get_event_taxonomy' ) );
		add_action( 'manage_posts_custom_column', array( $this->get( 'column' ), 'get_column_columns' ) );
		add_action( 'current_screen', array( Core::get_instance(), 'current_screen' ) );
		add_action( 'pre_get_posts', array( $this->get( 'column' ), 'clientarea_default_order' ), 9 );
		//add media in frontend WP
		add_action( 'wp_enqueue_scripts', array( Core::get_instance(), "wp_enqueue_scripts" ) );
		//add media in admin WP
		add_action( 'admin_enqueue_scripts', array( Core::get_instance(), "admin_enqueue_scripts" ) );
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		// Manage event/column columns
		add_filter( 'manage_edit-mp-event_columns', array( $this->get( 'events' ), 'set_event_columns' ) );
		add_filter( 'manage_edit-mp-column_columns', array( $this->get( 'column' ), 'set_column_columns' ) );
		// post_class filter
		add_filter( 'post_class', 'mptt_post_class', 15, 3 );
		// to display events with other posts on author page
		add_filter( 'pre_get_posts', array( Post::get_instance(), 'pre_get_posts' ), 9 );
	}
	
	/**
	 * Register widgets and sidebar
	 */
	public function register_widgets() {
		$template = get_option( 'template' );
		if ( $template != 'twentyfourteen' && Settings::get_instance()->is_plugin_template_mode() ) {
			register_sidebar( array(
				'name'          => __( 'Timetable Sidebar', 'mp-timetable' ),
				'id'            => "mptt-sidebar",
				'description'   => __( 'Timetable', 'mp-timetable' ),
				'class'         => 'sidebar-container',
				'before_widget' => '<li id="%1$s" class="widget %2$s">',
				'after_widget'  => "</li>\n",
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => "</h2>\n"
			) );
		}
		register_widget( 'timetable\classes\widgets\Timetable_widget' );
	}
	
	/**
	 * Register template_action
	 */
	public function register_template_action() {
		add_action( 'mptt_sidebar', 'mptt_sidebar', 10 );
		add_filter( 'mptt_widget_settings', 'mptt_widget_settings', 10, 1 );
		add_action( 'mptt-single-mp-column-before-wrapper', 'mptt_theme_wrapper_before' );
		add_action( 'mptt-single-mp-column-after-wrapper', 'mptt_theme_wrapper_after' );
		add_action( 'mptt-single-mp-event-before-wrapper', 'mptt_theme_wrapper_before' );
		add_action( 'mptt-single-mp-event-after-wrapper', 'mptt_theme_wrapper_after' );
		
		// Event template action
		add_action( 'mptt_event_item_content', 'mptt_event_template_content_title', 10 );
		add_action( 'mptt_event_item_content', 'mptt_event_template_content_thumbnail', 20 );
		add_action( 'mptt_event_item_content', 'mptt_event_template_content_post_content', 30 );
		add_action( 'mptt_event_item_content', 'mptt_event_template_content_time_title', 40 );
		add_action( 'mptt_event_item_content', 'mptt_event_template_content_time_list', 50 );
		add_action( 'mptt_event_item_content', 'mptt_event_template_content_comments', 60 );
		
		// Column template action
		add_action( 'mptt_single_column_template_content', 'mptt_column_template_content_title', 10 );
		add_action( 'mptt_single_column_template_content', 'mptt_column_template_content_post_content', 20 );
		add_action( 'mptt_single_column_template_content', 'mptt_column_template_content_events_list', 30 );
		
		//Shortcode template action
		add_action( 'mptt_shortcode_template_before_content', 'mptt_shortcode_template_before_content', 10 );
		add_action( 'mptt_shortcode_template_content', 'mptt_shortcode_template_content_filter', 10 );
		add_action( 'mptt_shortcode_template_content', 'mptt_shortcode_template_content_static_table', 20 );
		add_action( 'mptt_shortcode_template_content', 'mptt_shortcode_template_content_responsive_table', 30 );
		add_action( 'mptt_shortcode_template_after_content', 'mptt_shortcode_template_after_content', 10 );
		
		// Widget actions
		add_action( 'mptt_widget_template_before_content', 'mptt_widget_template_before_content', 10 );
		add_action( 'mptt_widget_template_content', 'mptt_widget_template_content', 10 );
		add_action( 'mptt_widget_template_after_content', 'mptt_widget_template_after_content', 10 );
		
	}
	
	/**
	 * Init hook
	 */
	public function init() {
		
		// Init sort codes
		Shortcode::get_instance()->init();
		// Register post type
		Core::get_instance()->register_all_post_type();
		// Register taxonomy all
		Core::get_instance()->register_all_taxonomies();
		// route url
		Core::get_instance()->wp_ajax_route_url();
		
		if ( Settings::get_instance()->is_plugin_template_mode() ) {
			// plugin mode
			add_filter( 'template_include', array( View::get_instance(), 'template_loader' ), 99 );
		} else {
			//theme mode
			add_filter( 'single_template', array( Core::get_instance(), 'modify_single_template' ), 99 );
		}
		
		add_action( 'mp_library', array( Shortcode::get_instance(), 'integration_motopress' ), 20, 1 );
		
		Core::get_instance()->init_plugin_version();
		
		add_filter( 'body_class', array( $this, 'browser_body_class' ) );
		add_filter( 'the_tags', array( $this->get( 'events' ), 'the_tags' ), 10, 5 );
		add_filter( 'the_category', array( $this->get( 'events' ), 'the_category' ), 10, 3 );

		if ( function_exists('register_block_type') ) {
			new Timetable_Block();
		}
	}
	
	/**
	 * Hooks for admin panel
	 */
	public function admin_init() {
		//add buttons to mce
		add_filter( "mce_external_plugins", array( $this, "mce_external_plugins" ) );
		add_filter( 'mce_buttons', array( $this, "mce_buttons" ) );
		
		Core::get_instance()->init_plugin_version();
		
		add_action( 'before_delete_post', array( Post::get_instance(), 'before_delete_custom_post' ) );
		add_action( 'add_meta_boxes', array( Post::get_instance(), 'add_meta_boxes' ) );
		add_action( 'save_post', array( Post::get_instance(), 'save_custom_post' ), 40, 2 );
		add_action( 'wp_ajax_route_url', array( Core::get_instance(), "wp_ajax_route_url" ) );
		
		register_importer( 'mptt-importer', 'Timetable', __( 'Import Timetable events, categories, tags and images.' ), array( Import::get_instance(), 'import' ) );
	}
	
	/**
	 * Registered page in admin wp
	 */
	public function admin_menu() {

		//Timetable
		add_menu_page( __( 'Timetable', 'mp-timetable' ), __( 'Timetable', 'mp-timetable' ),
			'edit_posts', 'edit.php?post_type=mp-event', '', 'dashicons-calendar', '59.51' );
		
		//Events
		add_submenu_page( 'edit.php?post_type=mp-event', __( 'Events', 'mp-timetable' ), __( 'Events', 'mp-timetable' ),
			'edit_posts', 'edit.php?post_type=mp-event' );
		
		//Add Event
		add_submenu_page( 'edit.php?post_type=mp-event', __( 'Add Event', 'mp-timetable' ), __( 'Add Event', 'mp-timetable' ),
			'edit_posts', 'post-new.php?post_type=mp-event' );
		
		//Columns
		add_submenu_page( 'edit.php?post_type=mp-event', __( 'Columns', 'mp-timetable' ), __( 'Columns', 'mp-timetable' ),
			'edit_posts', 'edit.php?post_type=mp-column' );
		
		//Add Column
		add_submenu_page( 'edit.php?post_type=mp-event', __( 'Add Column', 'mp-timetable' ), __( 'Add Column', 'mp-timetable' ),
			'edit_posts', 'post-new.php?post_type=mp-column' );
		
		//Event Categories
		add_submenu_page( 'edit.php?post_type=mp-event', __( 'Event Categories', 'mp-timetable' ), __( 'Event Categories', 'mp-timetable' ),
			'manage_categories', 'edit-tags.php?taxonomy=mp-event_category&amp;post_type=mp-event' );
		
		//Event Tags
		add_submenu_page( 'edit.php?post_type=mp-event', __( 'Event Tags', 'mp-timetable' ), __( 'Event Tags', 'mp-timetable' ),
			'manage_categories', 'edit-tags.php?taxonomy=mp-event_tag&amp;post_type=mp-event' );
		
		//Settings
		add_submenu_page( 'edit.php?post_type=mp-event', __( 'Settings', 'mp-timetable' ), __( 'Settings', 'mp-timetable' ),
			'switch_themes', 'mptt-settings', array( $this->get_controller( 'settings' ), 'action_content' ) );
		
		//Export / Import
		add_submenu_page( 'edit.php?post_type=mp-event', __( 'Export / Import', 'mp-timetable' ), __( 'Export / Import', 'mp-timetable' ),
			'import', 'mptt-import', array( $this->get_controller( 'import' ), 'action_content' ) );
		
		//Help
		add_submenu_page( 'edit.php?post_type=mp-event', __( 'Help', 'mp-timetable' ), __( 'Help', 'mp-timetable' ),
			'read', 'mptt-help', array( $this->get_controller( 'help' ), 'action_content' ) );
	}
	
	/**
	 * Connect js for MCE editor
	 *
	 * @param $plugin_array
	 *
	 * @return mixed
	 */
	public function mce_external_plugins( $plugin_array ) {
		$path                           = Mp_Time_Table::get_plugin_url( 'media/js/mce-timeTable-buttons' . $this->get_prefix() . '.js' );
		$plugin_array[ 'mp_timetable' ] = $path;
		
		return $plugin_array;
	}
	
	/**
	 * Add button in MCE editor
	 *
	 * @param $buttons
	 *
	 * @return mixed
	 */
	public function mce_buttons( $buttons ) {
		array_push( $buttons, 'addTimeTableButton' );
		
		return $buttons;
	}
	
	/**
	 * Browser body class
	 *
	 * @param $classes
	 *
	 * @return array
	 */
	public function browser_body_class( $classes ) {
		global $is_IE;

		if ( $is_IE ) {
			$classes[] = 'mprm_ie_browser';
		}

		return $classes;
	}

	/**
	 * Set js
	 */
	public function set_html_js_class() {
		echo View::get_instance()->get_template_html('events/no-script');
	}
}
