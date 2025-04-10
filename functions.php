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

/**
 * Register custom block patterns
 */
function monitor_custom_register_patterns() {
    // Make sure the patterns directory exists
    $patterns_dir = get_stylesheet_directory() . '/patterns';
    
    if (!is_dir($patterns_dir)) {
        // Create patterns directory if it doesn't exist
        wp_mkdir_p($patterns_dir);
    }
    
    // Register the pattern directory
    register_block_pattern_category(
        'monitor-custom',
        array('label' => __('Monitor Custom Patterns', 'monitor-custom'))
    );
}
add_action('init', 'monitor_custom_register_patterns');
?>