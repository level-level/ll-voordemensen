<?php

namespace LevelLevel\VoorDeMensen\Sync;

class Setup {
	public function register_hooks(): void {
		add_filter( 'cron_schedules', array( $this, 'add_cron_schedules' ) );
	}

	public function add_cron_schedules( array $schedules ): array {
		if ( ! isset( $schedules['every_15_min'] ) ) {
			$schedules['every_15_min'] = array(
				'interval' => MINUTE_IN_SECONDS * 15,
				'display'  => __( 'Once every 15 minutes', 'll-vdm' ),
			);
		}
		if ( ! isset( $schedules['hourly'] ) ) {
			$schedules['hourly'] = array(
				'interval' => HOUR_IN_SECONDS,
				'display'  => __( 'Once every hour', 'll-vdm' ),
			);
		}
		return $schedules;
	}
}
