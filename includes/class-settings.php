<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists('Organized_Settings' ) ):

class Organized_Settings {

    private $settings_api;

    function __construct() {

        $this->settings_api = new Organized_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );        

    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function get_settings_sections() {
        $tabs = array(
            array(
                'id'    => 'organized',
                'title' => __( 'Organized Settings', 'organized' )
            ),
        );
        return apply_filters( 'organized_settings_tabs', $tabs );
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'organized' => array(
                array(
                    'name'    => 'columns',
                    'label'   => __( 'Columns', 'organized' ),
                    'desc'    => __( 'Number of columns on the dashboard', 'organized' ),
                    'type'    => 'select',
                    'default' => '3',
                    'options' => array(
                        '1' => __( '1 Column', 'organized' ),
                        '2' => __( '2 Columns', 'organized' ),
                        '3' => __( '3 Columns', 'organized' ),
                        '4' => __( '4 Columns', 'organized' ),
                    )
                ),
                // array(
                //     'name'              => 'text_val',
                //     'label'             => __( 'Text Input', 'organized' ),
                //     'desc'              => __( 'Text input description', 'organized' ),
                //     'placeholder'       => __( 'Text Input placeholder', 'organized' ),
                //     'type'              => 'text',
                //     'default'           => 'Title',
                //     'sanitize_callback' => 'sanitize_text_field'
                // ),
                // array(
                //     'name'              => 'number_input',
                //     'label'             => __( 'Number Input', 'organized' ),
                //     'desc'              => __( 'Number field with validation callback `floatval`', 'organized' ),
                //     'placeholder'       => __( '1.99', 'organized' ),
                //     'min'               => 0,
                //     'max'               => 100,
                //     'step'              => '0.01',
                //     'type'              => 'number',
                //     'default'           => 'Title',
                //     'sanitize_callback' => 'floatval'
                // ),
                // array(
                //     'name'        => 'textarea',
                //     'label'       => __( 'Textarea Input', 'organized' ),
                //     'desc'        => __( 'Textarea description', 'organized' ),
                //     'placeholder' => __( 'Textarea placeholder', 'organized' ),
                //     'type'        => 'textarea'
                // ),
                // array(
                //     'name'        => 'html',
                //     'desc'        => __( 'HTML area description. You can use any <strong>bold</strong> or other HTML elements.', 'organized' ),
                //     'type'        => 'html'
                // ),
                // array(
                //     'name'  => 'checkbox',
                //     'label' => __( 'Checkbox', 'organized' ),
                //     'desc'  => __( 'Checkbox Label', 'organized' ),
                //     'type'  => 'checkbox'
                // ),
                // array(
                //     'name'    => 'radio',
                //     'label'   => __( 'Radio Button', 'organized' ),
                //     'desc'    => __( 'A radio button', 'organized' ),
                //     'type'    => 'radio',
                //     'options' => array(
                //         'yes' => 'Yes',
                //         'no'  => 'No'
                //     )
                // ),
                
                // array(
                //     'name'    => 'password',
                //     'label'   => __( 'Password', 'organized' ),
                //     'desc'    => __( 'Password description', 'organized' ),
                //     'type'    => 'password',
                //     'default' => ''
                // ),
                // array(
                //     'name'    => 'file',
                //     'label'   => __( 'File', 'organized' ),
                //     'desc'    => __( 'File description', 'organized' ),
                //     'type'    => 'file',
                //     'default' => '',
                //     'options' => array(
                //         'button_label' => 'Choose Image'
                //     )
                // ),
                // array(
                //     'name'    => 'color',
                //     'label'   => __( 'Color', 'organized' ),
                //     'desc'    => __( 'Color description', 'organized' ),
                //     'type'    => 'color',
                //     'default' => ''
                // ),
                // array(
                //     'name'    => 'password',
                //     'label'   => __( 'Password', 'organized' ),
                //     'desc'    => __( 'Password description', 'organized' ),
                //     'type'    => 'password',
                //     'default' => ''
                // ),
                // array(
                //     'name'    => 'wysiwyg',
                //     'label'   => __( 'Advanced Editor', 'organized' ),
                //     'desc'    => __( 'WP_Editor description', 'organized' ),
                //     'type'    => 'wysiwyg',
                //     'default' => ''
                // ),
                // array(
                //     'name'    => 'multicheck',
                //     'label'   => __( 'Multile checkbox', 'organized' ),
                //     'desc'    => __( 'Multi checkbox description', 'organized' ),
                //     'type'    => 'multicheck',
                //     'default' => array('one' => 'one', 'four' => 'four'),
                //     'options' => array(
                //         'one'   => 'One',
                //         'two'   => 'Two',
                //         'three' => 'Three',
                //         'four'  => 'Four'
                //     )
                // ),
            )
        );

        return apply_filters( 'organized_settings_fields', $settings_fields );
    }

    public function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;
