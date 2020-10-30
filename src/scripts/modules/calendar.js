( function( $, app ) {

	app.calendar = function( element ) {

		const $element = $( element );

		// Initialize an instance
		function initialize() {
			addEventListeners();
		}

		function addEventListeners() {
			$element.on( 'click', () => {
				if ( typeof window.vdm_calendar === 'undefined' ) {
					return;
				}
				window.vdm_calendar();
			} );
		}

		initialize();
	};

}( jQuery, window.ll_vdm = window.ll_vdm || {} ) );
