<?php

namespace LevelLevel\VoorDeMensen\Objects;

class Location extends BaseTerm {
	public static $taxonomy = 'll_vdm_location';

	public function get_address(): ?string {
		$address = $this->get_meta( 'll_vdm_address', true );
		if ( empty( $address ) ) {
			return null;
		}
		return (string) $address;
	}

	public function get_address_1(): ?string {
		$address_1 = $this->get_meta( 'll_vdm_address_1', true );
		if ( empty( $address_1 ) ) {
			return null;
		}
		return (string) $address_1;
	}

	public function get_zip_code(): ?string {
		$zip_code = $this->get_meta( 'll_vdm_zip_code', true );
		if ( empty( $zip_code ) ) {
			return null;
		}
		return (string) $zip_code;
	}

	public function get_city(): ?string {
		$city = $this->get_meta( 'll_vdm_city', true );
		if ( empty( $city ) ) {
			return null;
		}
		return (string) $city;
	}

	public function get_country(): ?string {
		$country = $this->get_meta( 'll_vdm_country', true );
		if ( empty( $country ) ) {
			return null;
		}
		return (string) $country;
	}

	public function get_phone(): ?string {
		$phone = $this->get_meta( 'll_vdm_phone', true );
		if ( empty( $phone ) ) {
			return null;
		}
		return (string) $phone;
	}
}
