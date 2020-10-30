import SessionHelper from '../helpers/session-helper';

export default class APIHelper {
	constructor() {
		this.$ = jQuery;
		this.sessionHelper = new SessionHelper();
	}

	getCart() {
		console.log( this.getCartUrl() );
		return this.$.ajax( {
			method: "GET",
			url: this.getCartUrl(),
			dataType: 'json',
			timeout: 5000,
		} );
	}

	generateUrl( urlPath ) {
		if ( ! ll_vdm.api.client_name ) {
			return null;
		}

		return ll_vdm.api.base_url + encodeURIComponent( ll_vdm.api.client_name ) + '/' + urlPath.replace( /^\/+|\/+$/g, '' );
	}

	getCartUrl() {
		let urlPath = '/cart/';
		const sessionId = this.sessionHelper.getSessionId();
		if ( sessionId ) {
			urlPath += sessionId;
		}
		return this.generateUrl( urlPath );
	}
}
