<?php
/**
 * Monitor Custom functions and definitions
 */

if ( ! function_exists( 'monitor_custom_setup' ) ) {
    function monitor_custom_setup() {
        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );
        
        // Add theme support for other features as needed
    }
}
add_action( 'after_setup_theme', 'monitor_custom_setup' );

/**
 * Enqueue scripts and styles.
 */
function monitor_custom_enqueue_styles() {
    // Enqueue parent theme's style if this is a child theme
    if (is_child_theme()) {
        wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    }
    
    // Enqueue this theme's style.css with a version number for cache busting
    wp_enqueue_style( 'monitor-custom-style', get_stylesheet_uri(), array(), filemtime(get_stylesheet_directory() . '/style.css') );
}
add_action( 'wp_enqueue_scripts', 'monitor_custom_enqueue_styles' );

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

/**
 * Add custom meta boxes
 */
function monitor_custom_meta_boxes() {
    add_meta_box(
        'hide_featured_image',
        'Featured Image Options',
        'monitor_custom_featured_image_callback',
        'post',
        'side'
    );
}
add_action('add_meta_boxes', 'monitor_custom_meta_boxes');

function monitor_custom_featured_image_callback($post) {
    $value = get_post_meta($post->ID, 'hide_featured_image', true);
    ?>
    <label>
        <input type="checkbox" name="hide_featured_image" value="1" <?php checked($value, '1'); ?> />
        Hide featured image on post
    </label>
    <?php
}

function monitor_custom_save_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    $hide_featured = isset($_POST['hide_featured_image']) ? '1' : '';
    update_post_meta($post_id, 'hide_featured_image', $hide_featured);
}
add_action('save_post', 'monitor_custom_save_meta');

/**
 * Monitor Twenty-Five Child Theme functions and definitions
 */

/**
 * Theme setup
 */
function monitor_twentyfive_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');
    
    // Add theme support for other features as needed
}
add_action('after_setup_theme', 'monitor_twentyfive_setup');

/**
 * Enqueue scripts and styles - WordPress 6.8+ best practices for block themes
 */
function monitor_twentyfive_enqueue_styles() {
    // In WordPress 6.8+, for block themes, parent CSS is automatically included
    // We only need to enqueue our child theme's styles
    wp_enqueue_style(
        'monitor-twentyfive-style',
        get_stylesheet_uri(),
        [], // No dependencies needed for WP 6.8+ block themes
        filemtime(get_stylesheet_directory() . '/style.css')
    );
    
    // OPTIONAL: Add this debug hook to see all styles being loaded
    // add_action('wp_footer', function() {
    //     global $wp_styles;
    //     echo '<pre>Loaded styles: ' . print_r($wp_styles->queue, true) . '</pre>';
    // });
}
add_action('wp_enqueue_scripts', 'monitor_twentyfive_enqueue_styles');

/**
 * Register custom block patterns
 */
function monitor_twentyfive_register_patterns() {
    // Make sure the patterns directory exists
    $patterns_dir = get_stylesheet_directory() . '/patterns';
    
    if (!is_dir($patterns_dir)) {
        wp_mkdir_p($patterns_dir);
    }
    
    // Register the pattern directory
    register_block_pattern_category(
        'monitor-twentyfive',
        array('label' => __('Monitor Twenty Five Patterns', 'monitor-twenty-five'))
    );
}
add_action('init', 'monitor_twentyfive_register_patterns');

/**
 * Add custom meta boxes
 */
function monitor_twentyfive_meta_boxes() {
    add_meta_box(
        'hide_featured_image',
        'Featured Image Options',
        'monitor_twentyfive_featured_image_callback',
        'post',
        'side'
    );
}
add_action('add_meta_boxes', 'monitor_twentyfive_meta_boxes');

function monitor_twentyfive_featured_image_callback($post) {
    $value = get_post_meta($post->ID, 'hide_featured_image', true);
    ?>
    <label>
        <input type="checkbox" name="hide_featured_image" value="1" <?php checked($value, '1'); ?> />
        Hide featured image on post
    </label>
    <?php
}

function monitor_twentyfive_save_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    $hide_featured = isset($_POST['hide_featured_image']) ? '1' : '';
    update_post_meta($post_id, 'hide_featured_image', $hide_featured);
}
add_action('save_post', 'monitor_twentyfive_save_meta');
?>