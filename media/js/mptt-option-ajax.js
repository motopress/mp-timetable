;( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		let status;

		$( document ).on( 'click', '.motopress-offer-secondary .plugin-item .action-button .button', function( e ) {

			e.preventDefault();

			const currentStatus = $( this ).closest( '.actions' ).find( '.status-label' ),
			      plugin        = $( this ).data( 'path' );

			if ( currentStatus.hasClass( 'inactive' ) ) {
				status = 'activate';
			} else if ( currentStatus.hasClass( 'not-installed' ) ) {
				status = 'install';
			} else {
				return;
			}

			$.post( {
				url: MPTT.ajax_url,
				data: {
					nonce:  MPTT.nonce,
					action: 'install_plugin_ajax',
					status: status,
					plugin: plugin
				},
				beforeSend: () => {
					$( this ).html( `${ MPTT.status_loading }` );
				},
				success: res => {
					if ( res.success ) {
						if ( ! res.data.is_activated ) {
							currentStatus
								.removeClass( 'not-installed' )
								.addClass( 'inactive' )
								.html( `${ MPTT.status_inactive }` );

							$( this )
								.removeClass( 'button button-primary' )
								.addClass( 'button button-secondary' )
								.html( `${ MPTT.status_activate }` );
						} else {
							currentStatus
								.removeClass( 'inactive' )
								.addClass( 'active' )
								.html( `${ MPTT.status_active }` );

							$( this )
								.addClass( 'disabled' )
								.html( `${ MPTT.status_activated }` );
						}
					} else {
						if ( $( '.wp-heading-inline' )[ 0 ] ) {
							$( '.wp-heading-inline' ).after( '<div class="error notice is-dismissible"><p>' + res.data + '</p><button id="my-dismiss-admin-message" class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>' );
						}

						$( this ).html( `${ MPTT.status_download }` );
					}
				}
			} ).fail( function( xhr ) {
				console.log( xhr.responseText );
			} );
		} );
	} );
} )( window.jQuery );