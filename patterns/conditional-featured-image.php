<?php
/**
 * Title: Conditional Featured Image
 * Slug: monitor-custom/conditional-featured-image
 * Categories: text
 */
?>

<?php if (!get_post_meta(get_the_ID(), 'hide_featured_image', true)) : ?>
<!-- wp:group -->
<div class="wp-block-group">
  <!-- wp:post-featured-image {"aspectRatio":"16/9","style":{"object":{"position":"center center"},"width":"100%"}} /-->
</div>
<!-- /wp:group -->
<?php endif; ?>