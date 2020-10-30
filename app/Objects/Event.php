<?php

namespace LevelLevel\VoorDeMensen\Objects;

class Event extends BaseObject {
	public static $type = 'll_vdm_event';

	/**
	 * Get object by vdm ID
	 *
	 * @return static|null
	 */
	public static function get_by_vdm_id( string $vdm_id, array $args = array() ) {
		$default_args = array(
			'post_status' => 'any',
			'meta_query'  => array(
				array(
					'key'   => 'vdm_id',
					'value' => $vdm_id,
				),
			),
		);
		$args         = wp_parse_args( $args, $default_args );
		return static::get_one( $args );
	}

	/**
	 * Get object by connected post ID
	 *
	 * @param integer $post_id
	 * @return static|null
	 */
	public static function get_by_post_id( int $post_id ) {
		$event_id = (int) get_post_meta( $post_id, 'll_vdm_event_id', true );
		if ( ! $event_id ) {
			return null;
		}

		return static::get( $event_id );
	}

	/**
	 * Get sub events
	 *
	 * @param array $args
	 * @return SubEvent[]
	 */
	public function get_sub_events( $args = array() ): array {
		$default_args = array(
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'   => 'event_id',
					'value' => $this->get_id(),
				),
			),
		);
		$args         = wp_parse_args( $args, $default_args );
		return SubEvent::get_many( $args );
	}

	public function get_short_text(): string {
		return (string) $this->get_meta( 'short_text', true );
	}
}
