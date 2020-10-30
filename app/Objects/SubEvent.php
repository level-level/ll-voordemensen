<?php

namespace LevelLevel\VoorDeMensen\Objects;

use DateTime;
use DateTimeZone;

class SubEvent extends BaseObject {
	public static $type = 'll_vdm_sub_event';

	/**
	 * Get object by vdm ID
	 *
	 * @param array $args
	 * @return static|null
	 */
	public static function get_by_vdm_id( string $vdm_id, array $args = array() ) {
		$default_args = array(
			'post_status' => 'any',
			'meta_query'  => array(
				array(
					'key'   => 'vdm_id',
					'value' => $vdm_id,
				),
			),
		);
		$args         = wp_parse_args( $args, $default_args );
		return static::get_one( $args );
	}

	public static function get_many_by_event_id( int $event_id, array $args = array() ): array {
		$default_args = array(
			'post_status' => 'any',
			'meta_query'  => array(
				array(
					'key'   => 'event_id',
					'value' => $event_id,
				),
			),
		);
		$args         = wp_parse_args( $args, $default_args );
		return static::get_many( $args );
	}

	public function get_event_id(): int {
		return (int) $this->get_meta( 'event_id', true );
	}

	public function get_event(): ?Event {
		return Event::get( $this->get_event_id() );
	}

	public function get_vdm_event_id(): ?string {
		$vdm_event_id = (string) $this->get_meta( 'vdm_event_id', true );
		if ( empty( $vdm_event_id ) ) {
			return null;
		}
		return $vdm_event_id;
	}

	public function get_vdm_location_id(): ?string {
		$vdm_location_id = (string) $this->get_meta( 'vdm_location_id', true );
		if ( empty( $vdm_location_id ) ) {
			return null;
		}
		return $vdm_location_id;
	}

	public function get_vdm_status(): ?string {
		$status = $this->get_meta( 'vdm_status', true );
		if ( empty( $status ) ) {
			return null;
		}
		return (string) $status;
	}

	public function get_url(): ?string {
		$url = $this->get_meta( 'url', true );
		if ( empty( $url ) ) {
			return null;
		}
		return (string) $url;
	}

	/**
	 * Get start date
	 *
	 * @return DateTime|null
	 */
	public function get_start_date(): ?DateTime {
		$timestamp = (int) $this->get_meta( 'start_date', true );
		if ( ! $timestamp ) {
			return null;
		}
		$date = DateTime::createFromFormat( 'U', (string) $timestamp );
		if ( $date instanceof DateTime ) {
			$timezone = (string) get_option( 'timezone_string' ) ?: null;
			if ( ! empty( $timezone ) ) {
				$datetimezone = new DateTimeZone( $timezone );
				$date->setTimezone( $datetimezone );
			}
			return $date;
		}
		return null;
	}

	/**
	 * Get end date
	 *
	 * @return DateTime|null
	 */
	public function get_end_date(): ?DateTime {
		$timestamp = (int) $this->get_meta( 'end_date', true );
		if ( ! $timestamp ) {
			return null;
		}
		$date = DateTime::createFromFormat( 'U', (string) $timestamp );
		if ( $date instanceof DateTime ) {
			$timezone = (string) get_option( 'timezone_string' ) ?: null;
			if ( ! empty( $timezone ) ) {
				$datetimezone = new DateTimeZone( $timezone );
				$date->setTimezone( $datetimezone );
			}
			return $date;
		}
		return null;
	}

	public function get_rep(): ?string {
		$rep = $this->get_meta( 'rep', true );
		if ( empty( $rep ) ) {
			return null;
		}
		return (string) $rep;
	}

	public function get_max_tickets_per_order(): ?int {
		$max = $this->get_meta( 'max_tickets_per_order', true );
		if ( ! is_numeric( $max ) ) {
			return null;
		}
		return (int) $max;
	}

	public function get_location_id(): int {
		return (int) $this->get_meta( 'location_id', true );
	}

	public function get_location(): ?Location {
		return Location::get( $this->get_location_id() );
	}

	/**
	 * Get sub events
	 *
	 * @param array $args
	 * @return TicketType[]
	 */
	public function get_ticket_types( $args = array() ): array {
		$default_args = array(
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'   => 'sub_event_id',
					'value' => $this->get_id(),
				),
			),
		);
		$args         = wp_parse_args( $args, $default_args );
		return TicketType::get_many( $args );
	}
}
