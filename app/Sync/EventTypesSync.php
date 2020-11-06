<?php

namespace LevelLevel\VoorDeMensen\Sync;

use LevelLevel\VoorDeMensen\API\Client;
use LevelLevel\VoorDeMensen\Objects\Location;
use WP_Error;

class EventTypesSync extends BaseSync {

	protected const TRIGGER = 'll_vdm_sync_event_types';

	protected function sync( int $limit = null ): void {
		$client        = new Client();
		$api_event_types = $client->get_event_types();
		if ( $limit !== null ) {
			$api_event_types = array_slice( $api_event_types, -$limit );
		}

		foreach ( $api_event_types as $api_event_type ) {
			$this->create_or_update_event_type( $api_event_type );
		}
	}

	/**
	 * Create or update location object from api location data
	 *
	 * @param object $api_event_type
	 * @return integer
	 */
	protected function create_or_update_event_type( $api_event_type ): int {
		$vdm_id      = (string) $api_event_type->location_id;
		$location    = Location::get_by_vdm_id( $vdm_id );
		$location_id = 0;
		if ( $location instanceof Location ) {
			$location_id = $location->get_id();
		}

		do_action( 'll_vdm_before_insert_location', $location_id, $api_event_type );

		$term_data = array(
			'name' => $api_event_type->location_name ?? '',
			'slug' => sanitize_title( $api_event_type->location_name ?? '' ),
		);
		$term_data = apply_filters( 'll_vdm_update_location_term_data', $term_data, $api_event_type );

		$term_result = null;
		if ( ! $location_id ) {
			$term_result = wp_insert_term( $api_event_type->location_name, Location::$taxonomy, $term_data );
		} else {
			$term_result = wp_update_term( $location_id, Location::$taxonomy, $term_data );
		}

		if ( $term_result instanceof WP_Error || ! isset( $term_result['term_id'] ) || ! $term_result['term_id'] ) {
			return 0;
		}

		$location_id = $term_result['term_id'];

		// Update meta
		update_term_meta( $location_id, 'll_vdm_vdm_id', $api_event_type->location_id );
		update_term_meta( $location_id, 'll_vdm_address', $api_event_type->location_address ?? null );
		update_term_meta( $location_id, 'll_vdm_address_1', $api_event_type->location_address1 ?? null );
		update_term_meta( $location_id, 'll_vdm_zip_code', $api_event_type->ocation_zip ?? null );
		update_term_meta( $location_id, 'll_vdm_city', $api_event_type->location_city ?? null );
		update_term_meta( $location_id, 'll_vdm_country', $api_event_type->location_country ?? null );
		update_term_meta( $location_id, 'll_vdm_phone', $api_event_type->location_phone ?? null );

		do_action( 'll_vdm_after_insert_location', $location_id, $api_event_type );
		return $location_id;
	}
}
