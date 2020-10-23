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
	public function get_html( $user_args, string $content = null ): string {
		// Prepare variables
		$args = $this->get_args( $user_args );

		if ( $content === null ) {
			$content = __( 'Buy now', 'll-vdm' );
		}

		$event        = Event::get_by_post_id( (int) $args['post_id'] );
		$event_vdm_id = 0;
		if ( $event instanceof Event ) {
			$event_vdm_id = $event->get_vdm_id();
		}

		// Render html
		$html  = '<button onclick="vdm_order(' . $event_vdm_id . ', \'' . esc_attr( session_id() ) . '\');" ' . disabled( $event_vdm_id, 0, false ) . '>';
		$html .= $content;
		$html .= '</button>';
		$html  = apply_filters( self::PREFIX . 'shortcode_' . self::NAME . '_html', $html, $args, $content, $event );
		return $html;
	}
}
