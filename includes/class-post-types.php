<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The main class
 *
 * @since 1.0.0
 */
class Organized_Post_Types {

	/**
	 * Main constructor
	 *
	 * @since 1.0.0
	 *
	 */
	public function __construct() {

		// Hook into actions & filters
		$this->hooks();

	}

	/**
	 * Hook in to actions & filters
	 *
	 * @since 1.0.0
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'register_thing' ) );
	}

	/**
	 * Registers and sets up the custom post types
	 *
	 * @since 1.0
	 * @return void
	 */
	public function register_thing() {

		$labels = apply_filters( 'organized_thing_labels', array(
			'name'                  => _x( '%2$s', 'thing post type name', 'organized' ),
			'singular_name'         => _x( '%1$s', 'singular thing post type name', 'organized' ),
			'add_new'               => __( 'New %1s', 'organized' ),
			'add_new_item'          => __( 'Add New %1$s', 'organized' ),
			'edit_item'             => __( 'Edit %1$s', 'organized' ),
			'new_item'              => __( 'New %1$s', 'organized' ),
			'all_items'             => __( '%2$s', 'organized' ),
			'view_item'             => __( 'View %1$s', 'organized' ),
			'search_items'          => __( 'Search %2$s', 'organized' ),
			'not_found'             => __( 'No %2$s found', 'organized' ),
			'not_found_in_trash'    => __( 'No %2$s found in Trash', 'organized' ),
			'parent_item_colon'     => '',
			'menu_name'             => _x( '%2$s', 'thing post type menu name', 'organized' ),
			'filter_items_list'     => __( 'Filter %2$s list', 'organized' ),
			'items_list_navigation' => __( '%2$s list navigation', 'organized' ),
			'items_list'            => __( '%2$s list', 'organized' ),
		) );

		foreach ( $labels as $key => $value ) {
			$labels[ $key ] = sprintf( $value, __( 'Thing', 'organized' ), __( 'Things', 'organized' ) );
		}

		$args = array(
			'labels'             	=> $labels,
			'public'             	=> false,
			'show_in_rest' 			=> false,
			'exclude_from_search'	=> true,
			'publicly_queryable' 	=> true,
			'show_ui'            	=> true,
			'show_in_menu'       	=> false, // we are using custom add_submenu_page
			'query_var'          	=> true,
			'capability_type'    	=> 'post',
			'map_meta_cap' 		 	=> true,
			'has_archive'        	=> false,
			'hierarchical'       	=> false,
			'supports'           	=> array( 'title', 'revisions', 'author' ),
		);

		register_post_type( 'organized-thing', apply_filters( 'organized_thing_post_type_args', $args ) );

	}


}

return new Organized_Post_Types();