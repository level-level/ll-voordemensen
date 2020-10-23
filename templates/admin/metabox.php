<?php

/**
 * Template for the admin metabox dropdown field
 *
 * @var int $event_id
 * @var \LevelLevel\VoorDeMensen\Objects\Event[] $events
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
					<?php foreach ($events as $event) { ?>
						<option value="<?php echo esc_attr( $event->get_id() ) ?>" <?php selected( $event->get_id(), $event_id, true ); ?>>
							<?php echo esc_html( $event->get_vdm_id() . ' | ' . $event->get_title() ); ?>
						</option>
					<?php } ?>
				</select>
			</td>
		</tr>
	</tbody>
</table>
<?php  wp_nonce_field( 'll_vdm_metabox', 'll_vdm_nonce' ); ?>

