<?php

namespace LevelLevel\VoorDeMensen\Sync;

class Setup {
	public function register_hooks(): void {
		add_filter( 'cron_schedules', array( $this, 'add_cron_schedules' ) );
	}

	public function add_cron_schedules( array $schedules ): array {
		if ( ! isset( $schedules['hourly'] ) ) {
			$schedules['hourly'] = array(
				'interval' => HOUR_IN_SECONDS,
				'display'  => __( 'Every hour', 'll-vdm' ),
			);
		}
		return $schedules;
	}
}
