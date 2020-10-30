<?php

namespace LevelLevel\VoorDeMensen\Utilities;

class Session {
	public function create(): bool {
		if ( $this->exists() ) {
			false;
		}
		return session_start();
	}

	public function exists(): bool {
		return empty( $this->get_id() );
	}

	public function get_id(): string {
		return session_id();
	}

	public function get_name(): string {
		return session_name();
	}
}
