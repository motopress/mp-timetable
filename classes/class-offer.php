<?php

class Plugins_Offer {

	private $plugins = array();

	public function __construct() {

		$this->plugins = array(
			'getwid' => array(
				'slug' => 'getwid',
				'name' => 'Getwid: 40+ Free Gutenberg Blocks',
				'path' => 'getwid/getwid.php',
				'icon' => 'https://ps.w.org/getwid/assets/icon.svg',
				'description' => 'Getwid is a collection of 40+ Gutenberg blocks that greatly extends the library of existing core WordPress blocks and 35+ unique pre-made block templates for the Block Editor.'
			),
			'stratum' => array(
				'slug' => 'stratum',
				'name' => 'Stratum: 20+ Free Elementor Widgets',
				'path' => 'stratum/stratum.php',
				'icon' => 'https://ps.w.org/stratum/assets/icon.svg',
				'description' => 'Stratum is a free collection of 20+ Elementor addons with the aim of enhancing the existing widget functionality of your favorite page builder.'
			),
			'hotel-booking' => array(
				'slug' => 'motopress-hotel-booking-lite',
				'name' => 'Hotel Booking: WordPress Booking Plugin',
				'path' => 'motopress-hotel-booking-lite/motopress-hotel-booking.php',
				'icon' => 'https://ps.w.org/motopress-hotel-booking-lite/assets/icon-128x128.png',
				'description' => 'Hotel Booking plugin by MotoPress is the ultimate WordPress property rental system with a real lodging business in mind.'
			),
			/*'timetable' => array(
				'slug' => 'mp-timetable',
				'name' => 'Timetable and Event Schedule',
				'path' => 'mp-timetable/mp-timetable.php',
				'icon' => 'https://ps.w.org/mp-timetable/assets/icon-128x128.jpg',
				'description' => 'All-around organizer plugin developed to help you create and manage online schedules for a single or multiple events.'
			),*/
			'restaurant-menu' => array(
				'slug' => 'mp-restaurant-menu',
				'name' => 'Restaurant Menu',
				'path' => 'mp-restaurant-menu/restaurant-menu.php',
				'icon' => 'https://ps.w.org/mp-restaurant-menu/assets/icon-128x128.jpg',
				'description' => 'Restaurant Menu is a full-fledged WordPress food ordering system that can be smoothly integrated with your restaurant or cafe website.'
			),
		);
	}

	private function getPluginInstallationLink( $slug ) {
	
		$action = 'install-plugin';
		return wp_nonce_url(
			add_query_arg(
				array(
					'action' => $action,
					'plugin' => $slug
				),
				admin_url( 'update.php' )
			),
			$action.'_'.$slug
		);
	}

	private function getPluginActivationLink( $path ) {
	
		$action = 'activate';
		return wp_nonce_url(
			add_query_arg(
				array(
					'action' => $action,
					'plugin' => urlencode( $path )
				),
				admin_url( 'plugins.php' )
			),
			$action.'-plugin_'.$path
		);
	}

	// check status
	private function getPluginData( $plugin ) {

		if ( array_key_exists( $plugin['path'], get_plugins() ) ) {
			
			if ( is_plugin_active( $plugin['path'] ) ) {
				$plugin['status_text'] = 'Active';
				$plugin['status_class'] = 'active';
				$plugin['action_class'] = 'button button-secondary disabled';
				$plugin['action_text'] = 'Activated';
				$plugin['action_url'] = '#';
			} else {
				$plugin['status_text'] = 'Inactive';
				$plugin['status_class'] = 'inactive';
				$plugin['action_class'] = 'button button-secondary';
				$plugin['action_text'] = 'Activate';
				$plugin['action_url'] = $this->getPluginActivationLink( $plugin['path'] );
			}
		} else {
			$plugin['status_text'] = 'Not Installed';
			$plugin['status_class'] = 'not-installed';
			$plugin['action_class'] = 'button button-primary';
			$plugin['action_text'] = 'Install Plugin';
			$plugin['action_url'] = $this->getPluginInstallationLink( $plugin['slug'] );
		}

		return $plugin;
	}

	public function render() {
?>
	<div class="motopress-offer-secondary">

		<h2>More free plugins for you</h2>
<?php
		foreach ( $this->plugins as $key => $plugin ) :

		$plugin = $this->getPluginData( $plugin );
?>
		<div class="plugin-container">
			<div class="plugin-item">
				<div class="details">
					<img src="<?php echo esc_url( $plugin['icon'] ); ?>">
					<h5 class="plugin-name"><?php echo esc_html( $plugin['name'] ); ?></h5>
					<p class="plugin-description"><?php echo esc_html( $plugin['description'] ); ?></p>
				</div>
				<div class="actions">
					<div class="status">
						<strong>Status: <span class="status-label <?php echo esc_attr( $plugin['status_class'] ); ?>">
							<?php echo esc_html( $plugin['status_text'] ); ?></span></strong>
					</div>
					<div class="action-button">
						<a href="<?php echo $plugin['action_url']; ?>" class="<?php echo esc_attr( $plugin['action_class'] ); ?>">
							<?php echo esc_html( $plugin['action_text'] ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
<?php endforeach; ?>
	</div>
<?php
	}
}
