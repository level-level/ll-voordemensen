<?php

namespace LevelLevel\VoorDeMensen\ShortCodes;

use LevelLevel\VoorDeMensen\Objects\Event;

class EventName extends BaseShortCode {

	public const NAME = 'event_name';

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

		$event_name = '';
		$event      = Event::get_by_post_id( (int) $args['post_id'] );
		if ( $event instanceof Event ) {
			$event_name = $event->get_title();
		}

		// Render html
		$html = apply_filters( self::PREFIX . 'shortcode_' . self::NAME . '_html', $event_name, $args, $event );
		return $html;
	}
}
