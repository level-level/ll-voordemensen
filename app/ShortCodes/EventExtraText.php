<?php

namespace LevelLevel\VoorDeMensen\ShortCodes;

use LevelLevel\VoorDeMensen\Objects\Event;

class EventExtraText extends BaseShortCode {

	public const NAME = 'event_extra_text';

	public function get_name(): string {
		return self::PREFIX . self::NAME;
	}

	/**
	 * Get shortcode html
	 *
	 * @param string|array $user_args
	 * @return string
	 */
	public function get_html( $user_args ): string {
		// Prepare variables
		$args = $this->get_args( $user_args );

		$text = '';
		$event      = Event::get_by_post_id( (int) $args['post_id'] );
		if ( $event instanceof Event ) {
			$text = $event->get_short_text();
		}

		// Render html
		$html = apply_filters( self::PREFIX . 'shortcode_' . self::NAME . '_html', $text, $args, $event );
		return $html;
	}
}
