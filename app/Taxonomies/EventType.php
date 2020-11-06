<?php

namespace LevelLevel\VoorDeMensen\Taxonomies;

use WP_REST_Terms_Controller;

class EventType {

	public const TAXONOMY_NAME = 'll_vdm_event_type';
	protected const POST_TYPES = array( 'll_vdm_event', 'll_vdm_sub_event' );

	public function register_hooks(): void {
		add_action( 'init', array( $this, 'register' ), 9 );
	}

	public function register(): void {
		$args = array(
			'labels'                => array(
				'name'                       => _x( 'Event types', 'taxonomy general name', 'll-vdm' ),
				'singular_name'              => _x( 'Event types', 'taxonomy singular name', 'll-vdm' ),
				'menu_name'                  => __( 'Event types', 'll-vdm' ),
				'all_items'                  => __( 'All Event types', 'll-vdm' ),
				'edit_item'                  => __( 'Edit Event type', 'll-vdm' ),
				'view_item'                  => __( 'View Event type', 'll-vdm' ),
				'update_item'                => __( 'Update Event type', 'll-vdm' ),
				'add_new_item'               => __( 'Add New Event type', 'll-vdm' ),
				'new_item_name'              => __( 'New Event type Name', 'll-vdm' ),
				'parent_item'                => __( 'Parent Event type', 'll-vdm' ),
				'parent_item_colon'          => __( 'Parent Event type:', 'll-vdm' ),
				'search_items'               => __( 'Search Event types', 'll-vdm' ),
				'popular_items'              => __( 'Popular Event types', 'll-vdm' ),
				'separate_items_with_commas' => __( 'Separate event types with commas', 'll-vdm' ),
				'add_or_remove_items'        => __( 'Add or remove event types', 'll-vdm' ),
				'choose_from_most_used'      => __( 'Choose from the most used event types', 'll-vdm' ),
				'not_found'                  => __( 'No event types found.', 'll-vdm' ),
				'back_to_items'              => __( 'Back to event types', 'll-vdm' ),
			),
			'public'                => false,
			'show_in_rest'          => true,
			'hierarchical'          => false,
			'show_in_nav_menus'     => false,
			'show_ui'               => false,
			'show_admin_column'     => false,
			'query_var'             => false,
			'rewrite'               => self::TAXONOMY_NAME,
			'rest_base'             => 'll_vdm_event_types',
			'rest_controller_class' => WP_REST_Terms_Controller::class,
		);
		$args = apply_filters( self::TAXONOMY_NAME . '_taxonomy_args', $args );
		register_taxonomy( self::TAXONOMY_NAME, self::POST_TYPES, $args );

		foreach ( self::POST_TYPES as $post_type ) {
			register_taxonomy_for_object_type( self::TAXONOMY_NAME, $post_type );
		}
	}
}
