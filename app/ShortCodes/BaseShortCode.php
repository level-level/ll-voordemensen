<?php

namespace LevelLevel\VoorDeMensen\ShortCodes;

use WP_Post;

abstract class BaseShortCode {

	public const PREFIX = 'll_vdm_';

	abstract public function get_name(): string;

	/**
	 * Get shortcode args
	 *
	 * @param string|array $user_args
	 * @return array
	 */
	protected function get_args( $user_args ): array {
		$args = shortcode_atts(
			array(
				'post_id' => get_the_ID(),
			), (array) $user_args, $this->get_name()
		);
		return $args;
	}

	/**
	 * Get shortcode html
	 *
	 * @param string|array $user_args
	 * @return string
	 */
	abstract public function get_html( $user_args ): string;
}
