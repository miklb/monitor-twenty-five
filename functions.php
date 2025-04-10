<?php
/**
 * Monitor Custom functions and definitions
 */

if ( ! function_exists( 'monitor_custom_setup' ) ) {
    function monitor_custom_setup() {
        // Enqueue parent theme's stylesheet
        add_action( 'wp_enqueue_scripts', 'monitor_custom_enqueue_styles' );
    }
}
add_action( 'after_setup_theme', 'monitor_custom_setup' );

/**
 * Enqueue scripts and styles.
 */
function monitor_custom_enqueue_styles() {
    wp_enqueue_style( 'twentytwentyfive-style', get_template_directory_uri() . '/style.css' );
}
?>