<?php


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Organized_Thing' ) ) :

/**
 * The main class
 *
 * @since 1.0.0
 */
class Organized_Thing {

	public $ajax_return = array();

	/**
	 * Main constructor
	 *
	 * @since 1.0.0
	 *
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hook in to actions & filters
	 *
	 * @since 1.0.0
	 */
	public function hooks() {
		add_action( 'wp_ajax_save_the_thing', array( $this, 'save_the_thing' ) );
		add_action( 'wp_ajax_edit_the_thing', array( $this, 'edit_the_thing' ) );
		add_action( 'wp_ajax_delete_the_thing', array( $this, 'delete_the_thing' ) );
		add_action( 'wp_ajax_update_things', array( $this, 'update_things' ) );
	}

	
	/**
	 * Init
	 */
	public function add_new() {

		?>

			<form name="post" action="" method="post" id="add-edit-thing">

				<div class="input-wrap">
					<?php 
					echo organized()->fields->text( 
						array(
							'name' => 'title',
							'id' => 'title',
							'tabindex' => '1',
							'placeholder' => __( 'Title', 'organized' ),
						) ); 
					?>
				</div>

				<div class="extras">

					<div class="input-wrap">
						<?php 
						echo organized()->fields->textarea( 
							array(
								'name' => 'notes',
								'id' => 'notes',
								'tabindex' => '2',
								'placeholder' => __( 'Notes', 'organized' ),
							) ); 
						?>
					</div>

					<div class="input-wrap">
						<div id="add-todo" style="width: 100%;"></div>
					</div>
					
					<div class="input-wrap">

						<div class="half">
							<?php 
							echo organized()->fields->text( 
								array(
									'name' => 'start_date',
									'id' => 'start_date',
									'class' => 'datepicker',
									'tabindex' => '4',
									'placeholder' => __( 'Start', 'organized' ),
								) ); 
							?>
						</div>
					
						<div class="half">
							<?php 
							echo organized()->fields->text( 
								array(
									'name' => 'end_date',
									'id' => 'end_date',
									'class' => 'datepicker',
									'tabindex' => '5',
									'placeholder' => __( 'End', 'organized' ),
								) ); 
							?>
						</div>

					</div>

					<?php do_action( 'organized_form_after_dates', $this ); ?>

					<div class="input-wrap">
						<div class="half">
							
							<a class="button button-small" title="<?php _e( 'Add files to this thing', 'organized' ); ?>" href="javascript:;" id="add-file" tabindex="7"><?php _e( 'Add Files', 'organized' ); ?></a>
							
							<div id="file-container" class="hidden">
							</div>
							
							<a class="hidden" title="<?php _e( 'Remove this file', 'organized' ); ?>" href="javascript:;" id="remove-file"><?php _e( 'Remove File', 'organized' ); ?></a>

							<input type="hidden" id="file_data" name="file_data" value="" />

						</div>
					
						<div class="half pull-right">
							<?php 
							echo organized()->fields->colorpicker( 
								array(
									'name' => 'color',
									'id' => 'color',
									'tabindex' => '8',
								) ); 
							?>
						</div>
					</div>

					<div class="input-wrap">
						<input type="hidden" name="id" value="" />
						<?php submit_button( __( 'Save Thing' ), 'primary', 'save-thing', false, array( 'id' => 'save-thing', 'tabindex' => '9', ) ); ?>
					</div>

				</div>

			</form>

		<?php

	}


	/**
	 * Hook in to actions & filters
	 *
	 * @param string $msg
	 * @param string $result success or fail
	 * @param string $data any data or html to return
	 * @param int $edit are we editing existing thing
	 */
	public function ajax_return( $msg, $result, $data = null, $edit = 0 ) {

		$return = array(
			'result' => $result,
			'edit' => $edit,
			'data' => $data,
			'message' => $msg,
		);
		
		echo wp_send_json( $return );

	}


	/**
	 * Save a thing.
	 * Also edits a thing
	 */
	public function save_the_thing() {
		
		$this->ajax_checks();

		$this->permission_checks();

		if ( $_POST['action'] !== 'save_the_thing' )
		  	exit( "fail" );

		$data = $this->normalize_thing();

		// Create the new thing if we aren't editing
		if( ! isset( $data['id'] ) || empty( $data['id'] ) ) {
			$thing = array(
			  	'post_title'    => current_time( 'mysql' ),
			  	'post_content'  => '',
			  	'post_status'   => 'publish',
			  	'post_author'   => get_current_user_id(),
			  	'post_type'   	=> 'organized-thing',
			);
			$post_id = wp_insert_post( $thing );
			$edit = 0;		
		} else {
			$post_id = $data['id'];
			$edit = $post_id;
		}

		unset( $data['id'] );

		$meta = update_post_meta( $post_id, 'thing_data', $data );

		if( $post_id ){
			ob_start();
			$this->output_thing( $post_id );
			$html = ob_get_clean();
			$html = preg_replace('/^\s+|\n|\r|\s+$/m', '', $html);

			$this->ajax_return( __( 'Saved', 'organized' ), 'success', $html, $edit );
		} else {
			$this->ajax_return( __( 'Error! Not Saved', 'organized' ), 'fail', null, $edit );
		}
		
	}

	
	/**
	 * Edit a thing
	 * Populates the form
	 */
	public function edit_the_thing() {
		
		$this->ajax_checks();

		if ( $_POST['action'] !== 'edit_the_thing' )
		  	exit( "fail" );

		$post_id = isset( $_POST['id'] ) ? (int) $_POST['id'] : null;
		$data = get_post_meta( $post_id, 'thing_data', true );
		$data['id'] = $post_id;

		if( $data ){
			$this->ajax_return( __( 'Editing', 'organized' ), 'success', $data );
		} else {
			$this->ajax_return( __( 'Error with editing', 'organized' ), 'fail', '' );
		}
		

	}

