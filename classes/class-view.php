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
		$includeFile = Mp_Time_Table::get_plugin_part_path('templates/') . $template . '.php';
		
		$includeFile = apply_filters('mptt_render_html', $includeFile, $template, $data, $output);
		
		ob_start();
		if (is_array($data)) {
			extract($data);
		}
		$this->data = $data;
		include($includeFile);
		$out = ob_get_clean();
		if ($output) {
			echo $out;
		} else {
			return $out;
		}
	}
}
