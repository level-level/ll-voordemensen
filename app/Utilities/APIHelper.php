<?php

namespace LevelLevel\VoorDeMensen\Utilities;

use DateTime;
use DateTimeZone;
use LevelLevel\VoorDeMensen\Objects\Event;
use LevelLevel\VoorDeMensen\Objects\SubEvent;
use LevelLevel\VoorDeMensen\Utilities\Image as ImageUtil;
use WP_Error;

class APIHelper {

	/**
	 * Create or update event object from api event data
	 *
	 * @param object $api_event
	 * @return integer
	 */
	public function create_or_update_event_object( $api_event ): int {
		$vdm_id   = (int) $api_event->event_id ?? 0;
		$event    = Event::get_by_vdm_id( $vdm_id );
		$event_id = 0;
		if ( $event instanceof Event ) {
			$event_id = $event->get_id();
		}

		do_action( 'll_vdm_before_insert_event', $event_id, $api_event );

		// Update post object
		$post_data = array(
			'ID'           => $event_id,
			'post_status'  => 'publish',
			'post_type'    => Event::$type,
			'post_title'   => $api_event->event_name,
			'post_name'    => sanitize_title( $api_event->event_name ),
			'post_content' => '<p>' . esc_html( $api_event->event_text ) . '</p>',
		);
		$post_data = apply_filters( 'll_vdm_update_event_post_data', $post_data, $api_event );

		$event_id   = wp_insert_post( $post_data );
		if ( ! $event_id || $event_id instanceof WP_Error ) {
			return 0;
		}

		// Set thumbnail
		if ( ! empty( $api_event->event_image ) ) {
			$image_util = new ImageUtil();
			$image_util->set_post_thumbnail( $event_id, $api_event->event_image );
		}

		// Update meta
		update_post_meta( $event_id, 'vdm_id', $api_event->event_id );

		update_post_meta( $event_id, 'short_text', $api_event->event_short_text ?? null );

		do_action( 'll_vdm_after_insert_event', $event_id, $api_event );

		if ( isset( $api_event->sub_events ) && is_array( $api_event->sub_events ) ) {
			foreach ( $api_event->sub_events as $api_sub_event ) {
				$this->create_or_update_sub_event_object( $event_id, $api_sub_event );
			}
		}
	}

	/**
	 * Create or update sub event object from api sub event data
	 *
	 * @param int $event_id
	 * @param object $api_event
	 * @return integer
	 */
	public function create_or_update_sub_event_object( int $event_id, $api_sub_event ): int {
		$vdm_id       = (int) $api_sub_event->event_id ?? 0;
		$sub_event    = SubEvent::get_by_vdm_id( $vdm_id );
		$sub_event_id = 0;
		if ( $sub_event instanceof Event ) {
			$sub_event_id = $sub_event->get_id();
		}

		do_action( 'll_vdm_before_insert_sub_event', $event_id, $api_sub_event );

		$status = 'draft';
		if ( isset( $api_sub_event->event_status ) && $api_sub_event->event_status === 'pub' ) {
			$status = 'publish';
		}

		// Update post object
		$post_data = array(
			'ID'           => $sub_event_id,
			'post_status'  => $status,
			'post_type'    => SubEvent::$type,
			'post_title'   => $api_sub_event->event_name,
			'post_name'    => sanitize_title( $api_sub_event->event_name ),
			'post_content' => '<p>' . esc_html( $api_sub_event->event_text ) . '</p>',
		);
		$post_data = apply_filters( 'll_vdm_update_sub_event_post_data', $post_data, $api_sub_event, $event_id );

		$event_id   = wp_insert_post( $post_data );
		if ( ! $event_id || $event_id instanceof WP_Error ) {
			return 0;
		}

		// Set thumbnail
		if ( ! empty( $api_sub_event->event_image ) ) {
			$image_util = new ImageUtil();
			$image_util->set_post_thumbnail( $event_id, $api_sub_event->event_image );
		}

		// Update meta
		update_post_meta( $event_id, 'vdm_id', $api_sub_event->event_id );
		update_post_meta( $event_id, 'event_id', $event_id );
		update_post_meta( $event_id, 'vdm_event_id', $api_sub_event->event_main_id );
		update_post_meta( $event_id, 'vdm_location_id', $api_sub_event->location_id ?? null );

		update_post_meta( $event_id, 'short_text', $api_sub_event->event_short_text ?? null );
		update_post_meta( $event_id, 'url', $api_sub_event->event_url ?? null );

		$start_timestamp = null;
		$end_timestamp = null;
		$timezone = new DateTimeZone( 'Europe/Amsterdam' ); // Plugin uses Europe/Amsterdam timestamps
		if ( isset( $api_sub_event->event_date ) ) {
			if ( isset( $api_sub_event->event_time ) ) {
				$start_date = DateTime::createFromFormat( 'Y-m-d H:i:s', $api_sub_event->event_date . ' ' . $api_sub_event->event_time, $timezone );

				if ( $start_date instanceof DateTime ) {
					$start_timestamp = $start_date->getTimestamp();
				}
			}

			if ( isset( $api_sub_event->event_end ) ) {
				$end_date = DateTime::createFromFormat( 'Y-m-d H:i:s', $api_sub_event->event_date . ' ' . $api_sub_event->event_end, $timezone );

				if ( $end_date instanceof DateTime ) {
					$end_timestamp = $end_date->getTimestamp();
					if ( $end_timestamp < $start_timestamp ) {
						$end_date->modify('+1 day');
						$end_timestamp = $end_date->getTimestamp();
					}
				}
			}
		}

		update_post_meta( $event_id, 'start_date', $start_timestamp );
		update_post_meta( $event_id, 'end_date', $start_timestamp );
		update_post_meta( $event_id, 'rep', $api_sub_event->event_rep ?? null );
		update_post_meta( $event_id, 'max_tickets_per_order', $api_sub_event->event_free ?? null );
		update_post_meta( $event_id, 'location_name', $api_sub_event->location_name ?? null );

		do_action( 'll_vdm_after_insert_sub_event', $event_id, $api_sub_event );
	}
}
