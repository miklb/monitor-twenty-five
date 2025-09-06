<?php
/**
 * Title: Parent Category Badge
 * Slug: monitor-twentyfive/parent-category-badge
 * Categories: text
 * Description: Display parent category badge for hierarchical posts
 */

// Ensure we have the correct post context
global $post;
if (!$post) {
    return;
}

// Get the current post categories - use the post ID to ensure we get the right categories
$categories = get_the_category($post->ID);

if (!empty($categories)) {
    $parent_badges = array();
    
    foreach ($categories as $category) {
        // Ensure we're working with clean category data
        if (!is_object($category) || !isset($category->term_id)) {
            continue;
        }
        
        // If this is a parent category (parent == 0), show it directly
        if ($category->parent == 0) {
            if (!isset($parent_badges[$category->slug])) {
                $parent_badges[$category->slug] = array(
                    'name' => $category->name,
                    'link' => get_category_link($category->term_id)
                );
            }
        } else {
            // If this is a child category, find and show its parent
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
    
    if (!empty($parent_badges)) {
        ?>
        <?php foreach ($parent_badges as $badge): ?>
            <a href="<?php echo esc_url($badge['link']); ?>" class="category-badge category-badge-parent"><?php echo esc_html($badge['name']); ?></a>
        <?php endforeach; ?>
        <?php
    }
}
?>
