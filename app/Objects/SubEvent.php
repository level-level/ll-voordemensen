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
					'key'   => 'll_vdm_vdm_id',
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
					'key'   => 'll_vdm_event_id',
					'value' => $event_id,
				),
			),
		);
		$args         = wp_parse_args( $args, $default_args );
		return static::get_many( $args );
	}

	public function get_event_id(): int {
		return (int) $this->get_meta( 'll_vdm_event_id', true );
	}

	public function get_event(): ?Event {
		return Event::get( $this->get_event_id() );
	}

	public function get_text(): string {
		return (string) $this->get_meta( 'll_vdm_text', true );
	}

	public function get_short_text(): string {
		return (string) $this->get_meta( 'll_vdm_short_text', true );
	}

	public function get_vdm_event_id(): ?string {
		$vdm_event_id = (string) $this->get_meta( 'll_vdm_vdm_event_id', true );
		if ( empty( $vdm_event_id ) ) {
			return null;
		}
		return $vdm_event_id;
	}

	public function get_vdm_location_id(): ?string {
		$vdm_location_id = (string) $this->get_meta( 'll_vdm_vdm_location_id', true );
		if ( empty( $vdm_location_id ) ) {
			return null;
		}
		return $vdm_location_id;
	}

	public function get_vdm_status(): ?string {
		$status = $this->get_meta( 'll_vdm_vdm_status', true );
		if ( empty( $status ) ) {
			return null;
		}
		return (string) $status;
	}

	public function get_url(): ?string {
		$url = $this->get_meta( 'll_vdm_url', true );
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
		$timestamp = (int) $this->get_meta( 'll_vdm_start_date', true );
		if ( ! $timestamp ) {
			return null;
		}
		
		$date = DateTime::createFromFormat( 'U', (string) $timestamp );
		if ( $date instanceof DateTime ) {
			$date->setTimezone( wp_timezone() );
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
		$timestamp = (int) $this->get_meta( 'll_vdm_end_date', true );
		if ( ! $timestamp ) {
			return null;
		}
		$date = DateTime::createFromFormat( 'U', (string) $timestamp );
		if ( $date instanceof DateTime ) {
			$date->setTimezone( wp_timezone() );
			return $date;
		}
		return null;
	}

	public function get_rep(): ?string {
		$rep = $this->get_meta( 'll_vdm_rep', true );
		if ( empty( $rep ) ) {
			return null;
		}
		return (string) $rep;
	}

	public function get_sale_enabled(): bool {
		return (bool) $this->get_meta( 'll_vdm_sale_enabled', true );
	}

	public function get_max_tickets_per_order(): ?int {
		$max = $this->get_meta( 'll_vdm_max_tickets_per_order', true );
		if ( ! is_numeric( $max ) ) {
			return null;
		}
		return (int) $max;
	}

	public function get_location(): ?Location {
		$terms = wp_get_post_terms( $this->get_id(), Location::$taxonomy );

		if ( $terms instanceof \WP_Error || empty( $terms ) ) {
			return null;
		}

		return new Location( $terms[0] );
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
					'key'   => 'll_vdm_sub_event_id',
					'value' => $this->get_id(),
				),
			),
		);
		$args         = wp_parse_args( $args, $default_args );
		return TicketType::get_many( $args );
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
}
