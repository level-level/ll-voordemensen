<?php

namespace LevelLevel\VoorDeMensen;

class Init {

	public function register_hooks(): void {
		add_action( 'init', array( $this, 'create_session' ) );
	}

	public function create_session(): void {
		if ( session_id() ) {
			return;
		}
		session_start();
	}
}
