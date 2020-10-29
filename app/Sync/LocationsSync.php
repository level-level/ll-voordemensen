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

		// Update post object
		$post_data = array(
			'ID'          => $location_id,
			'post_status' => 'publish',
			'post_type'   => Location::$type,
			'post_title'  => $api_location->location_name ?? '',
			'post_name'   => sanitize_title( $api_location->location_name ?? '' ),
			'meta_input'  => array(
				'vdm_id'    => $api_location->location_id,
				'address'   => $api_location->location_address ?? null,
				'address_1' => $api_location->location_address1 ?? null,
				'zip_code'  => $api_location->ocation_zip ?? null,
				'city'      => $api_location->location_city ?? null,
				'country'   => $api_location->location_country ?? null,
				'phone'     => $api_location->location_phone ?? null,
			),
		);
		$post_data = apply_filters( 'll_vdm_update_location_post_data', $post_data, $api_location );

		$location_id = wp_insert_post( $post_data );
		if ( ! $location_id || $location_id instanceof WP_Error ) {
			return 0;
		}

		do_action( 'll_vdm_after_insert_location', $location_id, $api_location );
		return $location_id;
	}
}
