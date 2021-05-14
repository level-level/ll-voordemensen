<?php

namespace LevelLevel\VoorDeMensen\Sync;

use DateTime;
use DateTimeZone;
use LevelLevel\VoorDeMensen\API\Client;
use LevelLevel\VoorDeMensen\Objects\Event;
use LevelLevel\VoorDeMensen\Objects\EventType;
use LevelLevel\VoorDeMensen\Objects\Location;
use LevelLevel\VoorDeMensen\Objects\SubEvent;
use LevelLevel\VoorDeMensen\Objects\TicketType;
use LevelLevel\VoorDeMensen\Utilities\Image as ImageUtil;
use WP_Error;

class EventsSync extends BaseSync {

	protected const TRIGGER           = 'll_vdm_sync_events';
	public const RECENT_LIMIT         = 10;
	protected const API_STATUSSES_MAP = array(
		'pub'   => 'publish',
		'arch'  => 'trash',
		'nosal' => 'publish',
		'trash' => 'trash',
	);

	public function sync_recent(): void {
		$this->sync( self::RECENT_LIMIT );
	}

	protected function sync( int $limit = null ): void {
		$client     = new Client();
		$api_events = $client->get_events();
		if ( $limit !== null ) {
			$api_events = array_slice( $api_events, -$limit );
		}

		foreach ( $api_events as $api_event ) {
			$this->create_or_update_event( $api_event );
		}
	}

	/**
	 * Create or update event object from api event data
	 *
	 * @param object $api_event
	 * @return integer
	 */
	protected function create_or_update_event( $api_event ): int {
		$vdm_id   = (string) $api_event->event_id;
		$event    = Event::get_by_vdm_id( $vdm_id );
		$event_id = 0;
		$update   = false;
		if ( $event instanceof Event ) {
			$event_id = $event->get_id();
		}

		if ( ! apply_filters( 'll_vdm_should_insert_event', true, $event_id, $api_event ) ) {
			return 0;
		}

		do_action( 'll_vdm_before_insert_event', $event_id, $api_event );

		// Update post object
		$post_data = array(
			'ID'          => $event_id,
			'post_status' => 'publish',
			'post_type'   => Event::$type,
			'post_title'  => $api_event->event_name ?? '',
			'post_name'   => sanitize_title( $api_event->event_name ?? '' ),
			'meta_input'  => array(
				'll_vdm_vdm_id'     => $api_event->event_id,
				'll_vdm_text'       => $api_event->event_text ?? null,
				'll_vdm_short_text' => $api_event->event_short_text ?? null,
			),
		);
		$post_data = apply_filters( 'll_vdm_update_event_post_data', $post_data, $api_event );
		if ( ! $post_data['ID'] ) {
			$event_id = wp_insert_post( $post_data );
		} else {
			$event_id = wp_update_post( $post_data );
			$update   = true;
		}
		if ( ! $event_id || $event_id instanceof WP_Error ) {
			return 0;
		}

		// Set thumbnail
		if ( ! empty( $api_event->event_image ) ) {
			$image_util = new ImageUtil();
			$image_util->set_post_thumbnail( $event_id, $api_event->event_image );
		}

		do_action( 'll_vdm_after_insert_event', $event_id, $api_event, $update );

		// Set terms
		$event_type_vdm_ids = $api_event->event_type ?? array();
		$event_types        = array();
		foreach ( $event_type_vdm_ids as $event_type_vdm_id ) {
			if ( empty( $event_type_vdm_id ) ) {
				continue;
			}
			$event_type = EventType::get_by_vdm_id( (string) $event_type_vdm_id );
			if ( ! $event_type instanceof EventType ) {
				continue;
			}
			$event_types[] = $event_type->get_id();
		}
		wp_set_object_terms( $event_id, $event_types, EventType::$taxonomy );

		// Create sub events
		if ( isset( $api_event->sub_events ) && is_array( $api_event->sub_events ) ) {
			foreach ( $api_event->sub_events as $api_sub_event ) {
				if ( ! isset( $api_sub_event->event_id ) ) {
					continue;
				}
				$this->create_or_update_sub_event( $event_id, $api_sub_event );
			}
		}
		return $event_id;
	}

