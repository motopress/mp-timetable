<?php

namespace mp_timetable\plugin_core\classes;

use mp_timetable\plugin_core\classes;
use mp_timetable\plugin_core\classes\modules\Post as Post;
use mp_timetable\plugin_core\classes\modules\Taxonomy as Taxonomy;
use Mp_Time_Table;

/**
 * Class main state
 */
class Core {

	/**
	 * Current state
	 */
	private $state;
	protected $version;

	public static $LANGS = array('en_US', 'ru_RU');
	protected static $instance;

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Core constructor.
	 */
	public function __construct() {
		$this->taxonomy_names = array(
			'mp-event_category',
		);
		$this->post_types = array(
			'mp-event', 'mp-column'
		);
	}

	/**
	 *  Init current plugin
	 *
	 * @param $name
	 */
	public function init_plugin($name) {
		ob_start('mp_timetable\plugin_core\classes\Preprocessor::fatal_error_handler');
		// run session
		if (!session_id()) {
			session_start();
		}
		// include template for function
		Core::include_all(Mp_Time_Table::get_plugin_part_path('templates-functions'));
		// include plugin models files
		Model::get_instance()->install();
		// include plugin controllers files
		Controller::get_instance()->install();
		// include plugin Preprocessors files
		Preprocessor::install();
		// include plugin modules
		Module::install();

		// install state
		$this->install_state($name);
		// init all hooks
		Hooks::get_instance()->install_hooks();
		Hooks::get_instance()->register_template_action();
	}

	/**
	 * Include template
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function include_custom_template($template) {
		global $post, $taxonomy;

		if (is_single()) {
			if (!empty($post)) {
				if (in_array($post->post_type, $this->post_types)) {
					if (basename($template) != "single-$post->post_type.php") {
						$path = Mp_Time_Table::get_plugin_part_path('templates/') . 'single-' . $post->post_type . '.php';
						if (file_exists($path)) {
							$template = $path;
						}
					}
				}
			} elseif (!empty($taxonomy)) {
				if (in_array($taxonomy, $this->taxonomy_names)) {
					if (basename($template) != "taxonomy-$taxonomy.php") {
						$path = Mp_Time_Table::get_plugin_part_path('templates/') . 'taxonomy-' . $taxonomy . '.php';
						if (is_tax($taxonomy) && file_exists($path)) {
							$template = $path;
						}
					}
				}
			}
		}
		return $template;
	}

	/**
	 * Get model instace
	 *
	 * @param bool|false $type
	 *
	 * @return bool|mixed
	 */
	public function get($type = false) {
		$state = false;
		if ($type) {
			$state = $this->get_model($type);
		}
		return $state;
	}

	/**
	 * Get plugin version
	 */
	public function init_plugin_version() {
		$filePath = Mp_Time_Table::get_plugin_path() . Mp_Time_Table::get_plugin_name() . '.php';
		if (!function_exists('get_plugin_data')) {
			include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}
		$pluginObject = get_plugin_data($filePath);
		$this->version = $pluginObject['Version'];
	}

	/**
	 * Get version
	 * @return mixed
	 */
	public function get_version() {
		if (empty($this->version)) {
			$this->init_plugin_version();
		}
		return $this->version;
	}

	/**
	 * Load language file
	 *
	 * @param bool|false $domain
	 *
	 * @return bool
	 */
	public function load_language($domain = false) {
		if (empty($domain)) {
			return false;
		}
		$locale = get_option("locate", true);
		$moFile = Mp_Time_Table::get_plugin_path() . 'languages' . "{$domain}-{$locale}.mo";
		$result = load_textdomain($domain, $moFile);
		return $result;
	}

	/**
	 * Install current state
	 *
	 * @param $name
	 */
	public function install_state($name) {
		// include plugin state
		Core::get_instance()->set_state(new State_Factory($name));
	}

	/**
	 * Route plugin url
	 */
	public function wp_ajax_route_url() {
		$controller = isset($_REQUEST["controller"]) ? $_REQUEST["controller"] : null;
		$action = isset($_REQUEST["mptt_action"]) ? $_REQUEST["mptt_action"] : null;

		if (!empty($action)) {
			// call controller
			Preprocessor::get_instance()->call_controller($action, $controller);
			die();
		}
	}

