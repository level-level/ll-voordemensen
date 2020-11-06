<?php

namespace LevelLevel\VoorDeMensen\Taxonomies;

use WP_REST_Terms_Controller;

class EventType {

	public const TAXONOMY_NAME = 'll_vdm_event_type';
	protected const POST_TYPES = array( 'll_vdm_sub_event' );

	public function register_hooks(): void {
		add_action( 'init', array( $this, 'register' ), 9 );
	}

	public function register(): void {
		$args = array(
			'labels'                => array(
				'name'                  => __( 'FAQ Locations', 'll-vdm' ),
				'singular_name'         => __( 'FAQ location', 'll-vdm' ),
				'all_items'             => __( 'Alle FAQ locations', 'll-vdm' ),
				'archives'              => __( 'FAQ location archieven', 'll-vdm' ),
				'attributes'            => __( 'FAQ location attributen', 'll-vdm' ),
				'insert_into_item'      => __( 'Voeg in FAQ location', 'll-vdm' ),
				'uploaded_to_this_item' => __( 'Geupload naar deze FAQ location', 'll-vdm' ),
				'filter_items_list'     => __( 'Filter FAQ locations lijst', 'll-vdm' ),
				'items_list_navigation' => __( 'FAQ Locations lijst navigatie', 'll-vdm' ),
				'items_list'            => __( 'FAQ Locations lijst', 'll-vdm' ),
				'new_item'              => __( 'Nieuwe FAQ location', 'll-vdm' ),
				'add_new'               => __( 'Voeg nieuwe toe', 'll-vdm' ),
				'add_new_item'          => __( 'Voeg nieuw FAQ location toe', 'll-vdm' ),
				'edit_item'             => __( 'Bewerk FAQ location', 'll-vdm' ),
				'view_item'             => __( 'Bekijk FAQ location', 'll-vdm' ),
				'view_items'            => __( 'Bekijk FAQ locations', 'll-vdm' ),
				'search_items'          => __( 'Zoek FAQ locations', 'll-vdm' ),
				'not_found'             => __( 'Geen FAQ locations gevonden', 'll-vdm' ),
				'not_found_in_trash'    => __( 'Geen FAQ locations gevonden in prullenbak', 'll-vdm' ),
				'parent_item_colon'     => __( 'Hoofd FAQ location:', 'll-vdm' ),
				'menu_name'             => __( 'FAQ location', 'll-vdm' ),
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