	/**
	 * Create or update sub event object from api sub event data
	 *
	 * @param int $event_id
	 * @param object $api_sub_event
	 * @return integer
	 */
	protected function create_or_update_sub_event( int $event_id, $api_sub_event ): int {
		$vdm_id       = (string) $api_sub_event->event_id;
		$sub_event    = SubEvent::get_by_vdm_id( $vdm_id );
		$sub_event_id = 0;
		$update       = false;
		if ( $sub_event instanceof SubEvent ) {
			$sub_event_id = $sub_event->get_id();
		}

		// Prepare status
		$status = 'draft';
		if ( isset( $api_sub_event->event_status ) ) {
			$status = $this->api_sub_event_status_to_post_status( $api_sub_event->event_status );
		}

		if ( ! apply_filters( 'll_vdm_should_insert_sub_event', ( $status !== 'trash' ), $sub_event_id, $api_sub_event ) ) {
			return 0;
		}

		do_action( 'll_vdm_before_insert_sub_event', $event_id, $api_sub_event );

		// Prepare start and end time
		$start_timestamp = null;
		$end_timestamp   = null;
		$timezone        = new DateTimeZone( 'Europe/Amsterdam' ); // Plugin uses Europe/Amsterdam timestamps
		if ( isset( $api_sub_event->event_date ) ) {
			// Get event start datetime
			if ( isset( $api_sub_event->event_time ) ) {
				$start_date = DateTime::createFromFormat( 'Y-m-d H:i:s', $api_sub_event->event_date . ' ' . $api_sub_event->event_time, $timezone );

				if ( $start_date instanceof DateTime ) {
					$start_timestamp = $start_date->getTimestamp();
				}
			}

			// Get event end datetime
			if ( isset( $api_sub_event->event_end ) ) {
				// Get end date from date and end time fields
				$end_date = DateTime::createFromFormat( 'Y-m-d H:i:s', $api_sub_event->event_date . ' ' . $api_sub_event->event_end, $timezone );

				// If event duration is over multiple dates, and event_view_end is filled in correctly, use that as end date
				if ( isset( $api_sub_event->event_view_end ) && $api_sub_event->event_view_end !== '0000-00-00 00:00:00' ) {
					$alt_end_date = DateTime::createFromFormat( 'Y-m-d H:i:s', $api_sub_event->event_view_end, $timezone );
					if ( $alt_end_date instanceof DateTime && $alt_end_date->getTimestamp() > ( $start_timestamp ?: 0 ) ) {
						$end_date = $alt_end_date;
					}
				}

				// Get end date, and add 1 day if end date is before start date as a fix for events with duration over 1 day
				if ( $end_date instanceof DateTime ) {
					$end_timestamp = $end_date->getTimestamp();
					if ( $end_timestamp < $start_timestamp ) {
						$end_date->modify( '+1 day' );
						$end_timestamp = $end_date->getTimestamp();
					}
				}
			}
		}

		// Update post object
		$post_data = array(
			'ID'          => $sub_event_id,
			'post_status' => $status,
			'post_type'   => SubEvent::$type,
			'post_title'  => $api_sub_event->event_name,
			'post_name'   => sanitize_title( $api_sub_event->event_name ),
			'meta_input'  => array(
				'll_vdm_vdm_id'                => $api_sub_event->event_id,
				'll_vdm_event_id'              => $event_id,
				'll_vdm_vdm_event_id'          => $api_sub_event->event_main_id ?? null,
				'll_vdm_vdm_status'            => $api_sub_event->event_status ?? null,
				'll_vdm_text'                  => $api_sub_event->event_text ?? null,
				'll_vdm_short_text'            => $api_sub_event->event_short_text ?? null,
				'll_vdm_url'                   => $api_sub_event->event_url ?? null,
				'll_vdm_start_date'            => $start_timestamp,
				'll_vdm_end_date'              => $end_timestamp,
				'll_vdm_rep'                   => $api_sub_event->event_rep ?? null,
				'll_vdm_max_tickets_per_order' => $api_sub_event->event_free ?? null,
				'll_vdm_sale_enabled'          => ( isset( $api_sub_event->event_status ) && $api_sub_event->event_status !== 'nosal' ),
			),
		);
		$post_data = apply_filters( 'll_vdm_update_sub_event_post_data', $post_data, $api_sub_event, $event_id );

		if ( ! $post_data['ID'] ) {
			$sub_event_id = wp_insert_post( $post_data );
		} else {
			$sub_event_id = wp_update_post( $post_data );
			$update       = true;
		}
		if ( ! $sub_event_id || $sub_event_id instanceof WP_Error ) {
			return 0;
		}

		// Set thumbnail
		if ( ! empty( $api_sub_event->event_image ) ) {
			$image_util = new ImageUtil();
			$image_util->set_post_thumbnail( $sub_event_id, $api_sub_event->event_image );
		}

		// Set terms
		$location_vdm_id = $api_sub_event->location_id ?? null;
		$location        = Location::get_by_vdm_id( (string) $location_vdm_id );
		$locations       = array();
		if ( $location instanceof Location ) {
			$locations[] = $location->get_id();
		}
		wp_set_object_terms( $sub_event_id, $locations, Location::$taxonomy );

		$event_type_vdm_ids = $api_sub_event->event_type ?? array();
		$event_types        = array();
		foreach ( $event_type_vdm_ids as $event_type_vdm_id ) {
			if ( empty( $event_type_vdm_id ) ) {
				continue;
			}
			$event_type = EventType::get_by_vdm_id( (string) $event_type_vdm_id );
			if ( ! $event_type instanceof EventType ) {
				continue;
			}
			$event_types[] = $event_type->get_id();
		}
		wp_set_object_terms( $sub_event_id, $event_types, EventType::$taxonomy );

		do_action( 'll_vdm_after_insert_sub_event', $sub_event_id, $event_id, $api_sub_event, $update );

		$this->create_or_update_ticket_types( $sub_event_id, $api_sub_event->event_id );

		return $sub_event_id;
	}

