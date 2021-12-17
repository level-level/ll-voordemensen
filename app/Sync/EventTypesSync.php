<?php

namespace LevelLevel\VoorDeMensen\Sync;

use LevelLevel\VoorDeMensen\API\Client;
use LevelLevel\VoorDeMensen\Objects\Event;
use LevelLevel\VoorDeMensen\Objects\EventType;
use LevelLevel\VoorDeMensen\Objects\SubEvent;
use WP_Error;

class EventTypesSync extends BaseSync {

	protected const TRIGGER = 'll_vdm_sync_event_types';

	protected function sync( int $limit = null ): void {
		$client          = new Client();
		$api_event_types = $client->get_event_types();
		if ( $limit !== null ) {
			$api_event_types = array_slice( $api_event_types, -$limit );
		}

		foreach ( $api_event_types as $api_event_type ) {
			$this->create_or_update_event_type( $api_event_type );
		}
	}

	/**
	 * Create or update event type object from api event type data
	 *
	 * @param object $api_event_type
	 */
	protected function create_or_update_event_type( $api_event_type ): int {
		$vdm_id        = (string) $api_event_type->eventtype_name;
		$event_type    = EventType::get_by_vdm_id( $vdm_id );
		$event_type_id = 0;
		if ( $event_type instanceof EventType ) {
			$event_type_id = $event_type->get_id();
		}

		if ( ! apply_filters( 'll_vdm_should_insert_event_type', true, $event_type_id, $api_event_type ) ) {
			do_action( 'll_vdm_skip_sync_event_type', $event_type_id, $api_event_type );
			return 0;
		}

		do_action( 'll_vdm_before_insert_event_type', $event_type_id, $api_event_type );

		$term_data = array(
			'name' => $api_event_type->eventtype_name ?? '',
			'slug' => sanitize_title( $api_event_type->eventtype_name ?? '' ),
		);
		$term_data = apply_filters( 'll_vdm_update_event_type_term_data', $term_data, $api_event_type, $event_type_id );

		$term_result = null;
		if ( ! $event_type_id ) {
			$term_result = wp_insert_term( $api_event_type->eventtype_name, EventType::$taxonomy, $term_data );
		} else {
			$term_result = wp_update_term( $event_type_id, EventType::$taxonomy, $term_data );
		}

		if ( $term_result instanceof WP_Error || ! isset( $term_result['term_id'] ) || ! $term_result['term_id'] ) {
			return 0;
		}

		$event_type_id = $term_result['term_id'];

		// Update meta
		update_term_meta( $event_type_id, 'll_vdm_vdm_id', $api_event_type->eventtype_name );

		do_action( 'll_vdm_after_insert_event_type', $event_type_id, $api_event_type );
		return $event_type_id;
	}
}
