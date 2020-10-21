<?php

namespace LevelLevel\VoorDeMensen\Admin\Settings;

class Fields {

	public function register_hooks(): void {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'register_fields' ) );
	}

	public function register_settings() {
		register_setting( 'll_vdm_options', 'll_vdm_api_client_name' );
		register_setting( 'll_vdm_options', 'll_vdm_display_ticket_sales_screen_type' );
	}

	public function register_fields() {
		// API settings
		add_settings_field(
			'll_vdm_api_client_name',
			__( 'Client name', 'll-vdm' ),
			array( $this, 'render_api_client_name_field' ),
			'll_vdm_options',
			'll_vdm_options_api'
		);

		// Display settings
		add_settings_field(
			'll_vdm_display_ticket_sales_screen_type',
			__( 'Ticket sales screen type', 'll-vdm' ),
			array( $this, 'render_display_ticket_sales_screen_type_field' ),
			'll_vdm_options',
			'll_vdm_options_display'
		);
	}

	public function render_api_client_name_field() {
		$setting = get_option( 'll_vdm_api_client_name' );
		?>

		<input type="text" name="ll_vdm_api_client_name" class="regular-text" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">

		<?php
	}

	public function render_display_ticket_sales_screen_type_field() {
		$setting = get_option( 'll_vdm_display_ticket_sales_screen_type' );

		$options = array(
			'popup' => __( 'Popup', 'll-vdm' ),
			'side'  => __( 'Side', 'll-vdm' ),
		);
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
