<?php

if ( ! current_user_can( 'manage_options' ) ) {
	wp_safe_redirect( admin_url( 'admin.php' ) );
	exit;
}

$is_updated = filter_input( INPUT_GET, 'settings-updated', FILTER_VALIDATE_BOOLEAN );
if ( $is_updated ) {
	add_settings_error( 'll_vdm_options', 'll-vdm-options-message', __( 'Settings saved', 'll-vdm' ), 'updated' );
}


settings_errors( 'll_vdm_options' );
?>

<div class="wrap">
	<h1>
		<?php echo esc_html( get_admin_page_title() ); ?>
	</h1>
	<form action="options.php" method="post">

	<?php
		settings_fields( 'll_vdm_options' );
		do_settings_sections( 'll_vdm_options' );
		submit_button();
	?>

	</form>
</div>


