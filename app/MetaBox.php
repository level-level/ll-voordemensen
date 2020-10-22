<?php

namespace LevelLevel\VoorDeMensen;

use LevelLevel\VoorDeMensen\API\Client;
use LevelLevel\VoorDeMensen\Objects\Event;
use WP_Post;

class MetaBox {
	public function register_hooks(): void {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_metabox_fields' ), 10, 2 );
	}

	public function add_meta_box( string $post_type ): void {
		$client_name = get_option( 'll_vdm_api_client_name', null );
		if ( empty( $client_name ) ) {
			return;
		}

		add_meta_box(
			'll-vdm',
			__( 'VoordeMensen settings', 'll-vdm' ),
			array( $this, 'render_meta_box_html' )
		);
	}

	public function render_meta_box_html( WP_Post $post ): void {
		// phpcs:disable SlevomatCodingStandard.Variables.UnusedVariable.UnusedVariable
		$event_vdm_id = (int) get_post_meta( $post->ID, 'll_vdm_event_id', true );
		$api_client   = new Client();
		$api_events   = $api_client->get_events();
		// phpcs:enable SlevomatCodingStandard.Variables.UnusedVariable.UnusedVariable

		require_once LL_VDM_PLUGIN_PATH . 'templates/admin/metabox.php';
	}

	/**
	 * Save metabox input fields
	 */
	public function save_metabox_fields( int $post_id, WP_Post $post ): void {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$nonce_value = filter_input( INPUT_POST, 'll_vdm_metabox_nonce' );
		$nonce_valid = wp_verify_nonce( $nonce_value, 'll_vdm_metabox' );
		if ( ! $nonce_valid ) {
			return;
		}

		$vdm_event_id = filter_input( INPUT_POST, 'll_vdm_event_id', FILTER_VALIDATE_INT ) ?: 0;
		update_post_meta( $post_id, 'll_vdm_event_id', $vdm_event_id );
	}
}
