<?php

namespace mp_timetable\plugin_core\classes\modules;

use mp_timetable\plugin_core\classes\Module;
use mp_timetable\plugin_core\classes\View;

class Taxonomy extends Module {

	protected static $instance;

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * Render html for filter taxonomy link
	 *
	 * @param $post
	 * @param $tax_name
	 *
	 * @return string
	 */
	public function get_the_term_filter_list($post, $tax_name) {
		$taxonomies = wp_get_post_terms($post->ID, $tax_name);
		$taxonomies_html = "";
		$last_key = key(array_slice($taxonomies, -1, 1, TRUE));

		foreach ($taxonomies as $key => $tax) {
			$data["wp"] = $tax;
			$data["filter_link"] = admin_url('edit.php?post_type=' . $post->post_type . '&' . $tax->taxonomy . '=' . $tax->slug);
			$taxonomies_html .= View::get_instance()->render_html("taxonomies/taxonomy-link", $data, false);
			$taxonomies_html .= ($last_key != $key) ? ', ' : '';
		}
		return (!empty($taxonomies_html)) ? $taxonomies_html : "—";
	}

}
