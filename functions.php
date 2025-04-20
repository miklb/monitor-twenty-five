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
?>