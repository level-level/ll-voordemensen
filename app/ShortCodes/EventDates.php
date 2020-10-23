<?php

namespace LevelLevel\VoorDeMensen\ShortCodes;

use DateTime;
use DateTimeZone;
use LevelLevel\VoorDeMensen\Objects\Event;

class EventDates extends BaseShortCode {

	public const NAME = 'event_dates';

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

		$event      = Event::get_by_post_id( (int) $args['post_id'] );
		if ( $event instanceof Event ) {
			$sub_events = $event->get_sub_events();
		}

		$date_format = (string) get_option('date_format') ?: 'Y-m-d';
		$time_format = (string) get_option('time_format') ?: 'H-i';

		// Render html
		$html  = '<ul>';
		foreach ($sub_events as $sub_event) {
			$start_date = $sub_event->get_start_date();
			$end_date = $sub_event->get_end_date();

			if ( !$start_date instanceof DateTime ) {
				continue;
			}

			$html .= '<li>';
			$html .= $start_date->format( $date_format ) . ' ' . $start_date->format( $time_format );
			if ( $end_date instanceof DateTime ) {
				$html .= __( ' to ', 'll-vdm' );
				if ( $start_date->format( $date_format ) !== $end_date->format( $date_format ) ) {
					$html .= $end_date->format( $date_format ) . ' ';
				}
				$html .= $end_date->format( $time_format );
			}
			$html .= '</li>';
		}
		$html .= '</ul>';
		$html = apply_filters( self::PREFIX . 'shortcode_' . self::NAME . '_html', $html, $args, $event );
		return $html;
	}
}
