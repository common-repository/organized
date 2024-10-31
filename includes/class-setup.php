<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Organized_Setup', false ) ) :

/**
 * Organized_Setup Class.
 */
class Organized_Setup {

	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hook in to actions & filters
	 *
	 * @since 1.0.0
	 */
	public function hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_footer', array( $this, 'message_html' ) );
	}

	/**
	 * Init
	 */
	public function admin_styles() {
		$screen = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		$url 	= ORGANIZED_URL;
		$v 		= ORGANIZED_VERSION;
		if ( in_array( $screen_id, array( 'toplevel_page_organized' ) ) ) {
			
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'organized-ui', $url . 'assets/css/jquery-ui.min.css', $v );
			wp_enqueue_style( 'organized', $url . 'assets/css/organized.css', $v );

		}

	}

	/**
	 * Init
	 */
	public function admin_scripts() {
		$screen = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		$url 	= ORGANIZED_URL;
		$v 		= ORGANIZED_VERSION;
		if ( in_array( $screen_id, array( 'toplevel_page_organized' ) ) ) {

			wp_enqueue_media();
			wp_enqueue_script( 'jquery-ui-core' ); 
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-droppable' );
			wp_enqueue_script( 'jquery-ui-draggable' );
			wp_enqueue_script( 'jquery-ui-sortable' );

			wp_enqueue_script( 'organized-todo', $url . 'assets/js/organized-todo.js', array( 'jquery' ), $v, true );

			// our last script
			wp_enqueue_script( 'organized', $url . 'assets/js/organized.js', array( 
				'jquery', 
				'wp-color-picker',
				'jquery-ui-core',
				'jquery-ui-datepicker',
				'jquery-ui-droppable',
				'jquery-ui-draggable',
				'jquery-ui-sortable',
				'organized-todo',
				), $v, true );
			
			// js options and i18n
			$options = array( 
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'template_path' => $url . 'templates',
				'todo_button_confirm' => organized()->todo->button_confirm(),
				'todo_item_edit' => organized()->todo->item_edit(),
				'todo_item' => organized()->todo->item(),
				'todo_list' => organized()->todo->list_items(),
				'nonce' => wp_create_nonce( 'organized_nonce' ),
			);

			$i18n = array( 
				'todo_remove' => __( 'Sure?', 'organized' ),
				'todo_tooltip' => __( 'Click to edit', 'organized' ),
				'todo_placeholder' => __( 'Todo list', 'organized' ),
				'delete' => __( 'Delete', 'organized' ),
				'cancel' => __( 'Cancel', 'organized' ),
				'insert_file' => __( 'Insert File', 'organized' ),
				'edit_thing' => __( 'Edit Thing', 'organized' ),
				'add_thing' => __( 'Add Thing', 'organized' ),
			);

    		wp_localize_script( 'organized-todo', 'organized_plugin', array_merge( $options, $i18n ) );

		}
	}


	/**
	 * Message wrapper
	 */
	public function message_html() {
		?>

		<div class="organized-message" style="display:none"></div>

		<?php 
	}



}

endif;

return new Organized_Setup();
