<?php

namespace LevelLevel\VoorDeMensen\ShortCodes;

class Init {
	protected const SHORTCODES = array(
		EventBuyButton::class,
		EventName::class,
		EventContent::class,
		EventExtraText::class,
		EventDates::class,
		EventLocations::class,
		CartButton::class,
		EventTicketTypes::class,
		EventBuyButtons::class,
		CartCounter::class,
		Calendar::class,
	);

	public function register_hooks(): void {
		add_action( 'init', array( $this, 'register_shortcodes' ) );
	}

	public function register_shortcodes(): void {
		foreach ( self::SHORTCODES as $shortcode_class ) {
			$shortcode = new $shortcode_class();
			add_shortcode( $shortcode->get_name(), array( $shortcode, 'get_html' ) );
		}
	}
}
