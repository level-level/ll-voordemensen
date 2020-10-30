<?php

namespace LevelLevel\VoorDeMensen\Sync;

use LevelLevel\VoorDeMensen\Admin\Settings\General\Fields\SyncEventsInterval as SyncEventsIntervalSetting;

abstract class BaseSync {

	protected const TRIGGER = 'll_vdm_sync_base';

	public function register_hooks(): void {
		add_action( static::TRIGGER, array( $this, 'sync_all' ) );
		$this->schedule_sync();
	}

	public function schedule_sync(): void {
		if ( ! wp_next_scheduled( static::TRIGGER ) ) {
			$schedule = ( new SyncEventsIntervalSetting() )->get_value();
			wp_schedule_event( time(), $schedule, static::TRIGGER );
		}
	}

	public function reschedule_sync(): void {
		$this->unschedule_sync();
		$this->schedule_sync();
	}

	protected function unschedule_sync(): void {
		if ( wp_next_scheduled( static::TRIGGER ) ) {
			wp_clear_scheduled_hook( static::TRIGGER );
		}
	}

	public function sync_all(): void {
		$this->sync();
	}

	abstract protected function sync( int $limit = null ): void;
}
