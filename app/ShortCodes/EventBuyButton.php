<?php

namespace LevelLevel\VoorDeMensen\ShortCodes;

use LevelLevel\VoorDeMensen\Objects\Event;

class EventBuyButton extends BaseShortCode {

	public const NAME = 'event_buy_button';

	public function get_name(): string {
		return self::PREFIX . self::NAME;
	}

	/**
	 * Get shortcode html
	 *
	 * @param string|array $user_args
	 * @return string
	 */
	public function get_html( $user_args, string $content = '' ): string {
		// Prepare variables
		$args = $this->get_args( $user_args );

		if ( empty( $content ) ) {
			$content = __( 'Buy now', 'll-vdm' );
		}

		$event        = Event::get_by_post_id( (int) $args['post_id'] );
		$event_vdm_id = 0;
		if ( $event instanceof Event ) {
			$event_vdm_id = $event->get_vdm_id();
		}

		// Render html
		$html  = '<button data-ll-vdm-module="eventBuyButton" data-vdm-event-id="' . $event_vdm_id . '" ' . disabled( $event_vdm_id, 0, false ) . '>';
		$html .= $content;
		$html .= '</button>';
		$html  = apply_filters( self::PREFIX . 'shortcode_' . self::NAME . '_html', $html, $args, $content, $event );
		return $html;
	}
}
