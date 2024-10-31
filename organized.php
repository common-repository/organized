<?php
/**
 * Plugin Name: Organized
 * Description: Get Organized
 * Author: organized
 * Version: 1.0.1
 * Text Domain: 'organized'
 * Domain Path: languages
 *
 */



// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Helper function for quick debugging
 */
if (!function_exists('pp')) {
	function pp( $array ) {
		echo '<pre style="white-space:pre-wrap;">';
			print_r( $array );
		echo '</pre>';
	}
}

/**
 * Main Class.
 *
 * @since 1.0.0
 */
final class Organized {

	/**
	 * @var The one true instance
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	public $version = '1.0.0';

	/**
	 * Main Instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'organized' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class.
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'organized' ), '1.0.0' );
	}

	/**
	 * 
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->define_constants();
		$this->includes();
		$this->localisation();
		$this->init();

		do_action( 'organized_loaded' );
	}

	/**
	 * Define Constants.
	 * @since  1.0.0
	 */
	private function define_constants() {
		$this->define( 'ORGANIZED_DIR',plugin_dir_path( __FILE__ ) );
		$this->define( 'ORGANIZED_URL',plugin_dir_url( __FILE__ ) );
		$this->define( 'ORGANIZED_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'ORGANIZED_VERSION', $this->version );
	}

	/**
	 * Define constant if not already set.
	 * @since  1.0.0
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}


	/**
	 * Include required files.
	 * @since  1.0.0
	 */
	public function includes() {
		include_once( 'includes/class-setup.php' );
		include_once( 'includes/class-post-types.php' );
		include_once( 'includes/class-menus.php' );
		include_once( 'includes/class-settings-api.php' );
		include_once( 'includes/class-settings.php' );
		
		include_once( 'includes/class-fields.php' );
		include_once( 'includes/class-todo.php' );
		include_once( 'includes/class-thing.php' );
		include_once( 'includes/class-dashboard.php' );
		include_once( 'includes/functions.php' );

	}

	public function init() {
		// Before init action.
		do_action( 'before_organized_init' );

		// Load class instances.
		$this->settings 	= new Organized_Settings();
		$this->dashboard 	= new Organized_Dashboard();
		$this->thing 		= new Organized_Thing();
		$this->todo 		= new Organized_Todo();
		$this->fields 		= new Organized_Fields();

		// Init action.
		do_action( 'organized_init' );
	}

	/**
	 * Load Localisation files.
	 * @since  1.0.0
	 */
	public function localisation() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'organized' );

		load_textdomain( 'organized', WP_LANG_DIR . '/organized/organized-' . $locale . '.mo' );
		load_plugin_textdomain( 'organized', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}


}


/**
 * Run the plugin.
 */
function organized() {
	return Organized::instance();
}
organized();