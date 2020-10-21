<?php

namespace LevelLevel\VoorDeMensen\Objects;

class Event extends BaseObject {
	public static $type = 'll_vdm_event';

	public static function get_by_post_id( int $post_id ) {
		$vdm_id = (int) get_post_meta( $post_id, 'll_vdm_id', true );
		if ( ! $vdm_id ) {
			return null;
		}

		return static::get_by_vdm_id( $vdm_id );
	}
}
