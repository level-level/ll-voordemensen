import Cookies from 'js-cookie';

export default class SessionHelper {
	constructor() {
		this.$ = jQuery;
	}

	getId() {
		const sessionId = Cookies.get( 'll_vdm_session_id' );

		if ( sessionId ) {
			return sessionId;
		}
		return this.generateId();
	}

	generateId( length = 12 ) {
		let result           = '';
		const characters       = 'abcdefghijklmnopqrstuvwxyz0123456789';
		const charactersLength = characters.length;
		for ( let i = 0; i < length; i++ ) {
			result += characters.charAt( Math.floor( Math.random() * charactersLength ) );
		}

		Cookies.set( 'll_vdm_session_id', result );
		return result;
	}
}
