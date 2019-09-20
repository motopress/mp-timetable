<?php

namespace mp_timetable\classes\blocks;

use Mp_Time_Table;
use mp_timetable\plugin_core\classes\Core;
use mp_timetable\plugin_core\classes\Shortcode;

class Timetable_Block {

	public function __construct() {

		// block-js
		wp_register_script(
			'mptt-blocks-js',
			Mp_Time_Table::get_plugin_url( '/media/js/blocks/dist/index.min.js' ),
			array( 'wp-i18n', 'wp-editor', 'wp-element', 'wp-blocks', 'wp-components', 'wp-api', 'wp-api-fetch', 'mptt-functions', 'mptt-event-object'),
			Core::get_instance()->get_version()
		);

		// style.css
		wp_register_style(
			'mptt-blocks',
			Mp_Time_Table::get_plugin_url( 'media/css/style.css' ),
			array(),
			Core::get_instance()->get_version()
		);
		
		// block-editor.css
		wp_register_style(
			'mptt-blocks-editor',
			Mp_Time_Table::get_plugin_url( '/media/css/block-editor.css' ),
			array('mptt-blocks'),
			Core::get_instance()->get_version()
		);

		// internationalization
		wp_set_script_translations( 'mptt-blocks-js', 'mp-timetable', Mp_Time_Table::get_plugin_path() . 'languages' );

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
						'default' => '1',
					),
					'view' => array(
						'type' => 'string',
						'default' => 'dropdown_list',
					),
					'label' => array(
						'type' => 'string',
						'default' => __( "All Events", 'mp-timetable' ),
					),
					'hide_label' => array(
						'type' => 'string',
						'default' => '0',
					),
					'hide_hrs' => array(
						'type' => 'string',
						'default' => '0',
					),
					'hide_empty_rows' => array(
						'type' => 'string',
						'default' => '1',
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
						'default' => '0',
					),
					'description' => array(
						'type' => 'string',
						'default' => '1',
					),
					'user' => array(
						'type' => 'string',
						'default' => '0',
					),
					'group' => array(
						'type' => 'string',
						'default' => '0',
					),					
					'disable_event_url' => array(
						'type' => 'string',
						'default' => '0',
					),
					'text_align' => array(
						'type' => 'string',
						'default' => 'center',
					),
					'id' => array(
						'type' => 'string',
					),
					'row_height' => array(
						'type' => 'string',
						'default' => '45',
					),
					'font_size' => array(
						'type' => 'string',
					),            
					'responsive' => array(
						'type' => 'string',
						'default' => '1',
					),            
					'text_align_vertical' => array(
						'type' => 'string',
						'default' => 'default',
					),
					'custom_class' => array(
						'type' => 'string',
					),            
				),
				'render_callback' => [ $this, 'render_timetable' ],
				'editor_style'    => 'mptt-blocks-editor',
				'editor_script'          => 'mptt-blocks-js',
			)
		);
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

		?></div><?php

		$result = ob_get_clean();
		return $result;
	}
}