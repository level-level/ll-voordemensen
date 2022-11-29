<?php

namespace LevelLevel\VoorDeMensen\Objects;

class TicketType extends BaseObject {
	public static $type = 'll_vdm_ticket_type';

	public static function get_many_by_sub_event_id( int $sub_event_id, array $args = array() ): array {
		$default_args = array(
			'post_status' => 'any',
			'meta_query'  => array(
				array(
					'key'   => 'll_vdm_sub_event_id',
					'value' => $sub_event_id,
				),
			),
		);
		$args         = wp_parse_args( $args, $default_args );
		return static::get_many( $args );
	}

	/**
	 * Get object by vdm ID
	 *
	 * @param array $args
	 * @return static|null
	 */
	public static function get_by_vdm_id_and_sub_event_id( string $vdm_id, int $sub_event_id, array $args = array() ) {
		$default_args = array(
			'post_status' => 'any',
			'meta_query'  => array(
				array(
					'key'   => 'll_vdm_vdm_id',
					'value' => $vdm_id,
				),
				array(
					'key'   => 'll_vdm_sub_event_id',
					'value' => $sub_event_id,
				),
			),
		);
		$args         = wp_parse_args( $args, $default_args );
		return static::get_one( $args );
	}

	public function get_sub_event_id(): int {
		return (int) $this->get_meta( 'll_vdm_sub_event_id', true );
	}

	public function get_sub_event(): ?SubEvent {
		return SubEvent::get( $this->get_sub_event_id() );
	}

	public function get_base_price(): float {
		return (float) $this->get_meta( 'll_vdm_base_price', true );
	}

	public function get_discount_type(): string {
		return (string) $this->get_meta( 'll_vdm_discount_type', true );
	}

	public function get_discount_value(): float {
		return (float) $this->get_meta( 'll_vdm_discount_value', true );
	}

	public function get_discounted_price(): float {
		return (float) $this->get_meta( 'll_vdm_discounted_price', true );
	}

	public function get_discount(): float {
		return $this->get_base_price() - $this->get_discounted_price();
	}

	public function get_price(): float {
		return $this->get_discounted_price();
	}
}
