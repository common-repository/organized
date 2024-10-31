<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// $thing variable passing through from class-thing.php, output_thing() function
// pp($thing);
$data 		= $thing['data'];
$title 		= isset( $data['title'] ) ? $data['title'] : '';
$notes 		= isset( $data['notes'] ) ? $data['notes'] : '';
$todos 		= isset( $data['todos'] ) ? $data['todos'] : '';
$file 		= isset( $data['file_data'] ) ? $data['file_data'] : '';
$start 		= isset( $data['start_date'] ) ? $data['start_date'] : '';
$end 		= isset( $data['end_date'] ) ? $data['end_date'] : '';
$tags 		= isset( $data['tags'] ) ? $data['tags'] : '';
$color 		= isset( $data['color'] ) ? $data['color'] : '#fff';

?>
	

<li class="thing ui-state-default" data-id="<?php echo (int) $thing['post']['ID']; ?>" style="border-color:<?php echo esc_attr( $color ); ?>">
	
	<div class="handle"></div>
	<div id="delete-thing" class="delete"><span class="dashicons dashicons-no-alt"></span></div>
	<div id="edit-thing" class="edit"><span class="dashicons dashicons-edit"></span></div>
	
	<div class="inner">

		<?php if( $title ) { ?>
		<div class="title">
			<?php echo esc_html( $title ); ?>	
		</div>
		<?php } ?>

		<?php if( $file ) { ?>
		<div class="file <?php echo esc_html( $file['subtype'] ); ?>">
			<img src="<?php echo $file['preview']; ?>" />
			<div class="icons">
				<span><a target="_blank" href="<?php echo esc_attr( $file['editurl'] ); ?>"><span class="dashicons dashicons-edit"></span></a></span>
				<span><a target="_blank" href="<?php echo esc_attr( $file['url'] ); ?>"><span class="dashicons dashicons-download"></span></a></span>
			</div>
			<?php if( ! is_organized_img( $file['subtype'] ) ) { ?>
			<div class="data">
				<span><?php echo esc_attr( $file['filename'] ); ?></span>
				<span><?php echo esc_attr( $file['filesize'] ); ?></span>
			</div>
			<?php } ?>
		</div>
		<?php } ?>

		<?php if( $notes ) { ?>
		<div class="notes">
			<?php echo esc_html( $notes ); ?>	
		</div>
		<?php } ?>

		<?php 
		if( $todos ) { ?>
			<ul class="todos">
				<?php foreach ( $todos as $key => $todo ) { ?>
					<li class="<?php echo esc_attr( $todo['done'] ); ?>">
						<span class="dashicons"></span>
						<span class="text"><?php echo esc_html( $todo['item'] ); ?></span>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>

		<?php if( $start ) { ?>
		<div class="start_date">
			<strong><?php esc_html_e( 'Start', 'organized' ); ?>:</strong> <?php echo esc_html( $start ); ?>
		</div>
		<?php } ?>

		<?php if( $end ) { ?>
		<div class="end_date">
			<strong><?php esc_html_e( 'End', 'organized' ); ?>:</strong> <?php echo esc_html( $end ); ?>
		</div>
		<?php } ?>

		<?php 
		if( $tags ) { ?>
			<ul class="tags">
				<?php foreach ( $tags as $key => $tag ) { ?>
					<li class="">
						<?php echo esc_html( $tag ); ?>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>		

	</div>
</li>

