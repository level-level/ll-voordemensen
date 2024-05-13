<?php
/**
 * Plugin Name:  VoordeMensen
 * Version:      1.6.0
 * Description:  Unofficial plugin to access the VoordeMensen ticket platform directly from WordPress
 * Author:       Level Level
 * Author URI:   https://www.level-level.com
 *
 * Text Domain: ll-vdm
 * Domain Path: /languages/
 */

add_action(
	'plugins_loaded',
	function () {
		// Set plugin variables
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		$plugin_path = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR;
		$plugin_data = get_plugin_data( __FILE__ );
		$plugin_url  = plugin_dir_url( __FILE__ );
		define( 'LL_VDM_PLUGIN_VERSION', isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : '' );
		define( 'LL_VDM_PLUGIN_PATH', $plugin_path );
		define( 'LL_VDM_PLUGIN_URL', $plugin_url );
		define( 'LL_VDM_PLUGIN_NAMESPACE', 'LevelLevel\\VoorDeMensen\\' );

		// Autoload files
		$autoload_file = $plugin_path . 'vendor/autoload.php';
		if ( file_exists( $autoload_file ) ) {
			require_once $autoload_file;
		} else {
			/**
			 * Autoload using spl_autoload_register
			 * @see https://www.php.net/manual/en/language.oop5.autoload.php#120258
			 */
			$autoload_dir = LL_VDM_PLUGIN_PATH . 'app' . DIRECTORY_SEPARATOR;
			spl_autoload_register(
				function ( string $classname ) use ( $autoload_dir ): void {
					$no_plugin_ns_class = str_replace( LL_VDM_PLUGIN_NAMESPACE, '', $classname );
					if ( $no_plugin_ns_class === $classname ) {
						return; // Class not in plugin namespace, skip autoloading
					}

					$file = str_replace( '\\', DIRECTORY_SEPARATOR, $no_plugin_ns_class ) . '.php';
					$file = $autoload_dir . $file;
					if ( ! file_exists( $file ) ) {
						return;
					}

					// Require the file
					require_once $file;
				}
			);
		}

		// Register hooks
		( new LevelLevel\VoorDeMensen\Admin\MetaBox() )->register_hooks();
		( new LevelLevel\VoorDeMensen\Admin\Settings\Menu() )->register_hooks();
		( new LevelLevel\VoorDeMensen\Admin\Settings\QuickSyncEventsController() )->register_hooks();
		( new LevelLevel\VoorDeMensen\Admin\Settings\General\Section() )->register_hooks();
		( new LevelLevel\VoorDeMensen\Admin\Settings\General\Settings() )->register_hooks();
		( new LevelLevel\VoorDeMensen\Admin\Settings\Display\Section() )->register_hooks();
		( new LevelLevel\VoorDeMensen\Admin\Settings\Display\Settings() )->register_hooks();
		( new LevelLevel\VoorDeMensen\PostTypes\Event() )->register_hooks();
		( new LevelLevel\VoorDeMensen\PostTypes\SubEvent() )->register_hooks();
		( new LevelLevel\VoorDeMensen\PostTypes\TicketType() )->register_hooks();
		( new LevelLevel\VoorDeMensen\Sync\Setup() )->register_hooks();
		( new LevelLevel\VoorDeMensen\Sync\LocationsSync() )->register_hooks();
		( new LevelLevel\VoorDeMensen\Sync\EventTypesSync() )->register_hooks();
		( new LevelLevel\VoorDeMensen\Sync\EventsSync() )->register_hooks();
		( new LevelLevel\VoorDeMensen\Taxonomies\Location() )->register_hooks();
		( new LevelLevel\VoorDeMensen\Taxonomies\EventType() )->register_hooks();
		( new LevelLevel\VoorDeMensen\Assets() )->register_hooks();
		( new LevelLevel\VoorDeMensen\ShortCodes\Init() )->register_hooks();

		// Load textdomain
		load_plugin_textdomain( 'll-vdm', false, basename( __DIR__ ) . '/languages/' );
	}
);
