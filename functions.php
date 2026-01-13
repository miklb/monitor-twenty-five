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
 * Hide author byline and author bio patterns for posts in category 22 (Agendas)
 */
function monitor_custom_hide_author_for_category_22( $block_content, $block ) {
    // Only run on single posts
    if ( ! is_single() ) {
        return $block_content;
    }
    
    // Check if this is one of our patterns that should be hidden
    if ( isset( $block['blockName'] ) && $block['blockName'] === 'core/pattern' ) {
        if ( isset( $block['attrs']['slug'] ) ) {
            $slug = $block['attrs']['slug'];
            
            // Check if this is one of the patterns we want to conditionally hide
            if ( $slug === 'monitor-custom/author-with-donation' || $slug === 'monitor-custom/post-meta-byline' ) {
                // Check if current post has category 22 (Agendas)
                if ( has_category( 22 ) ) {
                    return ''; // Return empty string to hide the pattern
                }
            }
        }
    }
    
    return $block_content;
}
add_filter( 'render_block', 'monitor_custom_hide_author_for_category_22', 10, 2 );

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
 * Shortcode to display category badges (parent and child separately)
 * This executes for each post in a query loop, unlike patterns which cache
 */
function monitor_custom_category_badges_shortcode($atts) {
    // Parse attributes
    $atts = shortcode_atts(array(
        'accent' => 'false', // Whether to use accent styling
    ), $atts);
    
    $use_accent = ($atts['accent'] === 'true');
    
    // Get the current post in the loop - try multiple methods
    $post_id = get_the_ID();
    
    // Fallback to global $post if get_the_ID() doesn't work
    if (!$post_id) {
        global $post;
        if ($post && isset($post->ID)) {
            $post_id = $post->ID;
        }
    }
    
    if (!$post_id) {
        return '<!-- No post ID found -->';
    }
    
    $categories = get_the_category($post_id);
    
    if (empty($categories)) {
        return '';
    }
    
    $parent_badges = array();
    $child_badges = array();
    
    foreach ($categories as $category) {
        if (!is_object($category) || !isset($category->term_id)) {
            continue;
        }
        
        // If this is a parent category (parent == 0), show it as parent
        if ($category->parent == 0) {
            if (!isset($parent_badges[$category->slug])) {
                $parent_badges[$category->slug] = array(
                    'name' => $category->name,
                    'link' => get_category_link($category->term_id)
                );
            }
        } else {
            // This is a child category
            if (!isset($child_badges[$category->slug])) {
                $child_badges[$category->slug] = array(
                    'name' => $category->name,
                    'link' => get_category_link($category->term_id)
                );
            }
            
            // Also find and show its top-level parent
            $parent_id = $category->parent;
            while ($parent_id != 0) {
                $parent = get_category($parent_id);
                if ($parent && !is_wp_error($parent)) {
                    if ($parent->parent == 0) {
                        if (!isset($parent_badges[$parent->slug])) {
                            $parent_badges[$parent->slug] = array(
                                'name' => $parent->name,
                                'link' => get_category_link($parent->term_id)
                            );
                        }
                        break;
                    }
                    $parent_id = $parent->parent;
                } else {
                    break;
                }
            }
        }
    }
    
    // Build output
    $output = '';
    $accent_class = $use_accent ? ' category-badge-accent' : '';
    
    // Display parent badges
    if (!empty($parent_badges)) {
        foreach ($parent_badges as $badge) {
            $output .= sprintf(
                '<a href="%s" class="category-badge category-badge-parent%s">%s</a>',
                esc_url($badge['link']),
                esc_attr($accent_class),
                esc_html($badge['name'])
            );
        }
    }
    
    // Display child badges
    if (!empty($child_badges)) {
        foreach ($child_badges as $badge) {
            $output .= sprintf(
                '<a href="%s" class="category-badge category-badge-child%s">%s</a>',
                esc_url($badge['link']),
                esc_attr($accent_class),
                esc_html($badge['name'])
            );
        }
    }
    
    return $output;
}
add_shortcode('category_badges', 'monitor_custom_category_badges_shortcode');

/**
 * Register a custom dynamic block for category badges
 * This will properly execute in query loops unlike patterns
 */
function monitor_custom_register_category_badge_block() {
    register_block_type('monitor-twentyfive/category-badges', array(
        'render_callback' => 'monitor_custom_render_category_badges_block',
        'attributes' => array(
            'accent' => array(
                'type' => 'boolean',
                'default' => false,
            ),
        ),
    ));
}
add_action('init', 'monitor_custom_register_category_badge_block');

/**
 * Render callback for category badges block
 */
