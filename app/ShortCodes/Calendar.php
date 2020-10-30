<?php

namespace LevelLevel\VoorDeMensen\ShortCodes;

use LevelLevel\VoorDeMensen\Objects\Event;

class Calendar extends BaseShortCode {

	public const NAME = 'calendar';

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
			$content = __( 'View calendar', 'll-vdm' );
		}

		// Render html
		$html  = '<button data-ll-vdm-module="calendar">';
		$html .= $content;
		$html .= '</button>';
		$html  = apply_filters( self::PREFIX . 'shortcode_' . self::NAME . '_html', $html, $args, $content );
		return $html;
	}
}
