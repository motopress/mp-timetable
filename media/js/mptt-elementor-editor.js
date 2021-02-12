'use strict';

$ = jQuery;

// Check if Frontend init.
$( window ).on( 'elementor/frontend/init', () => {
    elementorFrontend.hooks.addAction( 'frontend/element_ready/timetable.default', $scope => {
        const waitLoadTable = setInterval( () => {
            if ( $scope.find( '.mptt-shortcode-wrapper' ).length && ! $scope.find( '.mptt-shortcode-wrapper' ).hasClass('table-init') ) {
                clearInterval( waitLoadTable );
                window.mptt.tableInit();
            }
        }, 1 );
    } );
} );