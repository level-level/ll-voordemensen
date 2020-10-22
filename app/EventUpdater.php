<?php

namespace LevelLevel\VoorDeMensen;

use WP_Post;

class EventUpdater {
	public function register_hooks() {
		add_action( 'save_post', array( $this, 'update_event_on_post_save' ), 10, 3 );
	}

	public function update_event_on_post_save( int $post_id, WP_Post $post, bool $update ) {

	}
}
