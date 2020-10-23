<?php

namespace LevelLevel\VoorDeMensen\ShortCodes;

use WP_Post;

abstract class BaseShortCode {

	public const PREFIX = 'll_vdm_';

	public abstract function get_name(): string;

	/**
	 * Get shortcode args
	 *
	 * @param string|array $user_args
	 * @return array
	 */
	protected abstract function get_args( $user_args ): array;

	/**
	 * Get shortcode html
	 *
	 * @param string|array $user_args
	 * @return string
	 */
	public abstract function get_html( $user_args ): string;

	protected function get_current_post(): ?WP_Post {
		$post = get_post();
		if ( ! empty( $args['post_id'] ) && is_numeric( $args['post_id'] ) ) {
			$post = get_post( (int) $args['post_id'] );
		}
		if( ! $post instanceof WP_Post ) {
			return null;
		}
		return $post;
	}

	protected function get_current_post_id(): int {
		$post_id = (int) get_the_ID();
		if ( ! empty( $args['post_id'] ) && is_numeric( $args['post_id'] ) ) {
			$post_id = (int) $args['post_id'];
		}
		return $post_id;
	}
}
