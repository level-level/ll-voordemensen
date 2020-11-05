<?php

namespace LevelLevel\VoorDeMensen\Sync;

use LevelLevel\VoorDeMensen\API\Client;
use LevelLevel\VoorDeMensen\Objects\Location;
use WP_Error;

class LocationsSync extends BaseSync {

	protected const TRIGGER = 'll_vdm_sync_locations';

	protected function sync( int $limit = null ): void {
		$client        = new Client();
		$api_locations = $client->get_locations();
		if ( $limit !== null ) {
			$api_locations = array_slice( $api_locations, -$limit );
		}

		foreach ( $api_locations as $api_location ) {
			$this->create_or_update_location( $api_location );
		}
	}

	/**
	 * Create or update location object from api location data
	 *
	 * @param object $api_location
	 * @return integer
	 */
	protected function create_or_update_location( $api_location ): int {
		$vdm_id      = (string) $api_location->location_id;
		$location    = Location::get_by_vdm_id( $vdm_id );
		$location_id = 0;
		if ( $location instanceof Location ) {
			$location_id = $location->get_id();
		}

		do_action( 'll_vdm_before_insert_location', $location_id, $api_location );

		$term_data = array(
			'name' => $api_location->location_name ?? '',
			'slug' => sanitize_title( $api_location->location_name ?? '' ),
		);
		$term_data = apply_filters( 'll_vdm_update_location_term_data', $term_data, $api_location );

		$term_result = null;
		if ( ! $location_id ) {
			$term_result = wp_insert_term( $api_location->location_name, Location::$taxonomy, $term_data );
		} else {
			$term_result = wp_update_term( $location_id, Location::$taxonomy, $term_data );
		}

		if ( $term_result instanceof WP_Error || ! isset( $term_result['term_id'] ) || ! $term_result['term_id'] ) {
			return 0;
		}

		$location_id = $term_result['term_id'];

		// Update meta
		update_term_meta( $location_id, 'vdm_id', $api_location->location_id );
		update_term_meta( $location_id, 'vdm_address', $api_location->location_address ?? null );
		update_term_meta( $location_id, 'vdm_address_1', $api_location->location_address1 ?? null );
		update_term_meta( $location_id, 'vdm_zip_code', $api_location->ocation_zip ?? null );
		update_term_meta( $location_id, 'vdm_city', $api_location->location_city ?? null );
		update_term_meta( $location_id, 'vdm_country', $api_location->location_country ?? null );
		update_term_meta( $location_id, 'vdm_phone', $api_location->location_phone ?? null );

		do_action( 'll_vdm_after_insert_location', $location_id, $api_location );
		return $location_id;
	}
}
