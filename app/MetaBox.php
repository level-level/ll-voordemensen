<?php

namespace LevelLevel\VoorDeMensen;

use LevelLevel\VoorDeMensen\Objects\Event;
use WP_Post;

class Metabox {
	public function register_hooks() {
		add_action('add_meta_boxes', array( $this, 'add_meta_box' ));
	}

	public function add_meta_box( string $post_type ) {
		add_meta_box(
			'll_vdm_id',
			__( 'VoordeMensen event', 'll-vdm' ),
			array( 'render_meta_box_html' ),
		);
	}

	public function render_meta_box_html( WP_Post $post ) {
		$event = Event::get_by_post_id( $post->ID );
	}
}
