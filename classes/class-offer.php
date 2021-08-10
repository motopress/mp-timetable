<?php

namespace mp_timetable\plugin_core\classes;

class Plugins_Offer {

	public function __construct() { }

	public static function activatePluginAjax() {

        check_ajax_referer( 'mptt-install-plugins', 'nonce' );

        $error = esc_html__( 'Could not activate the plugin.', 'mp-timetable' );

        if ( ! current_user_can( 'activate_plugins' ) ) {

            wp_send_json_error( $error );
        }

        if ( isset( $_POST[ 'plugin' ] ) ) {

            $activate = activate_plugins( $_POST[ 'plugin' ] );

            if ( ! is_wp_error( $activate ) ) {
    		    wp_send_json_success(
                    [
                        'is_activated' => true
                    ]
                );
            }
        }

	    wp_send_json_error( $error );
	}

	public static function installPluginAjax() {

        check_ajax_referer( 'mptt-install-plugins', 'nonce' );

        $slug = wp_unslash( strtok( $_POST[ 'plugin' ], '/' ) );

        if ( empty( $_POST[ 'plugin' ] ) ) {
            wp_send_json_error( esc_html__( 'Could not install the plugin.', 'mp-timetable' ) );
        }

        if ( ! current_user_can( 'install_plugins' ) ) {
            wp_send_json_error( esc_html__( 'Sorry, you are not allowed to install plugins on this site.', 'mp-timetable' ) );
        }

        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

        $api = plugins_api(
            'plugin_information',
            array(
                'slug'   => sanitize_key( $slug ),
                'fields' => array(
                    'sections' => false,
                ),
            )
        );

        if ( is_wp_error( $api ) ) {

            wp_send_json_error( $api->get_error_message() );
        }

        $skin     = new \WP_Ajax_Upgrader_Skin();
        $upgrader = new \Plugin_Upgrader( $skin );
        $result   = $upgrader->install( $api->download_link );

        wp_cache_flush();

        if ( is_wp_error( $result ) ) {

            wp_send_json_error( $result->get_error_message() );
        } elseif ( is_wp_error( $skin->result ) ) {

            wp_send_json_error( $skin->result->get_error_message() );
        } elseif ( $skin->get_errors()->has_errors() ) {

            wp_send_json_error( $skin->get_error_messages() );
        } elseif ( is_null( $result ) ) {

            global $wp_filesystem;

            $error = esc_html__( 'Unable to connect to the filesystem. Please confirm your credentials.' );

            if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->has_errors() ) {
                $error = esc_html__( $wp_filesystem->errors->get_error_message() );
            }

            wp_send_json_error( $error );
        }

        $install_status = install_plugin_install_status( $api );

        if ( is_plugin_inactive( $install_status[ 'file' ] ) ) {
             wp_send_json_success(
                [
                    'is_activated' => false
                ]
            );
        }

        wp_send_json_error( esc_html__( 'Could not install the plugin.', 'mp-timetable' ) );
	}

    private function getPluginLists() {

        $plugins  = array(
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
            )
        );

        return $plugins;
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

	// check status
	private function getPluginData( $plugin ) {

		if ( array_key_exists( $plugin['path'], get_plugins() ) ) {
			
			if ( is_plugin_active( $plugin['path'] ) ) {
				$plugin['status_text'] = esc_html__( 'Active', 'mp-timetable' );
				$plugin['status_class'] = 'active';
				$plugin['action_class'] = 'button button-secondary disabled';
				$plugin['action_text'] = esc_html__( 'Activated', 'mp-timetable' );
			} else {
				$plugin['status_text'] = esc_html__( 'Inactive', 'mp-timetable' );
				$plugin['status_class'] = 'inactive';
				$plugin['action_class'] = 'button button-secondary';
				$plugin['action_text'] = esc_html__( 'Activate', 'mp-timetable' );
			}
		} else {
			$plugin['status_text'] = esc_html__( 'Not Installed', 'mp-timetable' );
			$plugin['status_class'] = 'not-installed';
			$plugin['action_class'] = 'button button-primary';
			$plugin['action_text'] = esc_html__( 'Install Plugin', 'mp-timetable' );
		}

		return $plugin;
	}

	public function render() {
        ?>
            <div class="motopress-offer-secondary">

                <h2>More free plugins for you</h2>
                <?php
                    foreach ( $this->getPluginLists() as $key => $plugin ) :

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
                                    <button data-path="<?php echo esc_attr( $plugin[ 'path' ] ); ?>" class="<?php echo esc_attr( $plugin['action_class'] ); ?>">
                                        <?php echo esc_html( $plugin['action_text'] ); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php
	}
}
