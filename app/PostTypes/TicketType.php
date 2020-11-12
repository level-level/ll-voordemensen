<?php

namespace LevelLevel\VoorDeMensen\PostTypes;

use WP_REST_Posts_Controller;

class TicketType {

	public const POST_TYPE_NAME = 'll_vdm_ticket_type';

	public function register_hooks(): void {
		add_action( 'init', array( $this, 'register' ), 9 );
	}

	public function register(): void {
		$args = array(
			'labels'                => array(
				'name'                  => __( 'Ticket types', 'll-vdm' ),
				'singular_name'         => __( 'Ticket type', 'll-vdm' ),
				'all_items'             => __( 'All Ticket types', 'll-vdm' ),
				'archives'              => __( 'Ticket type Archives', 'll-vdm' ),
				'attributes'            => __( 'Ticket type Attributes', 'll-vdm' ),
				'insert_into_item'      => __( 'Insert into ticket type', 'll-vdm' ),
				'uploaded_to_this_item' => __( 'Uploaded to this ticket type', 'll-vdm' ),
				'featured_image'        => _x( 'Featured Image', 'ticket type', 'll-vdm' ),
				'set_featured_image'    => _x( 'Set featured image', 'ticket type', 'll-vdm' ),
				'remove_featured_image' => _x( 'Remove featured image', 'ticket type', 'll-vdm' ),
				'use_featured_image'    => _x( 'Use as featured image', 'ticket type', 'll-vdm' ),
				'filter_items_list'     => __( 'Filter ticket types list', 'll-vdm' ),
				'items_list_navigation' => __( 'Ticket types list navigation', 'll-vdm' ),
				'items_list'            => __( 'Ticket types list', 'll-vdm' ),
				'new_item'              => __( 'New Ticket type', 'll-vdm' ),
				'add_new'               => __( 'Add New', 'll-vdm' ),
				'add_new_item'          => __( 'Add New Ticket type', 'll-vdm' ),
				'edit_item'             => __( 'Edit Ticket type', 'll-vdm' ),
				'view_item'             => __( 'View Ticket type', 'll-vdm' ),
				'view_items'            => __( 'View Ticket types', 'll-vdm' ),
				'search_items'          => __( 'Search ticket types', 'll-vdm' ),
				'not_found'             => __( 'No ticket types found', 'll-vdm' ),
				'not_found_in_trash'    => __( 'No ticket types found in trash', 'll-vdm' ),
				'parent_item_colon'     => __( 'Parent Ticket type:', 'll-vdm' ),
				'menu_name'             => __( 'Ticket types', 'll-vdm' ),
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
			'rest_base'             => 'll_vdm_ticket_types',
			'rest_controller_class' => WP_REST_Posts_Controller::class,
		);
		$args = apply_filters( self::POST_TYPE_NAME . '_post_type_args', $args );
		register_post_type( self::POST_TYPE_NAME, $args );
	}
}
