<?php

namespace LevelLevel\VoorDeMensen\ShortCodes;

use LevelLevel\VoorDeMensen\Objects\Event;

class EventContent extends BaseShortCode {

	public const NAME = 'event_content';

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

		$content = '';
		$event   = Event::get_by_post_id( (int) $args['post_id'] );
		if ( $event instanceof Event ) {
			$content = $event->get_text();
		}

		// Render html
		$html = apply_filters( self::PREFIX . 'shortcode_' . self::NAME . '_html', $content, $args, $event );
		return $html;
	}
}
