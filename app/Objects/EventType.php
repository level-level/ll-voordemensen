<?php

namespace LevelLevel\VoorDeMensen\Objects;

class EventType extends BaseTerm {
	public static $taxonomy = 'll_vdm_event_type';

	/**
	 * Get by vdm ID
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
}
