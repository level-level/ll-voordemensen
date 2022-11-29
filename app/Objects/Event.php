<?php

namespace LevelLevel\VoorDeMensen\Objects;

use DateTime;
use LevelLevel\VoorDeMensen\Admin\Settings\General\Fields\PostTypes as PostTypesSetting;

class Event extends BaseObject {
	public static $type = 'll_vdm_event';

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
					'key'   => 'll_vdm_event_id',
					'value' => $this->get_id(),
				),
			),
		);
		$args         = wp_parse_args( $args, $default_args );
		return SubEvent::get_many( $args );
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

	public function get_text(): string {
		return (string) $this->get_meta( 'll_vdm_text', true );
	}

	public function get_short_text(): string {
		return (string) $this->get_meta( 'll_vdm_short_text', true );
	}

	public function get_event_types(): array {
		$terms   = wp_get_post_terms( $this->get_id(), EventType::$taxonomy );
		$objects = array();

		if ( ! is_array( $terms ) ) {
			return $objects;
		}

		foreach ( $terms as $term ) {
			$objects[] = new EventType( $term );
		}

		return $objects;
	}

	/**
	 * Get start date from earliest sub events start date
	 *
	 * @return DateTime|null
	 */
	public function get_start_date(): ?DateTime {
		$start_date = null;
		$sub_events = $this->get_sub_events();
		foreach ( $sub_events as $sub_event ) {
			$sub_event_start_date = $sub_event->get_start_date();
			if ( ! $sub_event_start_date instanceof DateTime ) {
				continue;
			}

			if ( $start_date instanceof DateTime && $sub_event_start_date->getTimestamp() > $start_date->getTimestamp() ) {
				continue;
			}

			$start_date = $sub_event_start_date;
		}
		return $start_date;
	}

	/**
	 * Get end date from latest sub events end date
	 *
	 * @return DateTime|null
	 */
	public function get_end_date(): ?DateTime {
		$end_date   = null;
		$sub_events = $this->get_sub_events();
		foreach ( $sub_events as $sub_event ) {
			$sub_event_end_date = $sub_event->get_end_date();
			if ( ! $sub_event_end_date instanceof DateTime ) {
				$sub_event_end_date = $sub_event->get_start_date();
			}
			if ( ! $sub_event_end_date instanceof DateTime ) {
				continue;
			}

			if ( $end_date instanceof DateTime && $sub_event_end_date->getTimestamp() < $end_date->getTimestamp() ) {
				continue;
			}

			$end_date = $sub_event_end_date;
		}
		return $end_date;
	}
}
