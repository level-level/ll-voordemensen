<?php

namespace LevelLevel\VoorDeMensen\Objects;

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

			$post = get_post( $id );
			if ( $post instanceof WP_Post ) {
				/**
				 * @psalm-suppress UnsafeInstantiation
				 */
				static::$posts[ $id ] = new $class( $post );
			} else {
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
		$args['fields']        = '';
		$args['no_found_rows'] = true;

		$posts = get_posts( $args );
		$class = static::class;

		return array_map(
			function ( $post ) use ( $class ) {
				/**
				 * @psalm-suppress UnsafeInstantiation
				 */
				return new $class( $post );
			},
			$posts
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
	 * Get objects by vdm ID
	 *
	 * @param string $vdm_id
	 * @param array<string,mixed> $args
	 * @return static[]
	 */
	public static function get_many_by_vdm_id( string $vdm_id, array $args = array() ): array {
		$default_args = array(
			'post_status' => 'any',
			'meta_query'  => array(
				array(
					'key'   => 'll_vdm_vdm_id',
					'value' => $vdm_id,
				),
			),
		);
		$args         = wp_parse_args( $args, $default_args );
		return static::get_many( $args );
	}

	/**
	 * Get object by vdm ID
	 *
	 * @param string $vdm_id
	 * @param array<string,mixed> $args
	 * @return static|null
	 */
	public static function get_by_vdm_id( string $vdm_id, array $args = array() ) {
		$default_args = array(
			'post_status' => 'any',
			'meta_query'  => array(
				array(
					'key'   => 'll_vdm_vdm_id',
					'value' => $vdm_id,
				),
			),
		);
		$args         = wp_parse_args( $args, $default_args );
		return static::get_one( $args );
	}

	public function get_object(): WP_Post {
		return $this->post;
	}

	public function get_id(): int {
		return $this->post->ID;
	}

	public function get_vdm_id(): ?string {
		$vdm_id = (string) $this->get_meta( 'll_vdm_vdm_id', true );
		if ( empty( $vdm_id ) ) {
			return null;
		}
		return $vdm_id;
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

	public function delete(): void {
		wp_delete_post( $this->get_id(), true );
	}
}
