<?php

namespace LevelLevel\VoorDeMensen\Admin\Settings\Display;

class Section {

	public const NAME = 'll_vdm_options_display';

	public function register_hooks(): void {
		add_action( 'admin_init', array( $this, 'register_section' ) );
	}

	public function register_section(): void {
		add_settings_section( self::NAME, __( 'Display Settings', 'll-vdm' ), array( $this, 'render_description' ), 'll_vdm_options' );
	}

	/**
	 * Render a settings section description based on the section ID
	 */
	public function render_description( array $args ): void {
		$text = esc_html__( 'Here you can change how the plugin displays information.', 'll-vdm' );
		if ( empty( $text ) ) {
			return;
		}
		echo wp_kses_post( '<p>' . $text . '</p>' );
	}
}
