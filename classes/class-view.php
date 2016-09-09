<?php

namespace mp_timetable\plugin_core\classes;

use \Mp_Time_Table;

/**
 * View class
 */
class View {

	private $data;
	private $template;
	protected static $instance;


	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Render template
	 *
	 * @param null $template
	 * @param null $data
	 */
	function render_template($template = null, $data = null) {
		$this->template = $template;
		if (is_array($data)) {
			extract($data);
		}
		$this->data = $data;
		include_once Mp_Time_Table::get_plugin_part_path('templates/') . 'index.php';
	}

	/**
	 * Render html
	 *
	 * @param $template
	 * @param null $data
	 * @param bool $output
	 *
	 * @return string
	 */
	public function render_html($template, $data = null, $output = true) {

		ob_start();
		$locatedTemplate = $this->locate_template('mp-timetable/' . $template . '.php', true, $data);
		$out = ob_get_clean();

		if (empty($locatedTemplate)) {
			$includeFile = Mp_Time_Table::get_plugin_part_path('templates/') . $template . '.php';
			$includeFile = apply_filters('mptt_render_html', $includeFile, $template, $data, $output);

			ob_start();
			$this->load_template($includeFile, $data);
			$out = ob_get_clean();
		}

		if ($output) {
			echo $out;
			return;
		}

		return $out;
	}


	/**
	 * Retrieve the name of the highest priority template file that exists
	 *
	 * Searches in the STYLESHEETPATH before TEMPLATEPATH and wp-includes/theme-compat
	 * so that themes which inherit from a parent theme can just overload one file
	 *
	 * @param string|array $template_names Template file(s) to search for, in order
	 * @param bool $load If true the template file will be loaded if it is found
	 * @param array $data data to locate into template
	 *
	 * @return string The template filename if one is located.
	 */
	function locate_template($template_names, $load = false, $data = null) {
		$located = '';
		foreach ((array)$template_names as $template_name) {
			if (!$template_name)
				continue;
			if (file_exists(STYLESHEETPATH . '/' . $template_name)) {
				$located = STYLESHEETPATH . '/' . $template_name;
				break;
			} elseif (file_exists(TEMPLATEPATH . '/' . $template_name)) {
				$located = TEMPLATEPATH . '/' . $template_name;
				break;
			} elseif (file_exists(ABSPATH . WPINC . '/theme-compat/' . $template_name)) {
				$located = ABSPATH . WPINC . '/theme-compat/' . $template_name;
				break;
			}
		}

		if ($load && '' != $located) {
			$this->load_template($located, $data);
		}

		return $located;
	}

	/**
	 * Include the template file
	 *
	 * @param string $template_file Path to template file.
	 * @param bool $require_once Whether to require_once or require. Default true.
	 * @param array $data data to locate into template
	 */
	function load_template($template_file, $data = null) {

		if (is_array($data)) {
			extract($data);
		}
		$this->data = $data;

		include($template_file);
	}
}
