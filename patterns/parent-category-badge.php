<?php
/**
 * Title: Parent Category Badge
 * Slug: monitor-twentyfive/parent-category-badge
 * Categories: text
 * Description: Display parent and child category badges separately for hierarchical posts
 */

// Ensure we have the global post object
global $post;

// Get the current post ID from the global post or get_the_ID()
$post_id = isset($post->ID) ? $post->ID : get_the_ID();
if (!$post_id) {
    return;
}

// Get the current post categories - use the post ID to ensure we get the right categories
$categories = get_the_category($post_id);

if (!empty($categories)) {
    $parent_badges = array();
    $child_badges = array();
    
    foreach ($categories as $category) {
        // Ensure we're working with clean category data
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
    
    // Display parent badges (no padding)
    if (!empty($parent_badges)) {
        foreach ($parent_badges as $badge): ?>
<a href="<?php echo esc_url($badge['link']); ?>" class="category-badge category-badge-parent"><?php echo esc_html($badge['name']); ?></a>
        <?php endforeach;
    }
    
    // Display child badges
    if (!empty($child_badges)) {
        foreach ($child_badges as $badge): ?>
<a href="<?php echo esc_url($badge['link']); ?>" class="category-badge category-badge-child"><?php echo esc_html($badge['name']); ?></a>
        <?php endforeach;
    }
}
?>
