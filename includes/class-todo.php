<?php


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Organized_Todo' ) ) :

/**
 * The main class
 *
 * @since 1.0.0
 */
class Organized_Todo {


	
	public function button_confirm() {
		ob_start();

		?>
		<span class="organized-todo-button">
		    <span class="organized-todo-action organized-todo-button-confirm">Sure?</span>
		    <span class="organized-todo-action organized-todo-button-cancel"><span class="dashicons dashicons-no-alt"></span></span>
		</span>

		<?php
		$output = ob_get_clean();
		$output = preg_replace('/^\s+|\n|\r|\s+$/m', '', $output);
		return $output;
	}

	public function item_edit() {
		ob_start();

		?>
		<div class="organized-todo-edit">
		    <div class="organized-todo-edit-input">
		        <input type="text" name="e" value="" />
		    </div>
		    <span class="organized-todo-edit-save" title="Save"><span class="dashicons dashicons-yes"></span></span>
		</div>

		<?php
		$output = ob_get_clean();
		$output = preg_replace('/^\s+|\n|\r|\s+$/m', '', $output);
		return $output;
	}

	public function item() {
		ob_start();

		?>
		<div class="organized-todo-item">
		    <div class="">
		        <div class="organized-todo-item-title organized-todo-action-edit organized-todo-action">
		            <span class="organized-todo-item-title-text"></span>
		        </div>
		    </div>
		    <span class="organized-todo-item-actions-left">
		        <span class="organized-todo-action organized-todo-item-checkbox"></span>
		    </span>
		    <span class="organized-todo-item-actions-right">
		        <span class="organized-todo-action organized-todo-item-action-remove"><span class="dashicons dashicons-no-alt"></span></span>
		    </span>
		</div>

		<?php
		$output = ob_get_clean();
		$output = preg_replace('/^\s+|\n|\r|\s+$/m', '', $output);
		return $output;
	}


	public function list_items() {
		ob_start();

		?>
		<div class="organized-todo" style="display:none;">

		    <div class="organized-todo-footer">
		        <div class="organized-todo-add">
		            <span class="organized-todo-add-input">
		                <input class="organized-todo-add-input-text" placeholder="New Item" tabindex="3" type="text" name="j" name="j" value="" />
		            </span>
		            <a class="organized-todo-action organized-todo-add-action" href="javascript:"><span class="dashicons dashicons-plus"></span></a>
		        </div>
		    </div>
		    
		    <div class="organized-todo-items"></div>

		    
		</div>

		<?php
		$output = ob_get_clean();
		$output = preg_replace('/^\s+|\n|\r|\s+$/m', '', $output);
		return $output;
	}



}

endif;