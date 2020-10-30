export default class SessionHelper {
	constructor() {
		this.$ = jQuery;
	}

	getName() {
		return ll_vdm.session.name;
	}

	getId() {
		const cookies = document.cookie.split( '; ' );
		const fullSessionIdCookie = cookies.find( row => row.startsWith( this.getName() ) );
		if ( ! fullSessionIdCookie ) {
			return null;
		}

		const sessionIdCookieParts = fullSessionIdCookie.split( '=', 2 );
		if ( sessionIdCookieParts.length < 2 && sessionIdCookieParts[ 1 ].length > 0 ) {
			return null;
		}
		return sessionIdCookieParts[ 1 ];
	}
}
