<?php

namespace LevelLevel\VoorDeMensen;

use LevelLevel\VoorDeMensen\Utilities\Session;

class Init {

	public function register_hooks(): void {
		add_action( 'init', array( $this, 'create_session' ) );
	}

	public function create_session(): void {
		( new Session() )->create();
	}
}
