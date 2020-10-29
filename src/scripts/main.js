import './modules/example-module.js';

( function( app, $ ) {
	'use strict';

	app.instantiate = function( elem ) {
		const $this   = $( elem );
		const module  = $this.attr( 'data-ll-vdm-module' );
		if ( module === undefined ) {
			throw 'Module not defined (use data-ll-vdm-module="")';
		} else if ( module in app ) {
			new app[ module ]( elem );
			$this.attr( 'data-initialized', true );
		} else {
			throw 'Module \'' + module + '\' not found';
		}
	};

	$( '[data-ll-vdm-module]' ).each( function() {
		app.instantiate( this );
	} );

}( window.ll_vdm = window.ll_vdm || {}, jQuery ) );
