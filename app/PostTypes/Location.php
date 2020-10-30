<?php

namespace LevelLevel\VoorDeMensen\PostTypes;

use WP_REST_Controller;

class Location {

	public const POST_TYPE_NAME = 'll_vdm_location';

	public function register_hooks(): void {
		add_action( 'init', array( $this, 'register' ), 9 );
	}

	public function register(): void {
		$args = array(
			'labels'                => array(
				'name'                  => __( 'Locations', 'll-vdm' ),
				'singular_name'         => __( 'Location', 'll-vdm' ),
				'all_items'             => __( 'All Locations', 'll-vdm' ),
				'archives'              => __( 'Location Archives', 'll-vdm' ),
				'attributes'            => __( 'Location Attributes', 'll-vdm' ),
				'insert_into_item'      => __( 'Insert into location', 'll-vdm' ),
				'uploaded_to_this_item' => __( 'Uploaded to this location', 'll-vdm' ),
				'featured_image'        => _x( 'Featured Image', 'location', 'll-vdm' ),
				'set_featured_image'    => _x( 'Set featured image', 'location', 'll-vdm' ),
				'remove_featured_image' => _x( 'Remove featured image', 'location', 'll-vdm' ),
				'use_featured_image'    => _x( 'Use as featured image', 'location', 'll-vdm' ),
				'filter_items_list'     => __( 'Filter locations list', 'll-vdm' ),
				'items_list_navigation' => __( 'Locations list navigation', 'll-vdm' ),
				'items_list'            => __( 'Locations list', 'll-vdm' ),
				'new_item'              => __( 'New Location', 'll-vdm' ),
				'add_new'               => __( 'Add New', 'll-vdm' ),
				'add_new_item'          => __( 'Add New Location', 'll-vdm' ),
				'edit_item'             => __( 'Edit Location', 'll-vdm' ),
				'view_item'             => __( 'View Location', 'll-vdm' ),
				'view_items'            => __( 'View Locations', 'll-vdm' ),
				'search_items'          => __( 'Search locations', 'll-vdm' ),
				'not_found'             => __( 'No locations found', 'll-vdm' ),
				'not_found_in_trash'    => __( 'No locations found in trash', 'll-vdm' ),
				'parent_item_colon'     => __( 'Parent Location:', 'll-vdm' ),
				'menu_name'             => __( 'Locations', 'll-vdm' ),
			),
			'public'                => false,
			'hierarchical'          => false,
			'show_ui'               => false,
			'show_in_nav_menus'     => false,
			'supports'              => array( 'title', 'custom-fields', 'editor', 'thumbnail' ),
			'has_archive'           => false,
			'query_var'             => false,
			'menu_icon'             => 'dashicons-id',
			'show_in_rest'          => true,
			'rest_base'             => 'll_vdm_locations',
			'rest_controller_class' => WP_REST_Controller::class,
		);
		$args = apply_filters( self::POST_TYPE_NAME . '_post_type_args', $args );
		register_post_type( self::POST_TYPE_NAME, $args );
	}
}
