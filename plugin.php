<?php
/*
    Plugin Name: Image Upload - WPshed
    Plugin URI: http://wpshed.com/
    Description: This plugin let's you upload an image to a widget and add a link as well. It can be used for banner Ads or to feature something on your site.
    Author: Stefan I.
    Author URI: http://wpshed.com/
    Version: 0.1
    Text Domain: wpshed
    License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/



/**
 * Load scripts.
 */
function wpshed_image_upload_scripts() {

    global $pagenow, $wp_customize;

    if ( 'widgets.php' === $pagenow || isset( $wp_customize ) ) {

        wp_enqueue_media();
        wp_enqueue_script( 'wpshed-image-upload', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/upload.js', array( 'jquery' ) );
        wp_enqueue_style( 'wpshed-image-upload',  trailingslashit( plugin_dir_url( __FILE__ ) )  . 'css/upload.css' );

    }

}
add_action( 'admin_enqueue_scripts', 'wpshed_image_upload_scripts' );


/**
 * Image Upload Widget
 */
class WPshed_Image_Upload_Widget extends WP_Widget {

    // Holds widget settings defaults, populated in constructor.
    protected $defaults;

    // Constructor. Set the default widget options and create widget.
    function __construct() {

        $this->defaults = array(
            'title' => '',
            'image' => '',
            'link'  => '',
        );

        $widget_ops = array(
            'classname'   => 'wpshed-media-widget',
            'description' => __( 'Image Upload Widget', 'wpshed' ),
        );

        $control_ops = array(
            'id_base' => 'wpshed-media-widget',
            'width'   => 200,
            'height'  => 250,
        );

        parent::__construct( 'wpshed-media-widget', __( 'Image Upload', 'wpshed' ), $widget_ops, $control_ops );

    }

    // The widget content.
    function widget( $args, $instance ) {

        //* Merge with defaults
        $instance = wp_parse_args( (array) $instance, $this->defaults );

        echo $args['before_widget'];

            if ( ! empty( $instance['title'] ) )
                echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];

            echo ( ! empty( $instance['link'] ) ) ? '<a href="' . $instance['link'] . '">' : '';

            echo ( ! empty( $instance['image'] ) ) ? '<img src="' . $instance['image'] . '" alt="" />' : '';

            echo ( ! empty( $instance['link'] ) ) ? '</a>' : '';

        echo $args['after_widget'];

    }

    // Update a particular instance.
    function update( $new_instance, $old_instance ) {

        $new_instance['title']  = strip_tags( $new_instance['title'] );
        $new_instance['image']  = strip_tags( $new_instance['image'] );
        $new_instance['link']   = strip_tags( $new_instance['link'] );

        return $new_instance;

    }

    // The settings update form.
    function form( $instance ) {

        // Merge with defaults
        $instance = wp_parse_args( (array) $instance, $this->defaults );

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpshed' ); ?>:</label>
            <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Image', 'wpshed' ); ?>:</label>
            <div class="wpshed-media-container">
                <div class="wpshed-media-inner">
                    <?php $img_style = ( $instance[ 'image' ] != '' ) ? '' : 'style="display:none;"'; ?>
                    <img id="<?php echo $this->get_field_id( 'image' ); ?>-preview" src="<?php echo esc_attr( $instance['image'] ); ?>" <?php echo $img_style; ?> />
                    <?php $no_img_style = ( $instance[ 'image' ] != '' ) ? 'style="display:none;"' : ''; ?>
                    <span class="wpshed-no-image" id="<?php echo $this->get_field_id( 'image' ); ?>-noimg" <?php echo $no_img_style; ?>><?php _e( 'No image selected', 'wpshed' ); ?></span>
                </div>
            
            <input type="text" id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" value="<?php echo esc_attr( $instance['image'] ); ?>" class="wpshed-media-url" />

            <input type="button" value="<?php echo _e( 'Remove', 'wpshed' ); ?>" class="button wpshed-media-remove" id="<?php echo $this->get_field_id( 'image' ); ?>-remove" <?php echo $img_style; ?> />

            <?php $button_text = ( $instance[ 'image' ] != '' ) ? __( 'Change Image', 'wpshed' ) : __( 'Select Image', 'wpshed' ); ?>
            <input type="button" value="<?php echo $button_text; ?>" class="button wpshed-media-upload" id="<?php echo $this->get_field_id( 'image' ); ?>-button" />
            <br class="clear">
            </div>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'URL', 'wpshed' ); ?>:</label>
            <input type="text" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo esc_attr( $instance['link'] ); ?>" class="widefat" />
        </p>

        <?php

    }

}


/**
 * Register Widget
 */
function register_wpshed_image_upload_widget() { 
  
    register_widget( 'WPshed_Image_Upload_Widget' ); 

} 
add_action( 'widgets_init','register_wpshed_image_upload_widget' );
