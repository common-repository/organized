<?php


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Organized_Dashboard' ) ) :

/**
 * The main class
 *
 * @since 1.0.0
 */
class Organized_Dashboard {

	/**
	 * Main constructor
	 *
	 * @since 1.0.0
	 *
	 */
	public function __construct() {

	}


	/**
	 * Setup the tabs
	 */
	public function get_tabs() {

	    $tabs = array(
			'dashboard'     => array(
				'title'  => __( 'Dashboard', 'organized' ),
			),
		);

		return apply_filters( 'organized_dashboard_tabs', $tabs );

	}

	/**
	 * Output the dashboard
	 */
	public function dashboard_page() {
		$tabs        	= $this->get_tabs();
		$first_tab      = array_keys( $tabs );
		$current_tab  	= ! empty( $_GET['tab'] ) ? sanitize_title( $_GET['tab'] ) : $first_tab[0];
		include_once( ORGANIZED_DIR . '/templates/dashboard.php' );
	}




}

endif;