function monitor_custom_render_category_badges_block($attributes) {
    $use_accent = isset($attributes['accent']) ? $attributes['accent'] : false;
    
    // Get the current post ID
    $post_id = get_the_ID();
    if (!$post_id) {
        return '';
    }
    
    $categories = get_the_category($post_id);
    
    if (empty($categories)) {
        return '';
    }
    
    $parent_badges = array();
    $child_badges = array();
    
    foreach ($categories as $category) {
        if (!is_object($category) || !isset($category->term_id)) {
            continue;
        }
        
        if ($category->parent == 0) {
            if (!isset($parent_badges[$category->slug])) {
                $parent_badges[$category->slug] = array(
                    'name' => $category->name,
                    'link' => get_category_link($category->term_id)
                );
            }
        } else {
            if (!isset($child_badges[$category->slug])) {
                $child_badges[$category->slug] = array(
                    'name' => $category->name,
                    'link' => get_category_link($category->term_id)
                );
            }
            
            $parent_id = $category->parent;
            while ($parent_id != 0) {
                $parent = get_category($parent_id);
                if ($parent && !is_wp_error($parent)) {
                    if ($parent->parent == 0) {
                        if (!isset($parent_badges[$parent->slug])) {
                            $parent_badges[$parent->slug] = array(
                                'name' => $parent->name,
                                'link' => get_category_link($parent->term_id)
                            );
                        }
                        break;
                    }
                    $parent_id = $parent->parent;
                } else {
                    break;
                }
            }
        }
    }
    
    $output = '';
    $accent_class = $use_accent ? ' category-badge-accent' : '';
    
    if (!empty($parent_badges)) {
        foreach ($parent_badges as $badge) {
            $output .= sprintf(
                '<a href="%s" class="category-badge category-badge-parent%s">%s</a>',
                esc_url($badge['link']),
                esc_attr($accent_class),
                esc_html($badge['name'])
            );
        }
    }
    
    if (!empty($child_badges)) {
        foreach ($child_badges as $badge) {
            $output .= sprintf(
                '<a href="%s" class="category-badge category-badge-child%s">%s</a>',
                esc_url($badge['link']),
                esc_attr($accent_class),
                esc_html($badge['name'])
            );
        }
    }
    
    return $output;
}

/**
 * Enable shortcode processing in block content
 * This is necessary for shortcodes to work in block templates
 */
function monitor_custom_process_shortcodes_in_blocks($block_content, $block) {
    // Only process shortcode blocks
    if ($block['blockName'] === 'core/shortcode' && !empty($block_content)) {
        return do_shortcode($block_content);
    }
    return $block_content;
}
add_filter('render_block', 'monitor_custom_process_shortcodes_in_blocks', 10, 2);

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

/**
 * Enqueue custom editor styles for block editor.
 */
function monitor_twentyfive_add_editor_styles() {
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-styles.css');
}
add_action('after_setup_theme', 'monitor_twentyfive_add_editor_styles');

/**
 * Shortcode to display category badges for parent and child categories
 * Usage: [category_badges] or [category_badges accent="true"]
 */
function monitor_twentyfive_category_badges_shortcode($atts) {
    $atts = shortcode_atts(array(
        'accent' => false,
    ), $atts);
    
    $accent_class = $atts['accent'] ? ' category-badge-accent' : '';
    
    // Get the current post ID in the loop
    global $post;
    if (!$post) {
        return '';
    }
    
    $post_id = $post->ID;
    $categories = get_the_category($post_id);
    
    if (empty($categories)) {
        return '';
    }
    
    $parent_badges = array();
    $child_badges = array();
    
    foreach ($categories as $category) {
        if (!is_object($category) || !isset($category->term_id)) {
            continue;
        }
        
        // If this is a parent category (parent == 0), show it as parent
        if ($category->parent == 0) {
            if (!isset($parent_badges[$category->slug])) {
                $parent_badges[$category->slug] = array(
                    'name' => $category->name,
                    'link' => get_category_link($category->term_id)
                );
            }
        } else {
            // This is a child category - add it to child badges
            if (!isset($child_badges[$category->slug])) {
                $child_badges[$category->slug] = array(
                    'name' => $category->name,
                    'link' => get_category_link($category->term_id)
                );
            }
            
            // Also find and show its top-level parent
            $parent_id = $category->parent;
            
            // Walk up to find the top-level parent
            while ($parent_id != 0) {
                $parent = get_category($parent_id);
                if ($parent && !is_wp_error($parent)) {
                    if ($parent->parent == 0) {
                        // This is a top-level parent, add it
                        if (!isset($parent_badges[$parent->slug])) {
                            $parent_badges[$parent->slug] = array(
                                'name' => $parent->name,
                                'link' => get_category_link($parent->term_id)
                            );
                        }
                        break;
                    }
                    $parent_id = $parent->parent;
                } else {
                    break;
                }
            }
        }
    }
    
    $output = '';
    
    // Display parent badges
    if (!empty($parent_badges)) {
        foreach ($parent_badges as $badge) {
            $output .= sprintf(
                '<a href="%s" class="category-badge category-badge-parent%s">%s</a>',
                esc_url($badge['link']),
                esc_attr($accent_class),
                esc_html($badge['name'])
            );
        }
    }
    
    // Display child badges
    if (!empty($child_badges)) {
        foreach ($child_badges as $badge) {
            $output .= sprintf(
                '<a href="%s" class="category-badge category-badge-child%s">%s</a>',
                esc_url($badge['link']),
                esc_attr($accent_class),
                esc_html($badge['name'])
            );
        }
    }
    
    return $output;
}
add_shortcode('category_badges', 'monitor_twentyfive_category_badges_shortcode');


?>