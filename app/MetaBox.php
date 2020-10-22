<?php

namespace LevelLevel\VoorDeMensen;

use LevelLevel\VoorDeMensen\API\Client;
use LevelLevel\VoorDeMensen\Objects\Event;
use WP_Post;

class MetaBox {
	public function register_hooks() {
		add_action('add_meta_boxes', array( $this, 'add_meta_box' ));
	}

	public function add_meta_box( string $post_type ) {
		$client_name = get_option( 'll_vdm_api_client_name', null );
		if ( empty( $client_name ) ) {
			return;
		}

		add_meta_box(
			'll_vdm_id',
			__( 'VoordeMensen settings', 'll-vdm' ),
			array( $this, 'render_meta_box_html' ),
		);
	}

	public function render_meta_box_html( WP_Post $post ) {
		$event = Event::get_by_post_id( $post->ID );
		$api_client = new Client();
		$api_events = $api_client->get_events();

		require_once LL_VDM_PLUGIN_PATH . 'templates/admin/metabox.php';
	}
}
