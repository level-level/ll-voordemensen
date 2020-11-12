<?php

namespace LevelLevel\VoorDeMensen\PostTypes;

use WP_REST_Posts_Controller;

class SubEvent {

	public const POST_TYPE_NAME = 'll_vdm_sub_event';

	public function register_hooks(): void {
		add_action( 'init', array( $this, 'register' ), 9 );
	}

	public function register(): void {
		$args = array(
			'labels'                => array(
				'name'                  => __( 'Sub events', 'll-vdm' ),
				'singular_name'         => __( 'Sub event', 'll-vdm' ),
				'all_items'             => __( 'All Sub events', 'll-vdm' ),
				'archives'              => __( 'Sub event Archives', 'll-vdm' ),
				'attributes'            => __( 'Sub event Attributes', 'll-vdm' ),
				'insert_into_item'      => __( 'Insert into sub event', 'll-vdm' ),
				'uploaded_to_this_item' => __( 'Uploaded to this sub event', 'll-vdm' ),
				'featured_image'        => _x( 'Featured Image', 'sub event', 'll-vdm' ),
				'set_featured_image'    => _x( 'Set featured image', 'sub event', 'll-vdm' ),
				'remove_featured_image' => _x( 'Remove featured image', 'sub event', 'll-vdm' ),
				'use_featured_image'    => _x( 'Use as featured image', 'sub event', 'll-vdm' ),
				'filter_items_list'     => __( 'Filter sub events list', 'll-vdm' ),
				'items_list_navigation' => __( 'Sub events list navigation', 'll-vdm' ),
				'items_list'            => __( 'Sub events list', 'll-vdm' ),
				'new_item'              => __( 'New Sub event', 'll-vdm' ),
				'add_new'               => __( 'Add New', 'll-vdm' ),
				'add_new_item'          => __( 'Add New Sub event', 'll-vdm' ),
				'edit_item'             => __( 'Edit Sub event', 'll-vdm' ),
				'view_item'             => __( 'View Sub event', 'll-vdm' ),
				'view_items'            => __( 'View Sub events', 'll-vdm' ),
				'search_items'          => __( 'Search sub events', 'll-vdm' ),
				'not_found'             => __( 'No sub events found', 'll-vdm' ),
				'not_found_in_trash'    => __( 'No sub events found in trash', 'll-vdm' ),
				'parent_item_colon'     => __( 'Parent Sub event:', 'll-vdm' ),
				'menu_name'             => __( 'Sub events', 'll-vdm' ),
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
			'rest_base'             => 'll_vdm_sub_events',
			'rest_controller_class' => WP_REST_Posts_Controller::class,
		);
		$args = apply_filters( self::POST_TYPE_NAME . '_post_type_args', $args );
		register_post_type( self::POST_TYPE_NAME, $args );
	}
}
