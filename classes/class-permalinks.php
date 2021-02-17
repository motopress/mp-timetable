<?php

namespace mp_timetable\plugin_core\classes;

class Permalinks {

	/**
	 * Permalink settings.
	 *
	 * @var array
	 */
	private $permalinks = array();

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		$this->settings_init();
		$this->settings_save();
	}

	/**
	 * Init our settings.
	 */
	public function settings_init() {
		add_settings_section( 'mp-timetable-permalinks', __( 'Timetable Permalinks', 'mp-timetable' ), array( $this, 'settings' ), 'permalink' );

		add_settings_field(
			'timetable_column_slug',
			__( 'Column base', 'mp-timetable' ),
			array( $this, 'timetable_column_slug_input' ),
			'permalink',
			'mp-timetable-permalinks'
		);
		add_settings_field(
			'timetable_event_slug',
			__( 'Event base', 'mp-timetable' ),
			array( $this, 'timetable_event_slug_input' ),
			'permalink',
			'mp-timetable-permalinks'
		);		
		add_settings_field(
			'timetable_event_category_slug',
			__( 'Event Category base', 'mp-timetable' ),
			array( $this, 'timetable_event_category_slug_input' ),
			'permalink',
			'mp-timetable-permalinks'
		);
		add_settings_field(
			'timetable_event_tag_slug',
			__( 'Event Tag base', 'mp-timetable' ),
			array( $this, 'timetable_event_tag_slug_input' ),
			'permalink',
			'mp-timetable-permalinks'
		);

		$this->permalinks = Core::get_instance()->get_permalink_structure();
	}

	/**
	 * Show a Column slug input box.
	 */
	public function timetable_column_slug_input() {
		?>
		<input name="timetable_column_slug" type="text" class="regular-text" value="<?php echo esc_attr( $this->permalinks['column_base'] ); ?>" placeholder="timetable/column" />
		<?php
	}

	/**
	 * Show an Event slug input box.
	 */
	public function timetable_event_slug_input() {
		?>
		<input name="timetable_event_slug" type="text" class="regular-text" value="<?php echo esc_attr( $this->permalinks['event_base'] ); ?>" placeholder="timetable/event" />
		<?php
	}
	
	/**
	 * Show an Event Category slug input box.
	 */
	public function timetable_event_category_slug_input() {
		?>
		<input name="timetable_event_category_slug" type="text" class="regular-text" value="<?php echo esc_attr( $this->permalinks['event_category_base'] ); ?>" placeholder="timetable/category" />
		<?php
	}

	/**
	 * Show an Event Tag slug input box.
	 */
	public function timetable_event_tag_slug_input() {
		?>
		<input name="timetable_event_tag_slug" type="text" class="regular-text" value="<?php echo esc_attr( $this->permalinks['event_tag_base'] ); ?>" placeholder="timetable/tag" />
		<?php
	}

	/**
	 * Show the settings.
	 */
	public function settings() {
		wp_nonce_field( 'timetable-permalinks', 'timetable-permalinks-nonce' );
	}

	/**
	 * Save the settings.
	 */
	public function settings_save() {
		if ( ! is_admin() ) {
			return;
		}

		// We need to save the options ourselves; settings api does not trigger save for the permalinks page.
		if ( isset( $_POST['permalink_structure'],
					$_POST['timetable-permalinks-nonce'],
					$_POST['timetable_column_slug'],
					$_POST['timetable_event_slug'],
					$_POST['timetable_event_category_slug'],
					$_POST['timetable_event_tag_slug']
				) && wp_verify_nonce( wp_unslash( $_POST['timetable-permalinks-nonce'] ), 'timetable-permalinks' )
		) { // WPCS: input var ok, sanitization ok.

			$permalinks = (array) get_option( 'mp_timetable_permalinks', array() );

			$permalinks['column_base']			= $this->sanitize_permalink( wp_unslash( $_POST['timetable_column_slug'] ) ); // WPCS: input var ok, sanitization ok.
			$permalinks['event_base']			= $this->sanitize_permalink( wp_unslash( $_POST['timetable_event_slug'] ) ); // WPCS: input var ok, sanitization ok.
			$permalinks['event_category_base']	= $this->sanitize_permalink( wp_unslash( $_POST['timetable_event_category_slug'] ) ); // WPCS: input var ok, sanitization ok.
			$permalinks['event_tag_base']		= $this->sanitize_permalink( wp_unslash( $_POST['timetable_event_tag_slug'] ) ); // WPCS: input var ok, sanitization ok.

			update_option( 'mp_timetable_permalinks', $permalinks );
		}
	}

	private function sanitize_permalink( $value ) {

		$value = esc_url_raw( trim( $value ) );
		$value = str_replace( 'http://', '', $value );
		return untrailingslashit( $value );
	}
}
