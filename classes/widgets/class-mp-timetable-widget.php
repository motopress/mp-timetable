<?php

namespace timetable\classes\widgets;

use mp_timetable\classes\models\Column;
use mp_timetable\classes\models\Events;
use mp_timetable\classes\models\Settings;
use mp_timetable\plugin_core\classes\Core;
use mp_timetable\plugin_core\classes\View;

class Timetable_widget extends \WP_Widget {
	
	/**
	 * Widget constructor.
	 */
	public function __construct() {
		
		$classname = Settings::get_instance()->is_plugin_template_mode() ? 'mptt-container' : 'widget_recent_entries';
		
		$widget_ops = array(
			'classname'   => $classname,
			'description' => __( 'Display upcoming events.', 'mp-timetable' )
		);
		parent::__construct( 'mp-timetable', __( 'Timetable Events', 'mp-timetable' ), $widget_ops );
		add_action( 'save_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( &$this, 'flush_widget_cache' ) );
	}
	
	public function form( $instance ) {
		$instance = shortcode_atts( array(
			'title'                   => '',
			'limit'                   => '3',
			'view_settings'           => '',
			'mp_categories'           => '',
			'next_days'               => '1',
			'time_settings'           => '',
			'custom_url'              => '',
			'disable_url'             => '',
			'background_color'        => '',
			'hover_background_color'  => '',
			'text_color'              => '',
			'hover_text_color'        => '',
			'item_border_color'       => '',
			'hover_item_border_color' => '',
		), $instance );
		
		$data[ 'columns' ]    = Column::get_instance()->get_all_column();
		$data[ 'events' ]     = Events::get_instance()->get_all_events();
		$data[ 'categories' ] = get_terms( 'mp-event_category', 'orderby=count&hide_empty=0' );;
		$data[ 'localtime' ] = date( get_option( 'time_format' ), current_time( 'timestamp', 0 ) );
		$data[ 'utc_time' ]  = date( get_option( 'time_format' ), current_time( 'timestamp', 1 ) );
		View::get_instance()->render_html( 'widgets/gallery-list', array( 'widget_object' => $this, 'data' => $data, 'instance' => $instance ), true );
	}
	
	/**
	 * Update widget
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		
		$instance                              = $old_instance;
		$instance[ 'title' ]                   = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'limit' ]                   = strip_tags( $new_instance[ 'limit' ] );
		$instance[ 'mp_categories' ]           = empty( $new_instance[ 'mp_categories' ] ) ? "" : $new_instance[ 'mp_categories' ];
		$instance[ 'view_settings' ]           = strip_tags( $new_instance[ 'view_settings' ] );
		$instance[ 'next_days' ]               = strip_tags( $new_instance[ 'next_days' ] );
		$instance[ 'time_settings' ]           = empty( $new_instance[ 'time_settings' ] ) ? "" : strip_tags( $new_instance[ 'time_settings' ] );
		$instance[ 'custom_url' ]              = strip_tags( $new_instance[ 'custom_url' ] );
		$instance[ 'disable_url' ]             = strip_tags( $new_instance[ 'disable_url' ] );
		$instance[ 'background_color' ]        = strip_tags( $new_instance[ 'background_color' ] );
		$instance[ 'hover_background_color' ]  = strip_tags( $new_instance[ 'hover_background_color' ] );
		$instance[ 'text_color' ]              = strip_tags( $new_instance[ 'text_color' ] );
		$instance[ 'hover_text_color' ]        = strip_tags( $new_instance[ 'hover_text_color' ] );
		$instance[ 'item_border_color' ]       = strip_tags( $new_instance[ 'item_border_color' ] );
		$instance[ 'hover_item_border_color' ] = strip_tags( $new_instance[ 'hover_item_border_color' ] );
		
		return $instance;
	}
	
	/**
	 * Flush widget cache.
	 *
	 * @since Twenty Eleven 1.0
	 */
	function flush_widget_cache() {
		wp_cache_delete( 'mp-timetable', 'widget' );
	}
	
	/**
	 * Display widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$cache = wp_cache_get( 'mp-timetable', 'widget' );
		
		if ( ! is_array( $cache ) ) {
			$cache = array();
		}
		
		if ( ! isset( $args[ 'widget_id' ] ) ) {
			$args[ 'widget_id' ] = null;
		}
		
		if ( isset( $cache[ $args[ 'widget_id' ] ] ) ) {
			echo $cache[ $args[ 'widget_id' ] ];
			
			return;
		}
		
		ob_start();
		$data[ 'args' ]     = $args;
		$data[ 'instance' ] = mptt_widget_settings( $instance );
		$data[ 'events' ]   = Events::get_instance()->get_widget_events( $data[ 'instance' ] );
		if ( Settings::get_instance()->is_plugin_template_mode() ) {
			Core::get_instance()->add_plugin_js( 'widget' );
			View::get_instance()->get_template( "widgets/widget-view", $data );
		} else {
			View::get_instance()->get_template( "theme/widget-upcoming-view", $data );
		}
		
		$cache[ $args[ 'widget_id' ] ] = ob_get_flush();
		wp_cache_set( 'mp-timetable', $cache, 'widget' );
	}
}

