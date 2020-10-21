<?php

namespace LevelLevel\VoorDeMensen;

class Assets {

	public function register_hooks(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts() {
		$client_name = get_option( 'll_vdm_api_client_name', null );
		if ( empty( $client_name ) ) {
			return;
		}

		$display_type = get_option( 'll_vdm_display_ticket_sales_screen_type', 'popup' );
		$src          = 'https://tickets.voordemensen.nl/' . rawurlencode( $client_name ) . '/iframes/vdm_loader.js';
		if ( $display_type === 'side' ) {
			$src = 'https://tickets.voordemensen.nl/' . rawurlencode( $client_name ) . '/iframes/vdm_sideloader.js';
		}

		wp_enqueue_script( 'vdm_loader', $src, array(), LL_VDM_PLUGIN_VERSION, true );
	}
}
