import SessionHelper from '../helpers/session-helper';

( function( $, app ) {

	app.eventBuyButton = function( element ) {

		const $element      = $( element ),
			eventId         = $element.data( 'vdm-event-id' ),
			sessionHelper   = new SessionHelper();

		// Initialize an instance
		function initialize() {
			addEventListeners();
		}

		function addEventListeners() {
			$element.on( 'click', () => {
				if ( typeof window.vdm_order === 'undefined' ) {
					return;
				}
				window.vdm_order( eventId, sessionHelper.getId() );
				focusCloseButton();
			} );

			$( window ).on( 'message', ( e ) => {
				const eventData = e.originalEvent.data;
				if ( ( typeof eventData.close_overlay !== 'undefined' && eventData.close_overlay ) || ( typeof eventData.vdm_closeoverlay !== 'undefined' && eventData.vdm_closeoverlay ) ) {
					$element.focus();
				}
			} );
		}

		function focusCloseButton() {
			if ( $( '.tingle-modal__close' ).length > 0 ) {
				$( '.tingle-modal__close' ).focus();
			}
			if ( $( '.vdmClosebutton' ).length > 0 ) {
				$( '.vdmClosebutton' ).focus();
			}
		}

		initialize();
	};

}( jQuery, window.ll_vdm = window.ll_vdm || {} ) );
