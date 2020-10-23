<?php

use LevelLevel\VoorDeMensen\Admin\Settings\Menu;
use LevelLevel\VoorDeMensen\Sync\EventsSync;

if ( ! current_user_can( 'manage_options' ) ) {
	wp_safe_redirect( admin_url( 'admin.php' ) );
	exit;
}

$is_updated = filter_input( INPUT_GET, 'settings-updated', FILTER_VALIDATE_BOOLEAN );
if ( $is_updated ) {
	add_settings_error( Menu::NAME, 'll-vdm-options-updated', __( 'Settings saved', 'll-vdm' ), 'updated' );
}

$has_quick_synced_events = filter_input( INPUT_GET, 'll_vdm_quick_sync_events', FILTER_VALIDATE_BOOLEAN );
if ( $has_quick_synced_events ) {
	add_settings_error( Menu::NAME, 'll-vdm-quick-sync-events', sprintf( __( 'Synced latest %d events', 'll-vdm' ), EventsSync::RECENT_LIMIT ), 'updated' );
}

settings_errors( Menu::NAME );
?>

<div class="wrap">
	<h1>
		<?php echo esc_html( get_admin_page_title() ); ?>
	</h1>
	<form action="options.php" method="post">
		<?php
			settings_fields( Menu::NAME );
			do_settings_sections( Menu::NAME );
			submit_button();
		?>
	</form>

	<form action="<?php echo esc_attr( admin_url( 'admin-post.php' ) ); ?>" method="post">

		<h2>
			<?php echo esc_html_e( 'Manually sync events', 'll-vdm' ); ?>
		</h2>

		<p>
			<?php echo esc_html_e( 'Events are synced automatically in the background.', 'll-vdm' ); ?>
		</p>

		<p>
			<?php echo esc_html( sprintf( __( 'If you want to sync the latest %d events now, click the button below.', 'll-vdm' ), EventsSync::RECENT_LIMIT ) ); ?>
		</p>

		<p class="submit">
			<input class="button button" type="submit" value="<?php echo esc_attr( sprintf( __( 'Sync latest %d events', 'll-vdm' ), EventsSync::RECENT_LIMIT ) ); ?>">
		</p>

		<?php
			wp_nonce_field( 'll_vdm_quick_sync_events', 'll_vdm' );
		?>
		<input type="hidden" name="action" value="ll_vdm_quick_sync_events">
	</form>
</div>



