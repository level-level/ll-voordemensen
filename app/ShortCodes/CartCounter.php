<?php

namespace LevelLevel\VoorDeMensen\ShortCodes;

class CartCounter extends BaseShortCode {

	public const NAME = 'cart_counter';

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

		// Render html
		$html  = '<span data-ll-vdm-module="cartCounter"></span>';
		$html  = apply_filters( self::PREFIX . 'shortcode_' . self::NAME . '_html', $html, $args );
		return $html;
	}
}
