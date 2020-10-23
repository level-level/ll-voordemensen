<?php

namespace LevelLevel\VoorDeMensen\Admin\Settings\General;

use LevelLevel\VoorDeMensen\Admin\Settings\General\Fields\ClientName;
use LevelLevel\VoorDeMensen\Admin\Settings\General\Fields\PostTypes;

class Settings {
	public const FIELDS = array(
		ClientName::class,
		PostTypes::class,
	);

	public function register_hooks(): void {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'register_fields' ) );
	}

	public function register_settings(): void {
		foreach ( self::FIELDS as $setting_field_class ) {
			$setting_field = new $setting_field_class();
			$args          = array(
				'description'  => $setting_field->get_description(),
				'show_in_rest' => false,
			);
			register_setting( 'll_vdm_options', $setting_field->get_name(), $args );
		}
	}

	public function register_fields(): void {
		foreach ( self::FIELDS as $setting_field_class ) {
			$setting_field = new $setting_field_class();
			add_settings_field(
				$setting_field->get_name(),
				$setting_field->get_label(),
				array( $setting_field, 'render_field' ),
				'll_vdm_options',
				Section::NAME,
				array(
					'label_for' => $setting_field->get_name(),
					'class'     => $setting_field->get_name() . '_field',
				)
			);
		}
	}
}
