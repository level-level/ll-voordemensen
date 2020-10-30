<?php

namespace LevelLevel\VoorDeMensen\ShortCodes;

use DateTime;
use LevelLevel\VoorDeMensen\Objects\Event;

class EventBuyButtons extends BaseShortCode {

	public const NAME = 'event_buy_buttons';

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

		$sub_events  = $event->get_sub_events();
		$date_format = (string) get_option( 'date_format' ) ?: 'Y-m-d';
		$time_format = (string) get_option( 'time_format' ) ?: 'H-i';
		$html        = '';
		foreach ( $sub_events as $sub_event ) {
			$start_date = $sub_event->get_start_date();
			if ( ! $start_date instanceof DateTime ) {
				continue;
			}
			$vdm_id = $sub_event->get_vdm_id();
			$html   = '<button data-ll-vdm-module="eventBuyButton" data-vdm-event-id="' . $vdm_id . '" ' . disabled( $vdm_id, 0, false ) . '>';
			$html  .= $start_date->format( $date_format ) . ' ' . $start_date->format( $time_format );
			$html  .= '</button>';
		}

		$html = apply_filters( self::PREFIX . 'shortcode_' . self::NAME . '_html', $html, $args, $event );
		return $html;
	}
}
