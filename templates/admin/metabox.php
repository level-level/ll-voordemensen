<?php

/**
 * Template for the admin metabox dropdown field
 *
 * @var int $event_vdm_id
 * @var array $api_events
 */

?>

<table class="form-table">
	<tbody>
		<tr>
			<th>
				<label for="ll-vdm-event-id"><?php esc_html_e( 'Event', 'll-vdm' ) ?></label>
			</th>
			<td>
				<select name="ll_vdm_event_id" id="ll-vdm-event-id" >
					<option value=""><?php echo esc_html_e( 'Select a VoordeMensen event', 'll-vdm' ); ?></option>
					<?php foreach ($api_events as $api_event) { ?>
						<option value="<?php echo esc_attr( $api_event->event_id ) ?>" <?php selected( (int) $api_event->event_id, $event_vdm_id, true ); ?>>
							<?php echo esc_html( $api_event->event_id . ' | ' . $api_event->event_name ); ?>
						</option>
					<?php } ?>
				</select>
			</td>
		</tr>
	</tbody>
</table>
<?php  wp_nonce_field( 'll_vdm_metabox', 'll_vdm_metabox_nonce' ); ?>

