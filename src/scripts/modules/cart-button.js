import SessionHelper from '../helpers/session-helper';

( function( $, app ) {

	app.cartButton = function( element ) {

		const $element      = $( element ),
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
				window.vdm_order( 'cart', sessionHelper.getSessionId() );
			} );
		}

		initialize();
	};

}( jQuery, window.ll_vdm = window.ll_vdm || {} ) );
