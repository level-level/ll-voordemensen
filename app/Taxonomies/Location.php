<?php

namespace LevelLevel\VoorDeMensen\Taxonomies;

use WP_REST_Terms_Controller;

class Location {

	public const TAXONOMY_NAME = 'll_vdm_location';
	protected const POST_TYPES = array( 'll_vdm_sub_event' );

	public function register_hooks(): void {
		add_action( 'init', array( $this, 'register' ), 9 );
	}

	public function register(): void {
		$args = array(
			'labels'                => array(
				'name'                       => _x( 'Locations', 'taxonomy general name', 'll-vdm' ),
				'singular_name'              => _x( 'Locations', 'taxonomy singular name', 'll-vdm' ),
				'menu_name'                  => __( 'Locations', 'll-vdm' ),
				'all_items'                  => __( 'All Locations', 'll-vdm' ),
				'edit_item'                  => __( 'Edit Location', 'll-vdm' ),
				'view_item'                  => __( 'View Location', 'll-vdm' ),
				'update_item'                => __( 'Update Location', 'll-vdm' ),
				'add_new_item'               => __( 'Add New Location', 'll-vdm' ),
				'new_item_name'              => __( 'New Location Name', 'll-vdm' ),
				'parent_item'                => __( 'Parent Location', 'll-vdm' ),
				'parent_item_colon'          => __( 'Parent Location:', 'll-vdm' ),
				'search_items'               => __( 'Search Locations', 'll-vdm' ),
				'popular_items'              => __( 'Popular Locations', 'll-vdm' ),
				'separate_items_with_commas' => __( 'Separate locations with commas', 'll-vdm' ),
				'add_or_remove_items'        => __( 'Add or remove locations', 'll-vdm' ),
				'choose_from_most_used'      => __( 'Choose from the most used locations', 'll-vdm' ),
				'not_found'                  => __( 'No locations found.', 'll-vdm' ),
				'back_to_items'              => __( 'Back to locations', 'll-vdm' ),
			),
			'public'                => false,
			'show_in_rest'          => true,
			'hierarchical'          => false,
			'show_in_nav_menus'     => false,
			'show_ui'               => false,
			'show_admin_column'     => false,
			'query_var'             => false,
			'rewrite'               => self::TAXONOMY_NAME,
			'rest_base'             => 'll_vdm_locations',
			'rest_controller_class' => WP_REST_Terms_Controller::class,
		);
		register_taxonomy( self::TAXONOMY_NAME, self::POST_TYPES, $args );

		foreach ( self::POST_TYPES as $post_type ) {
			register_taxonomy_for_object_type( self::TAXONOMY_NAME, $post_type );
		}
	}
}
