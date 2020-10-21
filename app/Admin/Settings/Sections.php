<?php

namespace LevelLevel\VoorDeMensen\Admin\Settings;

class Sections {

	public function register_hooks(): void {
		add_action( 'admin_init', array( $this, 'register_sections' ) );
	}

	public function register_sections() {
		add_settings_section( 'll_vdm_options_api', __( 'API Settings', 'll-vdm' ), array( $this, 'render_description' ), 'll_vdm_options' );
		add_settings_section( 'll_vdm_options_display', __( 'Display Settings', 'll-vdm' ), array( $this, 'render_description' ), 'll_vdm_options' );
	}

	/**
	 * Render a settings section description based on the section ID
	 */
	public function render_description( array $args ) {
		$text = '';
		$id   = $args['id'] ?? null;
		switch ( $id ) {
			case 'll_vdm_options_api':
				$text = esc_html__( 'Here you can set the options to connect to the VoordeMensen API.', 'll-vdm' );
				break;
			case 'll_vdm_options_display':
				$text = esc_html__( 'Here you can change how the plugin displays information.', 'll-vdm' );
				break;
		}

		if ( empty( $text ) ) {
			return;
		}
		echo wp_kses_post( '<p>' . $text . '</p>' );
	}
}
