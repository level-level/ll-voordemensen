<?php

namespace LevelLevel\VoorDeMensen\PostTypes;

use WP_REST_Controller;

class Event {

	public const POST_TYPE_NAME = 'll_vdm_event';

	public function register_hooks(): void {
		add_action( 'init', array( $this, 'register' ), 9 );
	}

	public function register(): void {
		$args = array(
			'labels'                => array(
				'name'                  => __( 'Events', 'll-vdm' ),
				'singular_name'         => __( 'Event', 'll-vdm' ),
				'all_items'             => __( 'All Events', 'll-vdm' ),
				'archives'              => __( 'Event Archives', 'll-vdm' ),
				'attributes'            => __( 'Event Attributes', 'll-vdm' ),
				'insert_into_item'      => __( 'Insert into event', 'll-vdm' ),
				'uploaded_to_this_item' => __( 'Uploaded to this event', 'll-vdm' ),
				'featured_image'        => _x( 'Featured Image', 'event', 'll-vdm' ),
				'set_featured_image'    => _x( 'Set featured image', 'event', 'll-vdm' ),
				'remove_featured_image' => _x( 'Remove featured image', 'event', 'll-vdm' ),
				'use_featured_image'    => _x( 'Use as featured image', 'event', 'll-vdm' ),
				'filter_items_list'     => __( 'Filter events list', 'll-vdm' ),
				'items_list_navigation' => __( 'Events list navigation', 'll-vdm' ),
				'items_list'            => __( 'Events list', 'll-vdm' ),
				'new_item'              => __( 'New Event', 'll-vdm' ),
				'add_new'               => __( 'Add New', 'll-vdm' ),
				'add_new_item'          => __( 'Add New Event', 'll-vdm' ),
				'edit_item'             => __( 'Edit Event', 'll-vdm' ),
				'view_item'             => __( 'View Event', 'll-vdm' ),
				'view_items'            => __( 'View Events', 'll-vdm' ),
				'search_items'          => __( 'Search events', 'll-vdm' ),
				'not_found'             => __( 'No events found', 'll-vdm' ),
				'not_found_in_trash'    => __( 'No events found in trash', 'll-vdm' ),
				'parent_item_colon'     => __( 'Parent Event:', 'll-vdm' ),
				'menu_name'             => __( 'Events', 'll-vdm' ),
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
			'rest_base'             => 'll_vdm_events',
			'rest_controller_class' => WP_REST_Controller::class,
		);
		$args = apply_filters( self::POST_TYPE_NAME . '_post_type_args', $args );
		register_post_type( self::POST_TYPE_NAME, $args );
	}
}
