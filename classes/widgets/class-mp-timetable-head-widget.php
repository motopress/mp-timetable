<?php

namespace timetable\classes\widgets;

use mp_timetable\classes\models\Column;
use mp_timetable\classes\models\Events;
use mp_timetable\plugin_core\classes\Core;
use mp_timetable\plugin_core\classes\View;

class Timetable_Head_widget extends \WP_Widget {


	public function __construct() {
		$widget_ops = array(
			'classname' => 'mptt-head-container',
			'description' => __('Display upcoming head events.', 'mp-timetable')
		);
		parent::__construct('mp-timetable-head', __('Timetable Head Events', 'mp-timetable'), $widget_ops);
		add_action('save_post', array(&$this, 'flush_widget_cache'));
		add_action('deleted_post', array(&$this, 'flush_widget_cache'));
		add_action('switch_theme', array(&$this, 'flush_widget_cache'));
	}

	public function form($instance) {
		$instance = shortcode_atts(array(
			'title' => '',
			'limit' => '3',
			'view_settings' => '',
			'user_id' => '',
		), $instance);

		$data['user_id'] = '';

		View::get_instance()->render_html('widgets/gallery-head-list', array('widget_object' => $this, 'data' => $data, 'instance' => $instance), true);
	}

	/**
	 * Update widget
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update($new_instance, $old_instance) {

		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['limit'] = strip_tags($new_instance['limit']);
		$instance['view_settings'] = strip_tags($new_instance['view_settings']);
		$instance['user_id'] = strip_tags($new_instance['user_id']);

		return $instance;
	}

	/**
	 * Flush widget cache.
	 *
	 * @since Twenty Eleven 1.0
	 */
	function flush_widget_cache() {
		wp_cache_delete('mp-timetable-head', 'widget');
	}

	/**
	 * Display widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget($args, $instance) {
		$cache = wp_cache_get('mp-timetable-head', 'widget');
		Core::get_instance()->add_plugin_js('widget');
		Core::get_instance()->add_plugin_css('widget');
		if (!is_array($cache)) {
			$cache = array();
		}

		if (!isset($args['widget_id'])) {
			$args['widget_id'] = null;
		}

		if (isset($cache[$args['widget_id']])) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		$data['args'] = $args;
		$data['instance'] = mptt_widget_head_settings($instance);
		$data['events'] = Events::get_instance()->get_widget_head_events($data['instance']);
		View::get_instance()->render_html("widgets/widget-head-view", $data, true);

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('mp-timetable-head', $cache, 'widget');
	}
}

