<?php

if ( ! current_user_can( 'manage_options' ) ) {
	wp_safe_redirect( admin_url( 'admin.php' ) );
	exit;
}

// check if the user have submitted the settings
// wordpress will add the "settings-updated" $_GET parameter to the url
if ( isset( $_GET['settings-updated'] ) ) {
	// add settings saved message with the class of "updated"
	add_settings_error( 'vdm_messages', 'vdm_message', __( 'Opgeslagen', 'vdm' ), 'updated' );
}

// show error/update messages
	settings_errors( 'vdm_messages' );
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


