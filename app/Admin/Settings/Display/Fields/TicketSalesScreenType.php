<?php

namespace LevelLevel\VoorDeMensen\Admin\Settings\Display\Fields;

class TicketSalesScreenType extends BaseField {

	protected const NAME = 'ticket_sales_screen_type';

	public function get_name(): string {
		return self::PREFIX . self::NAME;
	}

	public function get_label(): string {
		return __( 'Ticket sales screen type', 'll-vdm' );
	}

	public function get_value(): ?string {
		$default = $this->get_default_value();
		$value   = (string) get_option( $this->get_name(), $default );
		if ( empty( $value ) ) {
			return $default;
		}
		return $value;
	}

	public function get_default_value(): string {
		return 'popup';
	}

	public function get_options(): array {
		return array(
			'popup' => __( 'Popup', 'll-vdm' ),
			'side'  => __( 'Side', 'll-vdm' ),
		);
	}

	public function render_field(): void {
		$options = $this->get_options();

		$setting = get_option( 'll_vdm_display_ticket_sales_screen_type', 'popup' );
		?>

		<select id="ll_vdm_display_ticket_sales_screen_type" name="ll_vdm_display_ticket_sales_screen_type">
			<?php foreach ( $options as $option_name => $option_label ) { ?>
				<option value="<?php echo esc_attr( $option_name ); ?>" <?php selected( $option_name, $setting, true ); ?>>
					<?php echo esc_html( $option_label ); ?>
				</option>
			<?php } ?>
		</select>
		<label for="ll_vdm_display_ticket_sales_screen_type">
			<?php esc_html_e( 'Choose how the ticket sales are shown on your site. With a popup overlay, or on the side of the screen.', 'll-vdm' ); ?>
		</label>

		<?php
	}
}
