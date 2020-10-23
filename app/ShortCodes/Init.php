<?php

namespace LevelLevel\VoorDeMensen\ShortCodes;

use LevelLevel\VoorDeMensen\ShortCodes\BuyButton;

class Init {
	protected const SHORTCODES = array(
		BuyButton::class
	);

	public function register_hooks(): void {
		add_action( 'init', array( $this, 'register_shortcodes' ) );
	}

	public function register_shortcodes() {
		foreach ( self::SHORTCODES as $shortcode_class ) {
			$shortcode = new $shortcode_class();
			add_shortcode($shortcode->get_name(), array( $shortcode, 'get_html' ));
		}
	}
}
