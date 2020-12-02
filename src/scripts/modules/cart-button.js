import SessionHelper from '../helpers/session-helper';

( function( $, app ) {

	app.cartButton = function( element ) {

		const $element      = $( element ),
			sessionHelper   = new SessionHelper();
		let isOpen = false;

		// Initialize an instance
		function initialize() {
			addEventListeners();
		}

		function addEventListeners() {
			$element.on( 'click', () => {
				if ( typeof window.vdm_order === 'undefined' ) {
					return;
				}
				window.vdm_order( 'cart', sessionHelper.getId() );
				focusCloseButton();
				isOpen = true;
			} );

			$( window ).on( 'message', ( e ) => {
				const eventData = e.originalEvent.data;
				if ( isOpen && typeof eventData.vdm_close !== 'undefined' && eventData.vdm_close ) {
					$element.focus();
					isOpen = false;
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
