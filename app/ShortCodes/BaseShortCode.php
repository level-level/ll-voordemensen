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
	abstract protected function get_args( $user_args ): array;

	/**
	 * Get shortcode html
	 *
	 * @param string|array $user_args
	 * @return string
	 */
	abstract public function get_html( $user_args ): string;
}
