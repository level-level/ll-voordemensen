<?php

namespace LevelLevel\VoorDeMensen\Objects;

class Event extends BaseObject {
	public static $type = 'll_vdm_event';

	/**
	 * Get object by connected post ID
	 *
	 * @param integer $post_id
	 * @return static|null
	 */
	public static function get_by_post_id( int $post_id ) {
		$vevent_id = (int) get_post_meta( $post_id, 'll_vdm_event_id', true );
		if ( ! $vevent_id ) {
			return null;
		}

		return static::get( $vevent_id );
	}
}
