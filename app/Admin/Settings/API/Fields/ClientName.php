<?php

namespace LevelLevel\VoorDeMensen\Admin\Settings\API\Fields;

class ClientName extends BaseField {

	protected const NAME = 'client_name';

	public function get_name(): string {
		return self::PREFIX . self::NAME;
	}

	public function get_label(): string {
		return __( 'Client name', 'll-vdm' );
	}

	public function get_value(): ?string {
		$default = $this->get_default_value();
		$value   = (string) get_option( $this->get_name(), $default );
		if ( empty( $value ) ) {
			return $default;
		}
		return $value;
	}

	/**
	 * Get default value
	 *
	 * @return null
	 */
	protected function get_default_value() {
		return null;
	}

	public function render_field(): void {
		$setting = $this->get_value();
		?>

		<input type="text" name="ll_vdm_api_client_name" class="regular-text" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">

		<?php
	}
}
