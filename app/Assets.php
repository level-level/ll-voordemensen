<?php

namespace LevelLevel\VoorDeMensen;

use LevelLevel\VoorDeMensen\Admin\Settings\Display\Fields\TicketSalesScreenType as TicketSalesScreenTypeSetting;
use LevelLevel\VoorDeMensen\Admin\Settings\General\Fields\ClientName as ClientNameSetting;

class Assets {

	public function register_hooks(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts(): void {
		$client_name = ( new ClientNameSetting() )->get_value();
		if ( empty( $client_name ) ) {
			return;
		}

		$display_type = ( new TicketSalesScreenTypeSetting() )->get_value();
		$src          = 'https://tickets.voordemensen.nl/' . rawurlencode( $client_name ) . '/iframes/vdm_loader.js';
		if ( $display_type === 'side' ) {
			$src = 'https://tickets.voordemensen.nl/' . rawurlencode( $client_name ) . '/iframes/vdm_sideloader.js';
		}

		wp_enqueue_script( 'll_vdm_external_script', $src, array(), LL_VDM_PLUGIN_VERSION, true );
	}
}
