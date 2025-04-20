<?php
/**
 * Title: Conditional Featured Image
 * Slug: monitor-custom/conditional-featured-image
 * Categories: text
 */
?>

<!-- wp:group -->
<div class="wp-block-group">
<?php if (!get_post_meta(get_the_ID(), 'hide_featured_image', true)) : ?>
  <!-- wp:post-featured-image {"aspectRatio":"16/9","style":{"object":{"position":"center center"},"width":"100%"}} /-->
<?php endif; ?>
</div>
<!-- /wp:group -->