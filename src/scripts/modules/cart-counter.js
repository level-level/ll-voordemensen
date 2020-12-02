import APIHelper from '../helpers/api-helper';

( function( $, app ) {

	app.cartCounter = function( element ) {

		const $element      = $( element ),
			apiHelper   = new APIHelper();

		// Initialize an instance
		function initialize() {
			addEventListeners();
			setCounterFromApi();
		}

		function addEventListeners() {
			$( window ).on( 'message', ( e ) => {
				const eventData = e.originalEvent.data;

				// If we have a basketcounter value, update it.
				if ( typeof eventData.vdm_basketcounter !== 'undefined' ) {
					setCounter( parseInt( eventData.vdm_basketcounter ) );
				}

				// If overlay is closed, update it from api
				if ( typeof eventData.vdm_close !== 'undefined' && eventData.vdm_close ) {
					setCounterFromApi();
				}
			} );
		}

		function setCounterFromApi() {
			apiHelper.getCart()
				.done( ( data ) => {
					setCounter( Math.max( data.length - 1, 0 ) );
				} );
		}

		function setCounter( count ) {
			$element.text( count );
		}

		initialize();
	};

}( jQuery, window.ll_vdm = window.ll_vdm || {} ) );
