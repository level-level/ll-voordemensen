<?php

namespace LevelLevel\VoorDeMensen\Admin\Settings\General\Fields;

class DomainName extends BaseField {

	protected const NAME = 'domain_name';

	public function get_name(): string {
		return self::PREFIX . self::NAME;
	}

	public function get_label(): string {
		return __( 'Domain name', 'll-vdm' );
	}

	public function get_description(): string {
		return __( 'If VoordeMensen is running on a private (sub)domain, enter it here - otherwise leave to the standard tickets.voordemensen.nl', 'll-vdm' );
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
	 * @return string The default URL.
	 */
	protected function get_default_value() {
		return 'tickets.voordemensen.nl';
	}

	public function render_field(): void {
		$setting = $this->get_value();
		?>

		<p><label for="<?php echo esc_attr( $this->get_name() ); ?>"><?php echo esc_html( $this->get_description() ); ?></label></p>

		<input type="text" id="<?php echo esc_attr( $this->get_name() ); ?>" name="<?php echo esc_attr( $this->get_name() ); ?>" class="regular-text" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">

		<?php
	}
}
