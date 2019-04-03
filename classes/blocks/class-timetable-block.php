<?php

namespace mp_timetable\classes\blocks;

use Mp_Time_Table;
use mp_timetable\plugin_core\classes\Core;
use mp_timetable\plugin_core\classes\Shortcode;

class Timetable_Block {
	
	public function __construct() {

		wp_register_script(
			'mptt-blocks-js',
			Mp_Time_Table::get_plugin_url( '/media/js/blocks/dist/index.js' ),
			array( 'wp-i18n', 'wp-editor', 'wp-element', 'wp-blocks', 'wp-components', 'wp-api', 'wp-api-fetch', 'mptt-functions', 'mptt-event-object'),
			Core::get_instance()->get_version()
		);

		wp_register_style(
			'mptt-blocks',
			Mp_Time_Table::get_plugin_url( '/media/css/style.css' ),
			array(),
			Core::get_instance()->get_version()
		);

		register_block_type(
			'mp-timetable/timetable',
			array(
				'attributes' => array(
					'align' => array(
						'type' => 'string',
					),
					'col' => array(
						'type' => 'array',
						'items'   => [
							'type' => 'integer',
						],
					),
					'events' => array(
						'type' => 'array',
						'items'   => [
							'type' => 'integer',
						],
					),
					'event_categ' => array(
						'type' => 'array',
						'items'   => [
							'type' => 'integer',
						],
					),
					'increment' => array(
						'type' => 'string',
					),
					'view' => array(
						'type' => 'string',
					),
					'label' => array(
						'type' => 'string',
					),
					'hide_label' => array(
						'type' => 'string',
					),
					'hide_hrs' => array(
						'type' => 'string',
					),
					'hide_empty_rows' => array(
						'type' => 'string',
					),
					'title' => array(
						'type' => 'string',
						'default' => '1',
					),
					'time' => array(
						'type' => 'string',
						'default' => '1',
					),
					'sub_title' => array(
						'type' => 'string',
					),
					'description' => array(
						'type' => 'string',
					),
					'user' => array(
						'type' => 'string',
					),
					'disable_event_url' => array(
						'type' => 'string',
					),
					'text_align' => array(
						'type' => 'string',
					),
					'id' => array(
						'type' => 'string',
					),
					'row_height' => array(
						'type' => 'string',
					),
					'font_size' => array(
						'type' => 'string',
					),            
					'responsive' => array(
						'type' => 'string',
					),            
					'text_align_vertical' => array(
						'type' => 'string',
					),
					'group' => array(
						'type' => 'string',
					),
					'custom_class' => array(
						'type' => 'string',
					),            
				),
				'render_callback' => [ $this, 'render_timetable' ],
				'style'           => 'mptt-blocks-css',
				'editor_style'    => 'mptt-blocks-css',
				'script'          => 'mptt-blocks-js',
				'editor_script'   => 'mptt-blocks-js',
			)
		);

	}

	private function array_key_last($array) {
		if (!is_array($array) || empty($array)) {
			return NULL;
		}
		
		return array_keys($array)[count($array)-1];
	}

	private function show_shortcode($attributes) {
		foreach ($attributes as $key => $value) {
			// [] -> '1,2,3'
			if ( is_array($value) ) {
				$attributes[$key] = implode( ',', $value );
			}
			// 'sub_title' -> 'sub-title'
			if ($key == 'sub_title') {
				$attributes['sub-title'] = $attributes[$key];
				unset( $attributes[$key] );
			}
		}

		echo Shortcode::get_instance()->show_shortcode($attributes);
		echo "<pre>";
			var_dump($attributes);
		echo "</pre>";
	}

	public function render_timetable( $attributes ) {

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$block_name = 'wp-block-timetable';

		$class = $block_name;
		if ( isset( $attributes['align'] ) ) {
			$class .= ' align' . $attributes['align'];
		}

		ob_start();
		?><div class="<?php echo esc_attr( $class ); ?>"><?php

			$this->show_shortcode($attributes);

		?></div><?

		$result = ob_get_clean();
		return $result;
	}

}