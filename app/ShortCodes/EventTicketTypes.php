<?php

namespace LevelLevel\VoorDeMensen\ShortCodes;

use LevelLevel\VoorDeMensen\Objects\Event;

class EventTicketTypes extends BaseShortCode {

	public const NAME = 'event_ticket_types';

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

		// Render html
		$html = '<ul>';
		foreach ( $sub_events as $sub_event ) {
			$html .= '<li>';
			$html .= '<h3>' . esc_html( $sub_event->get_title() ) . '</h3>';
			$html .= '<ul>';

			$ticket_types = $sub_event->get_ticket_types();
			foreach ( $ticket_types as $ticket_type ) {
				$html .= '<li>';
				$html .= '&euro;' . number_format_i18n( $ticket_type->get_price(), 2 ) . ' (' . esc_html( $ticket_type->get_title() ) . ')';
				$html .= '</li>';
			}
			$html .= '</ul>';
			$html .= '</li>';
		}
		$html .= '</ul>';
		$html  = apply_filters( self::PREFIX . 'shortcode_' . self::NAME . '_html', $html, $args, $event );
		return $html;
	}
}
