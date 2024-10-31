<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div id="organized" class="wrap">
	
	<?php organized_get_tabs( $tabs, $current_tab ); ?>

	<div id="poststuff">

		<?php if ($current_tab == '' || $current_tab == 'dashboard' ) { ?>

		<div class="dash-wrap">

			<div class="col-left">

				<div class="postbox add-thing">
					<h2><?php _e( 'Add Thing', 'organized' ); ?></h2>
					<div class="inside">
						<div class="main">
						
							<?php organized_add_new_thing(); ?>

						</div>
					</div>
				</div>

			</div>
		
			<div class="col-right">

				<div class="sort-wrapper">

					<?php 	
					$cols = organized_get_columns();
					$things = organized_get_things( $cols );

					// minus 1 for zero indexing
					for ($i=0; $i <= ($cols - 1); $i++) { ?>

						<ul id="sortable<?php echo (int)$i; ?>" class="connected sortable cols-<?php echo (int)$cols; ?>">
							<?php 
							if( isset( $things[$i] ) && $things[$i] ) {
								foreach ($things[$i] as $index => $post_id) {
									organized_single_thing( $post_id );
								}
							} ?>
						</ul>

					<?php } ?>


				</div>

			</div>

		</div>

		<?php } else { 

			do_action( 'organized_dashboard_pages', $current_tab );

		} ?>

	</div>

</div>