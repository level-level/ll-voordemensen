<?php

namespace LevelLevel\VoorDeMensen\Admin\Settings;

use LevelLevel\VoorDeMensen\Sync\EventsSync;

class QuickSyncEventsController {

	public function register_hooks(): void {
		add_action( 'admin_post_ll_vdm_quick_sync_events', array( $this, 'quick_sync_events' ) );
	}

	public function quick_sync_events(): void {
		$nonce_valid = check_admin_referer( 'll_vdm_quick_sync_events', 'll_vdm' );
		if ( ! $nonce_valid ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$sync = new EventsSync();
		$sync->sync_recent();

		$redirect = admin_url( 'admin.php?page=' . urlencode( Menu::NAME ) );
		$http_referer = filter_input( INPUT_POST, '_wp_http_referer' );
		if ( $http_referer ) {
			$redirect = $http_referer;
		}

		$redirect = add_query_arg( 'll_vdm_quick_sync_events', 1, $redirect );
		wp_safe_redirect( $redirect );
		exit;
	}
}
