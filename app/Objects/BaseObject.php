<?php

namespace LevelLevel\VoorDeMensen\Objects;

use Exception;
use LevelLevel\VoorDeMensen\Admin\Settings\General\Fields\PostTypes as PostTypesSetting;
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
	 * Microcache for parsed post content.
	 *
	 * @var null|string
	 */
	protected $content;

	/**
	 * Define $posts.
	 *
	 * @var array $posts
	 */
	protected static $posts = array();

	public function __construct( WP_Post $post ) {
		$this->post = $post;
	}

	/**
	 * Get object by id
	 *
	 * @param int $id
	 * @return static|null
	 */
	public static function get( int $id ) {
		if ( ! isset( static::$posts[ $id ] ) ) {
			$class = static::class;

			try {
				/**
				 * @psalm-suppress UnsafeInstantiation
				 */
				static::$posts[ $id ] = new $class( get_post( $id ) );
			} catch ( Exception $e ) {
				static::$posts[ $id ] = null;
			}
		}

		return static::$posts[ $id ];
	}

	/**
	 * Get multiple objects
	 *
	 * @param array $args
	 * @return static[]
	 */
	public static function get_many( array $args = array() ): array {
		$args['post_type']     = static::$type;
		$args['no_found_rows'] = true;

		$query = new \WP_Query( $args );

		$class = static::class;

		return array_map(
			function( $post ) use ( $class ) {
				/**
				 * @psalm-suppress UnsafeInstantiation
				 */
				return new $class( $post );
			},
			$query->posts
		);
	}

	/**
	 * Get single object
	 *
	 * @param array $args
	 * @return static|null
	 */
	public static function get_one( array $args = array() ) {
		$args['posts_per_page'] = 1;
		$one                    = static::get_many( $args );
		return array_shift( $one );
	}

	/**
	 * Get object by vdm ID
	 *
	 * @param array $args
	 * @return static|null
	 */
	public static function get_by_vdm_id( int $vdm_id ) {
		$args = array(
			'post_status' => 'any',
			'meta_query'  => array(
				array(
					'key'   => 'vdm_id',
					'value' => $vdm_id,
				),
			),
		);
		return static::get_one( $args );
	}

	public function get_object(): WP_Post {
		return $this->post;
	}

	public function get_id(): int {
		return $this->post->ID;
	}

	public function get_vdm_id(): int {
		return (int) $this->get_meta( 'vdm_id' );
	}

	public function get_title(): string {
		return get_the_title( $this->get_id() );
	}

	/**
	 * Get post content
	 */
	public function get_content(): string {
		global $post;
		if ( ! isset( $this->content ) ) {
			setup_postdata( $this->post );

			if ( null === $post ) {
				$post = $this->post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, SlevomatCodingStandard.Variables.UnusedVariable.UnusedVariable
			}

			ob_start();
			the_content();

			$this->content = ob_get_clean() ?: '';
			wp_reset_postdata();
		}

		return $this->content;
	}

	public function has_thumbnail(): bool {
		return has_post_thumbnail( $this->get_id() );
	}

	public function get_thumbnail_id(): int {
		return get_post_thumbnail_id( $this->get_id() ) ?: 0;
	}

	/**
	 * Get thumbnail
	 *
	 * @param string|array $size
	 * @param string|array $attr
	 * @return string
	 */
	public function get_thumbnail( $size = 'thumbnail', $attr = '' ): string {
		return get_the_post_thumbnail( $this->get_id(), $size, $attr );
	}

	public function get_short_text(): string {
		return (string) $this->get_meta( 'short_text' );
	}

	/**
	 * Get meta value
	 *
	 * @param string $key
	 * @param boolean $single
	 * @return mixed
	 */
	public function get_meta( string $key, bool $single = false ) {
		return get_post_meta( $this->get_id(), $key, $single );
	}

	public function get_connected_posts( array $args = array() ): array {
		$vdm_id     = $this->get_vdm_id();
		$post_types = ( new PostTypesSetting() )->get_value();

		if ( empty( $post_types ) ) {
			return array();
		}

		$default_args = array(
			'post_type'      => $post_types,
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'   => 'll_vdm_event_id',
					'value' => $vdm_id,
				),
			),
		);
		$args         = wp_parse_args( $args, $default_args );
		return get_posts( $args );
	}
}
