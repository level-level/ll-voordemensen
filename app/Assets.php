<?php

namespace LevelLevel\VoorDeMensen;

use LevelLevel\VoorDeMensen\Admin\Settings\Display\Fields\TicketSalesScreenType as TicketSalesScreenTypeSetting;
use LevelLevel\VoorDeMensen\Admin\Settings\General\Fields\ClientName as ClientNameSetting;
use LevelLevel\VoorDeMensen\API\Client;

class Assets {

	public function register_hooks(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_external_vdm_script' ) );
		add_action( 'script_loader_tag', array( $this, 'defer_external_vdm_script' ), 10, 2 );
	}

	public function is_development_mode(): bool {
		return ! file_exists( LL_VDM_PLUGIN_PATH . 'dist/main.js' );
	}

	public function enqueue(): void {
		if ( $this->is_development_mode() ) {
			$this->enqueue_development( 'll_vdm_main', array( 'jquery' ), '/src/main.js', true );
		} else {
			$this->enqueue_production();
		}
		$this->localize();
	}

	public function enqueue_development( string $handle, array $deps, string $path, bool $in_footer ): void {
		$src = $this->get_development_src( $path );
		wp_enqueue_script( $handle, $src, $deps, LL_VDM_PLUGIN_VERSION, $in_footer );
	}

	public function enqueue_production(): void {
		wp_enqueue_script( 'll_vdm_main', LL_VDM_PLUGIN_URL . 'dist/main.js', array( 'jquery' ), LL_VDM_PLUGIN_VERSION, true );
		wp_enqueue_style( 'll_vdm_main', LL_VDM_PLUGIN_URL . 'dist/main.css', array(), LL_VDM_PLUGIN_VERSION, 'all' );
	}

	public function localize(): void {
		$data = $this->get_localize_data();
		wp_localize_script( 'll_vdm_main', 'll_vdm_options', $data );
	}

	public function get_localize_data(): array {
		return array(
			'api' => array(
				'base_url'    => Client::BASE_API_URL,
				'client_name' => ( new ClientNameSetting() )->get_value(),
			),
		);
	}

	public function get_development_src( string $path = '' ): string {
		$config   = $this->get_src_config();
		$protocol = $config['secure'] ? 'https' : 'http';
		return $protocol . '://localhost:' . $config['port'] . $path;
	}

	/**
	 * Get config settings from development/config.default.json and (optional) development/config.local.json
	 */
	public function get_src_config(): array {
		$local_config_path = LL_VDM_PLUGIN_PATH . 'development/config.local.json';
		$local_config      = array();
		$manifest          = json_decode( file_get_contents( LL_VDM_PLUGIN_PATH . 'development/config.default.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		if ( file_exists( $local_config_path ) ) {
			$local_config = json_decode( file_get_contents( $local_config_path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		}

		// Merge the two config files and the local config is always leading
		return array_merge( $manifest['config'], $local_config );
	}

	/**
	 * Get the stylesheet directory uri based on your current environment
	 */
	public function get_assets_directory_url(): string {
		if ( $this->is_development_mode() ) {
			return $this->get_development_src( '/src' );
		}
		return LL_VDM_PLUGIN_URL . 'dist';
	}

	public function enqueue_external_vdm_script(): void {
		$client_name = ( new ClientNameSetting() )->get_value();
		if ( empty( $client_name ) ) {
			return;
		}

		$display_type = ( new TicketSalesScreenTypeSetting() )->get_value();
		$src          = 'https://tickets.voordemensen.nl/' . rawurlencode( $client_name ) . '/event/vdm_loader.js';
		if ( $display_type === 'side' ) {
			$src = 'https://tickets.voordemensen.nl/' . rawurlencode( $client_name ) . '/event/vdm_sideloader.js';
		}

		wp_enqueue_script( 'll_vdm_external_script', $src, array(), LL_VDM_PLUGIN_VERSION, true );
	}

	public function defer_external_vdm_script( string $tag, string $handle ): string {
		if ( $handle !== 'll_vdm_external_script' ) {
			return $tag;
		}
		return str_replace( ' src=', ' defer src=', $tag );
	}
}
