<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Helper function to use in your theme to return a theme option value
function organized_get_option( $option = '' ) {
	if( ! $option )
		return;
	$options = get_option( 'organized' );
	return isset( $options[ $option ] ) ? $options[ $option ] : null;
}


function organized_add_new_thing() {
	$thing = new Organized_Thing();
	return $thing->add_new();
}

function organized_get_things( $cols = 3 ) {
	$thing = new Organized_Thing();
	$things = $thing->get_things();
	$count = count( $things );

	// if we change columns to have less than previous
	// we need to move the things into viewable cols
	if( $count > $cols ) {

		$visible 	= array_slice($things, 0, $cols, true);
		$hidden 	= array_slice($things, $cols, ( $count - $cols ), true);
		$last_key 	= key( array_slice( $visible, -1, 1, TRUE ) );

		// merge all hidden into 1 array
		foreach ($hidden as $arr) foreach ($arr as $val) $temp[] = $val;

		$last_array = array_merge( $visible[$last_key], $temp );
		$visible[$last_key] = $last_array;
		$output = $visible;

	} else {
		$output = $things;
	}

	return $output;

}



function organized_single_thing( $post_id ) {
	$thing = new Organized_Thing();
	return $thing->output_thing( $post_id );
}

function organized_get_columns() {
	return organized_get_option( 'columns' ) ? organized_get_option( 'columns' ) : '3';
}

function is_organized_img( $filetype ) {
	if( $filetype == 'jpeg' || $filetype == 'jpg' || $filetype == 'png' || $filetype == 'gif' ) {
		return true;
	} else {
		return false;
	}
}


function organized_get_tabs( $tabs, $current_tab ) {
	?>

	<nav class="nav-tab-wrapper">
		<?php
			foreach ( $tabs as $key => $tab_group ) {
				echo '<a href="' . admin_url( 'admin.php?page=organized&tab=' . urlencode( $key ) ) . '" class="nav-tab ';
				if ( $current_tab == $key ) {
					echo 'nav-tab-active';
				}
				echo '">' . esc_html( $tab_group['title'] ) . '</a>';
			}

			do_action( 'organized_tabs' );
		?>
	</nav>

	<?php
}