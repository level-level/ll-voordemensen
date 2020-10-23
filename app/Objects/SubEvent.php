<?php

namespace LevelLevel\VoorDeMensen\Objects;

use DateTime;
use DateTimeZone;

class SubEvent extends BaseObject {
	public static $type = 'll_vdm_sub_event';

	public function get_event_id(): int {
		return (int) $this->get_meta( 'event_id' );
	}

	public function get_event(): ?Event {
		return Event::get( $this->get_event_id() );
	}

	public function get_vdm_event_id(): int {
		return (int) $this->get_meta( 'vdm_event_id' );
	}

	public function get_vdm_location_id(): int {
		return (int) $this->get_meta( 'vdm_location_id' );
	}

	public function get_vdm_status(): ?string {
		$status = $this->get_meta( 'vdm_status' );
		if ( empty( $status ) ) {
			return null;
		}
		return (string) $status;
	}

	public function get_url(): ?string {
		$url = $this->get_meta( 'url' );
		if ( empty( $url ) ) {
			return null;
		}
		return (string) $url;
	}

	public function get_start_date(): ?DateTime {
		$timestamp = (int) $this->get_meta( 'start_date' );
		if ( ! $timestamp ) {
			return null;
		}
		$date = DateTime::createFromFormat( 'U', $timestamp );
		if ( $date instanceof DateTime ) {
			$timezone    = (string) get_option('timezone_string') ?: null;
			if ( ! empty( $timezone ) ) {
				$datetimezone = new DateTimeZone( $timezone );
				$date->setTimezone( $datetimezone );
			}
			return $date;
		}
		return null;
	}

	public function get_end_date(): ?DateTime {
		$timestamp = (int) $this->get_meta( 'end_date' );
		if ( ! $timestamp ) {
			return null;
		}
		$date = DateTime::createFromFormat( 'U', $timestamp );
		if ( $date instanceof DateTime ) {
			$timezone    = (string) get_option('timezone_string') ?: null;
			if ( ! empty( $timezone ) ) {
				$datetimezone = new DateTimeZone( $timezone );
				$date->setTimezone( $datetimezone );
			}
			return $date;
		}
		return null;
	}

	public function get_rep(): ?string {
		$rep = $this->get_meta( 'rep' );
		if ( empty( $rep ) ) {
			return null;
		}
		return (string) $rep;
	}

	public function get_max_tickets_per_order(): ?int {
		$max = $this->get_meta( 'max_tickets_per_order' );
		if ( ! is_numeric( $max ) ) {
			return null;
		}
		return (int) $max;
	}

	public function get_location_name(): ?string {
		$location = $this->get_meta( 'location_name' );
		if ( empty( $location ) ) {
			return null;
		}
		return (string) $location;
	}
}
