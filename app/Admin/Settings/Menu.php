<?php

namespace LevelLevel\VoorDeMensen\Admin\Settings;

class Menu
{
	public function register_hooks(): void {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
	}

	public function add_menu_page() {
		add_menu_page(
			__( 'VoordeMensen settings', 'll-vdm' ),
			__( 'VoordeMensen', 'll-vdm' ),
			'manage_options',
			'll_vdm_options',
			array( $this, 'render_settings_page' ),
			'dashicons-tickets'
		);
	}

	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		require_once LL_VDM_PLUGIN_PATH . 'templates/admin/settings-page.php';
	}
}
