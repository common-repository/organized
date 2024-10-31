<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Organized_Menus', false ) ) :

/**
 * Organized_Menus Class.
 */
class Organized_Menus {

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 9 );

	}

	/**
	 * Add menu items.
	 */
	public function admin_menu() {

		add_menu_page( __( 'Organized', 'organized' ), __( 'Organized', 'organized' ), 'manage_options', 'organized', null, 'dashicons-schedule', '55' );
		add_submenu_page( 'organized', __( 'Dashboard', 'organized' ),  __( 'Dashboard', 'organized' ) , 'manage_options', 'organized', array( organized()->dashboard, 'dashboard_page' ) );
		
		
		add_submenu_page( 
			'organized', 
			__( 'Settings', 'organized' ),  
			__( 'Settings', 'organized' ), 
			'manage_options', 
			'organized-settings', 
			array( organized()->settings, 'plugin_page' ) 
		);

	}


}

endif;

return new Organized_Menus();
