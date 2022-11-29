<?php

namespace LevelLevel\VoorDeMensen\Objects;

use WP_Error;
use WP_Term;
use WP_Term_Query;

/**
 * @psalm-consistent-constructor
 */

class BaseTerm {

	/**
	 * @var string $type
	 */
	public static $taxonomy = '';

	/**
	 * @var \WP_Term $term
	 */
	protected $term;

	public function __construct( WP_Term $term ) {
		$this->term = $term;
	}

	/**
	 * Get term by name
	 *
	 * @return static|null
	 */
	public static function get_by_name( string $name, string $taxonomy = null ) {
		$term = get_term_by( 'name', $name, $taxonomy ? $taxonomy : static::$taxonomy );
		if ( ! $term instanceof WP_Term ) {
			return null;
		}
		return new static( $term );
	}

	/**
	 * Get term by slug
	 *
	 * @return static|null
	 */
	public static function get_by_slug( string $slug, string $taxonomy = null ) {
		$term = get_term_by( 'slug', $slug, $taxonomy ? $taxonomy : static::$taxonomy );
		if ( ! $term instanceof WP_Term ) {
			return null;
		}
		return new static( $term );
	}

	/**
	 * Get term by id
	 *
	 * @return static|null
	 */
	public static function get_by_id( int $id, string $taxonomy = null ) {
		$term = get_term( $id, $taxonomy ? $taxonomy : static::$taxonomy );
		if ( ! $term instanceof WP_Term ) {
			return null;
		}
		return new static( $term );
	}

	/**
	 * Get term by vdm ID
	 *
	 * @return static|null
	 */
	public static function get_by_vdm_id( string $vdm_id, array $args = array() ) {
		$default_args = array(
			'hide_empty' => false,
			'meta_query' => array(
				array(
					'key'   => 'll_vdm_vdm_id',
					'value' => $vdm_id,
				),
			),
		);
		$args         = wp_parse_args( $args, $default_args );
		return static::get_one( $args );
	}

	/**
	 * Get terms by vdm ID
	 *
	 * @return static|null
	 */
	public static function get_many_by_vdm_id( string $vdm_id, array $args = array() ) {
		$default_args = array(
			'hide_empty' => false,
			'meta_query' => array(
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
	 * Get multiple terms
	 *
	 * @param array $args
	 * @return static[]
	 */
	public static function get_many( array $args = array() ): array {
		$args['taxonomy'] = static::$taxonomy;
		$args['fields']   = 'all';

		$query       = new WP_Term_Query( $args );
		$query_terms = $query->get_terms();

		$terms = array();
		/**
		 * @psalm-suppress PossiblyInvalidIterator
		 *
		 * Ignore iterator is not an array, as this is always an array
		 */
		foreach ( $query_terms as $term ) {
			if ( ! $term instanceof WP_Term ) {
				continue;
			}
			$terms[] = new static( $term );
		}
		return $terms;
	}

	/**
	 * Get single term
	 *
	 * @param array $args
	 * @return static|null
	 */
	public static function get_one( array $args = array() ) {
		$args['number'] = 1;
		$one            = static::get_many( $args );
		return array_shift( $one );
	}

	public function get_term(): WP_Term {
		return $this->term;
	}

	public function get_id(): int {
		return $this->term->term_id;
	}

	public function get_vdm_id(): ?string {
		$vdm_id = (string) $this->get_meta( 'll_vdm_vdm_id', true );
		if ( empty( $vdm_id ) ) {
			return null;
		}
		return $vdm_id;
	}

	/**
	 * Get parent term
	 *
	 * @return static|null
	 */
	public function get_parent() {
		if ( $this->term->parent ) {
			return static::get_by_id( $this->term->parent );
		}
		return null;
	}

	public function get_taxonomy(): string {
		return $this->term->taxonomy;
	}

	public function get_slug(): string {
		return $this->term->slug;
	}

	public function get_name(): string {
		return $this->term->name;
	}

	public function get_description(): string {
		return $this->term->description;
	}

	public function get_term_taxonomy_id(): int {
		return $this->term->term_taxonomy_id;
	}

	public function get_permalink(): ?string {
		$link = get_term_link( $this->get_term(), $this->get_taxonomy() );
		if ( $link instanceof WP_Error ) {
			return null;
		}
		return $link;
	}

	/**
	 * Get meta value
	 *
	 * @param string $key
	 * @param boolean $single
	 * @return mixed
	 */
	public function get_meta( string $key, bool $single = false ) {
		return get_term_meta( $this->get_id(), $key, $single );
	}

	public function delete(): void {
		wp_delete_term( $this->get_id(), $this->get_taxonomy() );
	}
}
