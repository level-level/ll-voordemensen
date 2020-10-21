<?php
/**
 * Plugin Name:  VoordeMensen
 * Version:      0.0.1
 * Description:  Unofficial plugin to access the VoordeMensen ticket platform directly from WordPress
 * Author:       Level Level
 * Author URI:   https://www.level-level.com
 *
 * Text Domain: ll-vdm
 * Domain Path: /languages/
 */

add_action(
	'plugins_loaded',
	function() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$plugin_path = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR;
		$plugin_data = get_plugin_data( __FILE__ );
		define( 'LL_VDM_PLUGIN_VERSION', isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : '' );
		define( 'LL_VDM_PLUGIN_PATH', $plugin_path );

		$autoload_file = $plugin_path . 'vendor/autoload.php';

		if ( file_exists( $autoload_file ) ) {
			include_once $autoload_file;
		}

		// Register hooks
		( new LevelLevel\VoorDeMensen\Admin\Settings\Menu() )->register_hooks();
		( new LevelLevel\VoorDeMensen\Admin\Settings\Sections() )->register_hooks();
		( new LevelLevel\VoorDeMensen\Admin\Settings\Fields() )->register_hooks();

		// Load textdomain
		load_plugin_textdomain( 'll-vdm', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
);
