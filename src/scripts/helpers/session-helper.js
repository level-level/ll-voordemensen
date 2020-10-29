export default class SessionHelper {
	constructor() {
		this.$ = jQuery;
	}

	getSessionId() {
		const cookies = document.cookie.split( '; ' );
		const fullSessionIdCookie = cookies.find( row => row.startsWith( 'PHPSESSID' ) );
		if ( ! fullSessionIdCookie ) {
			return null;
		}

		const sessionIdCookieParts = fullSessionIdCookie.split( '=', 2 )[ 1 ];
		if ( sessionIdCookieParts.length < 2 && sessionIdCookieParts[ 1 ].length > 0 ) {
			return null;
		}
		return sessionIdCookieParts[ 1 ];
	}
}
