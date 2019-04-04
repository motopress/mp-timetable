<?php

namespace mp_timetable\classes\blocks;

use Mp_Time_Table;
use mp_timetable\plugin_core\classes\Core;
use mp_timetable\plugin_core\classes\Shortcode;

class Timetable_Block {
	
	public function test_locale_data( $domain ) {
		$translations = get_translations_for_domain( $domain );

		$locale = array(
			'' => array(
				'domain' => $domain,
				'lang'   => is_admin() ? get_user_locale() : get_locale(),
			),
		);

		if ( ! empty( $translations->headers['Plural-Forms'] ) ) {
			$locale['']['plural_forms'] = $translations->headers['Plural-Forms'];
		}

		foreach ( $translations->entries as $msgid => $entry ) {
			$locale[ $msgid ] = $entry->translations;
		}

		return $locale;
	}

	public function test_load_locale_data() {
		$locale_data = $this->test_locale_data( 'mp-timetable' );
		wp_add_inline_script(
			'wp-i18n',
			'wp.i18n.setLocaleData( ' . json_encode( $locale_data ) . ' );'
		);
	}

	public function __construct() {

		// $this->test_locale_data( 'mp-timetable' );
// 'wp-i18n'
		wp_register_script(
			'mptt-blocks-js',
			Mp_Time_Table::get_plugin_url( '/media/js/blocks/dist/index.js' ),
			array( 'wp-i18n', 'wp-editor', 'wp-element', 'wp-blocks', 'wp-components', 'wp-api', 'wp-api-fetch', 'mptt-functions', 'mptt-event-object'),
			Core::get_instance()->get_version()
		);

	/* 	wp_set_script_translations( 'mptt-blocks-js', 'mp-timetable', plugin_dir_path( __FILE__ ) . 'languages' );
		wp_set_script_translations( 'mptt-blocks-js', 'mp-timetable' ); */

		// wp_set_script_translations( 'mptt-blocks-js', 'mp-timetable' );

		// $this->test_load_locale_data();


		// wp_set_script_translations( 'mptt-blocks-js', 'mp-timetable', plugin_dir_path( __FILE__ ) . 'languages' );
		// wp_set_script_translations( 'mptt-blocks-js', 'mp-timetable' );
		// wp_set_script_translations( 'mptt-blocks-js', 'mp-timetable', Mp_Time_Table::get_plugin_path() . 'languages/' );

	/* 	wp_localize_script(
			'mptt-blocks-js',
			'MPTT',
			[
				'localeData' => $this->test_locale_data( 'mp-timetable' ),
			]
		); */


	/* 	wp_localize_script(
			"{$this->prefix}-blocks-editor-js",
			'Getwid',			
			apply_filters(
				'getwid/editor_blocks_js/localize_data',
				[
					'localeData' => $this->test_locale_data( 'getwid' ),
					'settings' => [
						'google_api_key' => get_option('getwid_google_api_key', ''),
						'instagram_token' => get_option('getwid_instagram_token', ''),
						'assets_path' => getwid_get_plugin_url('/assets'),
						'image_sizes' => $this->getwid_get_image_sizes(),
						'excerpt_length' => apply_filters( 'excerpt_length', 55 ),
					],
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'options_writing_url' => admin_url( 'options-writing.php' ),
					'nonces' => array(
						'google_api_key' => wp_create_nonce( 'getwid_google_api_key' ),
					)
				]
			)
		); */

		// wp_set_script_translations( 'mptt-blocks-js', 'mp-timetable', Mp_Time_Table::get_plugin_path() . 'languages/' );
		// load_plugin_textdomain( 'mp-timetable', false, Mp_Time_Table::get_plugin_path() . 'languages/' );

		wp_register_style(
			'mptt-blocks-css',
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
/* 		echo "<pre>";
			var_dump($attributes);
		echo "</pre>"; */
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