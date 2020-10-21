<?php

namespace LevelLevel\VoorDeMensen\Objects;

use Exception;
use WP_Post;

class BaseObject {

	/**
	 * @var string $type
	 */
	public static $type = 'post';

	/**
	 * @var \WP_Post $_post
	 */
	protected $post;

	/**
	 * Define $posts.
	 *
	 * @var BaseObject[] $posts
	 */
	protected static $posts = array();

	public function __construct( WP_Post $post ) {
		$this->post = $post;
	}

	public function get_post(): WP_Post {
		return $this->post;
	}

	public static function get( int $id ) {
		if ( ! isset( static::$posts[ $id ] ) ) {
			$class = static::class;

			try {
				static::$posts[ $id ] = new $class( get_post( $id ) );
			} catch ( Exception $e ) {
				static::$posts[ $id ] = null;
			}
		}

		return static::$posts[ $id ];
	}

	public static function get_many( array $args = array() ): array {
		$args['post_type']     = static::$type;
		$args['no_found_rows'] = true;

		$query = new \WP_Query( $args );

		$class = static::class;

		return array_map(
			function( $post ) use ( $class ) {
					return new $class( $post );
			},
			$query->posts
		);
	}

	public static function get_one( array $args = array() ): array {
		$args['posts_per_page'] = 1;
		$one                    = static::get_many( $args );
		return array_shift( $one );
	}
}
