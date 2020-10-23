<?php

namespace LevelLevel\VoorDeMensen\Admin\Settings;

class Menu {

	public const NAME = 'll_vdm_options';

	public function register_hooks(): void {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
	}

	public function add_menu_page(): void {
		add_menu_page(
			__( 'VoordeMensen settings', 'll-vdm' ),
			__( 'VoordeMensen', 'll-vdm' ),
			'manage_options',
			self::NAME,
			array( $this, 'render_settings_page' ),
			'dashicons-tickets'
		);
	}

	public function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		require_once LL_VDM_PLUGIN_PATH . 'templates/admin/settings-page.php';
	}
}