	/**
	 * Check for ajax post
	 * @return bool
	 */
	static function is_ajax() {
		if (defined('DOING_AJAX') && DOING_AJAX) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get State
	 * @return bool
	 */
	public function get_state() {
		if ($this->state) {
			return $this->state;
		} else {
			return false;
		}
	}

	/**
	 * Get controller
	 *
	 * @param $type
	 *
	 * @return mixed
	 */
	public function get_controller($type) {
		return Core::get_instance()->get_state()->get_controller($type);
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
	 * Check and return current state
	 *
	 * @param string $type
	 *
	 * @return boolean
	 */
	public function get_model($type = null) {
		return Core::get_instance()->get_state()->get_model($type);
	}

	/**
	 * Get preprocessor
	 *
	 * @param $type
	 *
	 * @return mixed
	 */
	public function get_preprocessor($type = NULL) {
		return Core::get_instance()->get_state()->get_preprocessor($type);
	}

	/**
	 * Set state
	 *
	 * @param  $state
	 */
	public function set_state($state) {
		$this->state = $state;
	}

	/**
	 * Include all files from folder
	 *
	 * @param string $folder
	 * @param boolean $inFolder
	 */
	static function include_all($folder, $inFolder = true) {
		if (file_exists($folder)) {
			$includeArr = scandir($folder);
			foreach ($includeArr as $include) {
				if (!is_dir($folder . "/" . $include)) {
					include_once($folder . "/" . $include);
				} else {
					if ($include != "." && $include != ".." && $inFolder) {
						Core::include_all($folder . "/" . $include);
					}
				}
			}
		}
	}

	/**
	 * Register taxonomies
	 */
	public function register_all_taxonomies() {

		$labels = array(
			'name' => __('Event categories', 'mp-timetable'),
			'singular_name' => __('Event category', 'mp-timetable'),
			'add_new' => __('Add New Event category', 'mp-timetable'),
			'add_new_item' => __('Add New Event category', 'mp-timetable'),
			'edit_item' => __('Edit Event category', 'mp-timetable'),
			'new_item' => __('New Event category', 'mp-timetable'),
			'all_items' => __('All Event categories', 'mp-timetable'),
			'view_item' => __('View Event category', 'mp-timetable'),
			'search_items' => __('Search Event category', 'mp-timetable'),
			'not_found' => __('No Event categories found', 'mp-timetable'),
			'not_found_in_trash' => __('No Event categories found in Trash', 'mp-timetable'),
			'parent_item_colon' => 'media',
			'menu_name' => __('Event categories', 'mp-timetable')
		);

		$args = array(
			'label' => __('Event categories', 'mp-timetable'),
			'labels' => $labels,
			'public' => true,
			'show_in_nav_menus' => true,
			"show_ui" => true,
			'show_in_menu' => false,
			'show_tagcloud' => true,
			'hierarchical' => true,
			'update_count_callback' => '',
			'rewrite' => array(
				'slug' => 'timetable/category',
				'with_front' => true,
				'hierarchical' => true
			),
			'capabilities' => array(),
			'meta_box_cb' => null,
			'show_admin_column' => false,
			'_builtin' => false,
			'show_in_quick_edit' => null,
		);
		register_taxonomy('mp-event_category', array("mp-event"), $args);

		$labels = array(
			'name' => __('Event tags', 'mp-timetable'),
			'singular_name' => __('Event tag', 'mp-timetable'),
			'add_new' => __('Add New Event tag', 'mp-timetable'),
			'add_new_item' => __('Add New Event tag', 'mp-timetable'),
			'edit_item' => __('Edit Event tag', 'mp-timetable'),
			'new_item' => __('New Event tag', 'mp-timetable'),
			'all_items' => __('All Event tags', 'mp-timetable'),
			'view_item' => __('View Event tag', 'mp-timetable'),
			'search_items' => __('Search Event tag', 'mp-timetable'),
			'not_found' => __('No Event tags found', 'mp-timetable'),
			'not_found_in_trash' => __('No Event tags found in Trash', 'mp-timetable'),
			'parent_item_colon' => 'media',
			'menu_name' => __('Event tags', 'mp-timetable')
		);

		$args = array(
			'label' => __('Event tags', 'mp-timetable'),
			'labels' => $labels,
			'public' => true,
			'show_in_nav_menus' => true,
			"show_ui" => true,
			'show_in_menu' => false,
			'show_tagcloud' => true,
			'hierarchical' => true,
			'update_count_callback' => '',
			'rewrite' => array(
				'slug' => 'timetable/tag',
				'with_front' => true,
				'hierarchical' => true
			),
			'capabilities' => array(),
			'meta_box_cb' => null,
			'show_admin_column' => false,
			'_builtin' => false,
			'show_in_quick_edit' => null,
		);
		register_taxonomy('mp-event_tag', array("mp-event"), $args);


	}

	/**
	 * Register custom post type
	 */
	public function register_all_post_type() {

		$labels = array(
			'name' => __('Events', 'mp-timetable'),
			'singular_name' => __('Event', 'mp-timetable'),
			'add_new' => __('Add New Event', 'mp-timetable'),
			'add_new_item' => __('Add New Event', 'mp-timetable'),
			'edit_item' => __('Edit Event', 'mp-timetable'),
			'new_item' => __('New Event', 'mp-timetable'),
			'all_items' => __('All Events', 'mp-timetable'),
			'view_item' => __('View Event', 'mp-timetable'),
			'search_items' => __('Search Event', 'mp-timetable'),
			'not_found' => __('No Events found', 'mp-timetable'),
			'not_found_in_trash' => __('No Events found in Trash', 'mp-timetable'),
			'parent_item_colon' => 'media',
			'menu_name' => __('Events', 'mp-timetable')
		);

		$args = array(
			'label' => 'mp-event',
			'labels' => $labels,
			"public" => true,
			'show_ui' => true,
			'show_in_menu' => false,
			"capability_type" => "post",
			"menu_position" => 21,
			"hierarchical" => false,
			"rewrite" => array(
				'slug' => 'timetable/event',
				'with_front' => true,
				'hierarchical' => true
			),
			"supports" => array("title", "editor", "excerpt", "thumbnail", "page-attributes"),
			"show_in_admin_bar" => true
		);
		register_post_type('mp-event', $args);

		$labels = array(
			'name' => __('Columns', 'mp-timetable'),
			'singular_name' => __('Column', 'mp-timetable'),
			'add_new' => __('Add New Column', 'mp-timetable'),
			'add_new_item' => __('Add New Column', 'mp-timetable'),
			'edit_item' => __('Edit Column', 'mp-timetable'),
			'new_item' => __('New Column', 'mp-timetable'),
			'all_items' => __('All Columns', 'mp-timetable'),
			'view_item' => __('View Column', 'mp-timetable'),
			'search_items' => __('Search Column', 'mp-timetable'),
			'not_found' => __('No Columns found', 'mp-timetable'),
			'not_found_in_trash' => __('No Columns found in Trash', 'mp-timetable'),
			'parent_item_colon' => 'media',
			'menu_name' => __('Columns', 'mp-timetable')
		);

		$args = array(
			'label' => 'mp-column',
			'labels' => $labels,
			"public" => true,
			'show_ui' => true,
			'show_in_menu' => false,
			"capability_type" => "post",
			"menu_position" => 21,
			"hierarchical" => false,
			"rewrite" => array(
				'slug' => 'timetable/column',
				'with_front' => true,
				'hierarchical' => true
			),
			"supports" => array("title", "editor", "page-attributes"),
			"show_in_admin_bar" => true
		);
		register_post_type('mp-column', $args);

	}

	public function customizer_live_preview() {

	}

	/**
	 * Load script by current screen
	 *
	 * @param \WP_Screen $current_screen
	 */
	public function current_screen(\WP_Screen $current_screen) {
		wp_enqueue_style('mptt-admin-style', Mp_Time_Table::get_plugin_url('media/css/admin.css'), array(), $this->version);
		wp_enqueue_script("mptt-functions", Mp_Time_Table::get_plugin_url('media/js/mptt-functions.js'), array(), $this->version);

		if (!empty($current_screen)) {
			switch ($current_screen->id) {
				case "mp-event":
					wp_enqueue_script("spectrum", Mp_Time_Table::get_plugin_url('media/js/lib/spectrum.js'), array('jquery'), '1.8.0');
					wp_enqueue_script("mptt-event-object", Mp_Time_Table::get_plugin_url('media/js/events/event.js'), array('jquery'), $this->version);
					wp_enqueue_script("jquery-ui-timepicker", Mp_Time_Table::get_plugin_url('media/js/lib/jquery.ui.timepicker.js'), '0.3.3');

					wp_enqueue_style("jquery-ui-core", Mp_Time_Table::get_plugin_url('media/css/jquery-ui-1.10.0.custom.min.css'), array(), '1.10.0');
					wp_enqueue_style('spectrum', Mp_Time_Table::get_plugin_url('media/css/spectrum.css'), array(), '1.8.0');
					wp_enqueue_style("jquery-ui-timepicker", Mp_Time_Table::get_plugin_url('media/css/jquery.ui.timepicker.css'), array(), '0.3.3');
					break;
				case "mp-column":
					wp_enqueue_script('jquery-ui-datepicker');
					wp_enqueue_script("mptt-event-object", Mp_Time_Table::get_plugin_url('media/js/events/event.js'), array('jquery'), $this->version);

					wp_enqueue_style("jquery-ui-core", Mp_Time_Table::get_plugin_url('media/css/jquery-ui-1.10.0.custom.min.css'), array(), '1.10.0');
					break;
				case "customize":
					wp_enqueue_script("spectrum", Mp_Time_Table::get_plugin_url('media/js/lib/spectrum.js'), array('jquery'), '1.8.0');
					wp_enqueue_script("mptt-event-object", Mp_Time_Table::get_plugin_url('media/js/events/event.js'), array('jquery'), $this->version);

					wp_enqueue_style("jquery-ui-core", Mp_Time_Table::get_plugin_url('media/css/jquery-ui-1.10.0.custom.min.css'), array(), '1.10.0');
					wp_enqueue_style('spectrum', Mp_Time_Table::get_plugin_url('media/css/spectrum.css'), array(), '1.8.0');
					break;
			}

			switch ($current_screen->base) {
				case"post":
				case"page":
					wp_enqueue_script("jBox", Mp_Time_Table::get_plugin_url('media/js/lib/jBox.min.js'), array('jquery'), '0.2.1');
					wp_enqueue_script("mptt-popup-events", Mp_Time_Table::get_plugin_url('media/js/popup/popup-events.js'), array('jquery'), $this->version);
					break;
				default:
					break;
			}
		}
	}

	/**
	 * Add plugin js
	 *
	 * @param bool $type
	 */
	public function add_plugin_js($type = false) {
		switch ($type) {
			case"shortcode":
			case"widget":
				wp_enqueue_script("mptt-functions", Mp_Time_Table::get_plugin_url('media/js/mptt-functions.js'), array("jquery"), $this->version);
				wp_enqueue_script("mptt-event-object", Mp_Time_Table::get_plugin_url('media/js/events/event.js'), array('jquery'), $this->version);
				break;
		}
	}

	/**
	 * Add plugin css
	 *
	 * @param bool $type
	 */
	public function add_plugin_css($type = false) {
		switch ($type) {
			case"shortcode":
			case"widget":
				wp_enqueue_style('mptt-style', Mp_Time_Table::get_plugin_url('media/css/style.css'), array(), $this->version);
				break;
		}
	}

	/**
	 * Hook admin_enqueue_scripts
	 */
	public function admin_enqueue_scripts() {
		global $current_screen;
		$this->current_screen($current_screen);
	}

	/**
	 * Hook wp_enqueue_scripts
	 */
	public function wp_enqueue_scripts() {
		global $post_type;
		if (in_array($post_type, $this->post_types)) {
			wp_enqueue_style('mptt-style', Mp_Time_Table::get_plugin_url('media/css/style.css'), array(), $this->version);
		}
	}
}