	/**
	 * Delete a thing
	 */
	public function delete_the_thing() {
		
		$this->ajax_checks();

		if ( $_POST['action'] !== 'delete_the_thing' )
		  	exit( "fail" );

		$post_id = isset( $_POST['id'] ) ? (int) $_POST['id'] : null;
		
		if( ! $post_id )
			exit( "fail" );
		
		$delete = false;

		// remove the thing from the users things
		$user_id = get_current_user_id();
		$things = get_user_meta( $user_id, 'organized_things', true );

		if( $things ) {
			foreach ($things as $i => $col) {
				foreach ($col as $key => $thing) {
					if( $thing == $post_id ) {
						unset( $things[$i][$key] );
						$things[$i] = array_values($things[$i]);
					}
					
				}
			}
		}

		$meta = update_user_meta( $user_id, 'organized_things', $things );

		if( $meta )
			$delete = wp_delete_post( $post_id );

		if( $delete && $meta ){
			$this->ajax_return( __( 'Deleted', 'organized' ), 'success', '' );
		} else {
			$this->ajax_return( __( 'Error! Not Deleted', 'organized' ), 'fail', '' );
		}

	}

	/**
	 * Check our data
	 */
	public function ajax_checks() {

		if ( ! $_POST )
		  	exit( "no post data" );
		if ( ! wp_verify_nonce( $_POST['nonce'], "organized_nonce") ) 
		  	exit( "fail nonce verify" );
	}

	/**
	 * Check our data
	 */
	public function permission_checks() {

		 // ignore the request if the current user doesn't have
		 // sufficient permissions
		 // if ( current_user_can( 'edit_posts' ) ) {
		 //  	exit( "no post data" );

	}

	/**
	 * Normalize our data, posted from the Add Thing form
	 */
	public function normalize_thing() {

		$todos 	= isset( $_POST['todos'] ) ? $_POST['todos'] : array();
		$posted = isset( $_POST['posted'] ) ? $_POST['posted'] : array();

		// parse our posted data into an array
		parse_str( $posted, $data );

		// decode our json file data
		if( $data['file_data'] ) {
			$data['file_data'] = json_decode( $data['file_data'] );
			$data['file_data'] = (array) $data['file_data'];
		}

		// remove what we don't want
		unset( $data['_wp_http_referer'], $data['organized_nonce'], $data['j'] );

		// merge the todos with the main array
		$data['todos'] = $todos;

		if( ! $this->is_empty($data) ) {
			return $data;
		} else {
			exit( "no post data" );
		}

	}

	/*
	 * Stops saving of empty thing
	 */
	private function is_empty($stringOrArray) {
	    if( is_array($stringOrArray) ) {
	        foreach($stringOrArray as $value) {
	            if(!$this->is_empty($value)) {
	                return false;
	            }
	        }
	        return true;
	    }

	    return ! strlen( $stringOrArray );  // this properly checks on empty string ('')
	}


	/**
	 * get the things
	 */
	public function get_things() {
		$user_id = get_current_user_id();
		$things = get_user_meta( $user_id, 'organized_things', true );
		if( ! $things )
			$things = array(''=>''); // empty array
		return $things;
	}


	/**
	 * get a thing and all it's data
	 */
	public function get_thing( $post_id = 0 ) {
		$post = get_post( $post_id, ARRAY_A );
		if( ! $post['ID'] )
			return;
		$data = get_post_meta( $post['ID'], 'thing_data', true );
		return array(
			'data' => $data,
			'post' => $post,
		);
	}


	/**
	 * Output a single thing
	 */
	public function output_thing( $post_id = 0 ) {
		$thing = $this->get_thing( $post_id );
		include( ORGANIZED_DIR . '/templates/single-thing.php' );
	}


	/**
	 * Save the things on the dashboard
	 */
	public function update_things() {
		
		$this->ajax_checks();

		if ( $_POST['action'] !== 'update_things' )
		  	exit( "fail action" );

		$user_id = get_current_user_id();

		$meta = update_user_meta( $user_id, 'organized_things', $_POST['positions'] );

		if( $meta ){
			$this->ajax_return( __( 'Dashboard updated', 'organized' ), 'success', '' );
		} else {
			$this->ajax_return( __( 'Error! Not updated', 'organized' ), 'fail', '' );
		}

	}


}

endif;