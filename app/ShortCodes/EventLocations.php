<?php

namespace LevelLevel\VoorDeMensen\ShortCodes;

use LevelLevel\VoorDeMensen\Objects\Event;

class EventLocations extends BaseShortCode {

	public const NAME = 'event_locations';

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

		$event = Event::get_by_post_id( (int) $args['post_id'] );
		if ( ! $event instanceof Event ) {
			return '';
		}

		$sub_events = $event->get_sub_events();

		$locations = array();
		foreach ($sub_events as $sub_event) {
			$locations[] = $sub_event->get_location_name();
		}
		$locations = array_unique( $locations );

		// Render html
		$html  = '<ul>';
		foreach ($locations as $location) {
			$html .= '<li>';
			$html .= esc_html( $location );
			$html .= '</li>';
		}
		$html .= '</ul>';
		$html = apply_filters( self::PREFIX . 'shortcode_' . self::NAME . '_html', $html, $args, $event );
		return $html;
	}
}
