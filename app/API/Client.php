<?php

namespace LevelLevel\VoorDeMensen\API;

use WP_Error;

/**
 * API client for VoordeMensen
 *
 * To test the integration, use 'demo' as client name
 */
class Client {
	protected const BASE_API_URL = 'https://api.voordemensen.nl/v1/';

	/**
	 * Get array of API event response objects
	 */
	public function get_events(): array {
		$url = $this->get_events_url();
		if ( ! $url ) {
			return array();
		}

		$response = $this->request( $url );
		if ( ! isset( $response['success'] ) || ! $response['success'] || ! is_array( $response['data'] ) ) {
			return array();
		}
		return $response['data'];
	}

	/**
	 * Get API event response object
	 */
	public function get_event( int $vdm_id ) {
		$url = $this->get_events_url( $vdm_id );
		if ( ! $url ) {
			return null;
		}

		$response = $this->request( $url );
		if ( ! isset( $response['success'] ) || ! $response['success'] || ! is_array( $response['data'] ) || empty( $response['data'] ) ) {
			return null;
		}
		return is_object( $response['data'][0] ) ? $response['data'][0] : null;
	}

	/**
	 * Perform an API request
	 */
	public function request( string $url, string $method = 'GET', array $request_args = array() ) {
		$default_args     = array(
			'method'  => $method,
			'timeout' => 5,
		);
		$request_args     = wp_parse_args( $request_args, $default_args );
		$response         = wp_remote_request( $url, $request_args );
		$status_code      = wp_remote_retrieve_response_code( $response );
		$json_string_body = wp_remote_retrieve_body( $response );
		$data             = json_decode( $json_string_body );

		$response = array(
			'success'      => ! $response instanceof WP_Error,
			'url'          => $url,
			'request_args' => $request_args,
			'status_code'  => $status_code,
			'wp_error'     => $response instanceof WP_Error ? $response : null,
			'data'         => $data,
		);

		return $response;
	}

	/**
	 * Get url for the events endpoint
	 */
	protected function get_events_url( int $vdm_id = null ): ?string {
		$client_name = get_option( 'll_vdm_api_client_name', null );
		if ( empty( $client_name ) ) {
			return null;
		}

		$url = self::BASE_API_URL . rawurldecode( $client_name ) . '/events/';
		if ( $vdm_id ) {
			$url .= $vdm_id;
		}

		return $url;
	}
}
