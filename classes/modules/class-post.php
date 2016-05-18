<?php

namespace mp_timetable\plugin_core\classes\modules;

use mp_timetable\plugin_core\classes\Module as Module;
use \Mp_Time_Table;

class Post extends Module {

	protected static $instance;

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function pre_get_posts($query){
		global $wpdb;

		// if it's an author query
		if($query->is_author() && ! is_admin() ){
			// put all the posts on page 1
			if( isset($query->query['author_name']) ){
				$author = get_user_by('slug', $query->query['author_name']);
				$author_id = $author? $author->ID : array();
			} else if( isset($query->query['author']) ){
				$author_id = $query->query['author'];
			} else{
				$author_id = '';
			}

			$posts_name = $wpdb->prefix . 'posts';
			$tt_name = $wpdb->prefix . 'mp_timetable_data';

			$sql = "SELECT DISTINCT p.ID "
				." FROM {$posts_name} p LEFT JOIN {$tt_name} t ON p.`ID` = t.`event_id` "
				." WHERE ("
				." ( p.post_type IN ('post', 'mp-event') AND p.`post_status` LIKE 'publish')"
				."  AND ( t.`user_id` = {$author_id} OR p.`post_author` = {$author_id} )"
				." )";
			$admin_ids =  $wpdb->get_results($sql, ARRAY_N);
			$ids = array();

			foreach($admin_ids as $key=>$val){
				$ids[] = $val[0];
			}


			$query->set('post_type', array('post', 'mp-event'));
			$query->set('author_name','');
			$query->set('author','');
			$query->set('orderby', 'post_type');
			$query->set('order','ASC');
			$query->set('post__in',$ids);
		}

		return $query;
	}

	public function get_the_archive_title($title){
		if( is_author() ) {
			$title = '';
		}
		return $title;
	}


	/**
	 * add meta _boxes
	 */
	public function add_meta_boxes() {
		add_meta_box('mp-event_data', __('Time Slots', 'mp-timetable'), array($this->get('events'), 'render_event_data'), 'mp-event', 'normal', 'high', array('post_type' => 'mp-event'));
		add_meta_box('mp_event_options', __('Settings', 'mp-timetable'), array($this->get('events'), 'render_event_options'), 'mp-event', 'normal', 'high', array('post_type' => 'mp-event'));
		add_meta_box('mp-columns', __('Column Type', 'mp-timetable'), array($this->get('column'), 'render_column_options'), 'mp-column', 'normal', 'high', array('post_type' => 'mp-column'));
	}

	/**
	 * Save custom_post
	 *
	 * @param $post_id
	 * @param $post
	 */
	public function save_custom_post($post_id, $post) {
		$request = $_REQUEST;
		if (!empty($request[Mp_Time_Table::get_plugin_name() . '_noncename'])) {
			$post_type = $request['post_type'];
			if (!wp_verify_nonce($request[Mp_Time_Table::get_plugin_name() . '_noncename'], Mp_Time_Table::get_plugin_path())) {
				return $post->ID;
			}

			// Is the user allowed to edit the post or page?
			if (!current_user_can('edit_post', $post->ID)) {
				return $post->ID;
			}
			// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return $post->ID;
			}

			//Save post by post_type
			switch ($post_type) {
				case 'mp-event':
					$this->get('events')->save_event_data(array('post' => $post,
							'event_data' => ( !empty( $request['event_data'] ) )? $request['event_data'] : null,
							'event_meta' => ( !empty( $request['event_meta'] ) )? $request['event_meta'] : null));
					break;
				case 'mp-column':
					$this->get('column')->save_column_data(array('post' => $post, 'data' => $request['column']));
					break;
				default:
					break;
			}
		}
	}

	/**
	 * Before delete custom post
	 *
	 * @param $post_id
	 * @param $post
	 */
	public function before_delete_custom_post($post_id) {
		global $post_type;
		if ( $post_type != 'mp-column' ) return;

		$this->get('column')->before_delete_column( $post_id );

	}
}