	/**
	 * Convert event api status to wp post status
	 *
	 * @param string $api_status One of pub, unpub, nosal, arch, trash
	 */
	protected function api_sub_event_status_to_post_status( string $api_status ): string {
		$status_map = apply_filters( 'll_vdm_api_statusses_map', self::API_STATUSSES_MAP );
		if ( isset( $status_map[ $api_status ] ) ) {
			return $status_map[ $api_status ];
		}
		return 'draft';
	}

	protected function create_or_update_ticket_types( int $sub_event_id, string $vdm_sub_event_id ): void {
		$client           = new Client();
		$api_ticket_types = $client->get_ticket_types( $vdm_sub_event_id );

		// Update ticket types
		$updated_ids = array();
		foreach ( $api_ticket_types as $api_ticket_type ) {
			$updated_ids[] = $this->create_or_update_ticket_type( $sub_event_id, $api_ticket_type );
		}

		// Delete removed ticket type
		$old_ticket_types = TicketType::get_many(
			array(
				'posts_per_page' => -1,
				'post_status'    => 'any',
				'post__not_in'   => $updated_ids,
				'meta_query'     => array(
					array(
						'key'   => 'll_vdm_sub_event_id',
						'value' => $sub_event_id,
					),
				),
			)
		);
		foreach ( $old_ticket_types as $old_ticket_type ) {
			do_action( 'll_vdm_before_delete_ticket_type', $sub_event_id, $old_ticket_type );
			$old_ticket_type->delete();
			do_action( 'll_vdm_after_delete_ticket_type', $sub_event_id );
		}
	}

	/**
	 * Create or update ticket type object from ticket type data
	 *
	 * @param int $event_id
	 * @param object $api_ticket_type
	 * @return integer
	 */
	protected function create_or_update_ticket_type( int $sub_event_id, $api_ticket_type ): int {
		$vdm_id         = (string) $api_ticket_type->discount_id;
		$ticket_type    = TicketType::get_by_vdm_id_and_sub_event_id( $vdm_id, $sub_event_id );
		$ticket_type_id = 0;
		$update         = false;
		if ( $ticket_type instanceof TicketType ) {
			$ticket_type_id = $ticket_type->get_id();
		}

		if ( ! apply_filters( 'll_vdm_should_insert_ticket_type', true, $ticket_type_id, $api_ticket_type ) ) {
			return 0;
		}

		do_action( 'll_vdm_before_insert_ticket_type', $sub_event_id, $api_ticket_type );

		// Update post object
		$post_data = array(
			'ID'          => $ticket_type_id,
			'post_status' => 'publish',
			'post_type'   => TicketType::$type,
			'post_title'  => $api_ticket_type->discount_name,
			'post_name'   => sanitize_title( $api_ticket_type->discount_name ),
			'meta_input'  => array(
				'll_vdm_vdm_id'           => $api_ticket_type->discount_id,
				'll_vdm_sub_event_id'     => $sub_event_id,
				'll_vdm_base_price'       => (float) $api_ticket_type->base_price,
				'll_vdm_discount_type'    => $api_ticket_type->discount_type,
				'll_vdm_discount_value'   => (float) $api_ticket_type->discount_value,
				'll_vdm_discounted_price' => (float) $api_ticket_type->discounted_price,
			),
		);
		$post_data = apply_filters( 'll_vdm_update_ticket_type_post_data', $post_data, $sub_event_id, $api_ticket_type );

		if ( ! $post_data['ID'] ) {
			$ticket_type_id = wp_insert_post( $post_data );
		} else {
			$ticket_type_id = wp_update_post( $post_data );
			$update         = true;
		}

		if ( ! $ticket_type_id || $ticket_type_id instanceof WP_Error ) {
			return 0;
		}

		do_action( 'll_vdm_after_insert_ticket_type', $ticket_type_id, $api_ticket_type, $update );
		return $ticket_type_id;
	}
}
