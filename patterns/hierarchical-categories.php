<?php
/**
 * Title: Parent Categories Only
 * Slug: monitor-twentyfive/hierarchical-categories
 * Categories: text
 * Description: Display only parent categories for posts
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
        <!-- wp:group {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left"}} -->
        <div class="wp-block-group">
        <?php foreach ($parent_badges as $badge): ?>
            <a href="<?php echo esc_url($badge['link']); ?>" class="wp-element-button" style="display: inline-block; padding: 0.5rem 1rem; background-color: var(--wp--preset--color--contrast); color: var(--wp--preset--color--base); text-decoration: none; border-radius: 4px; font-family: var(--wp--preset--font-family--libre-franklin); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; margin-right: 0.5rem; margin-bottom: 0.25rem;"><?php echo esc_html($badge['name']); ?></a>
        <?php endforeach; ?>
        </div>
        <!-- /wp:group -->
        <?php
    }
}
?>
