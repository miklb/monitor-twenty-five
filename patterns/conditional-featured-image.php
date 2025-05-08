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
  <!-- wp:post-featured-image {"aspectRatio":"16/9","style":{"object":{"position":"center center"},"width":"100%","spacing":{"margin":{"bottom":"0"}}}} /-->
  
  <?php 
  // Get the attachment ID for the featured image
  $attachment_id = get_post_thumbnail_id(get_the_ID());
  // Get the caption from the attachment
  $caption = wp_get_attachment_caption($attachment_id);
  
  if (!empty($caption)) : ?>
  <!-- wp:paragraph {"align":"right","style":{"typography":{"fontStyle":"italic"},"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10"}}},"fontSize":"small"} -->
  <p class="has-text-align-right has-small-font-size" style="font-style:italic;padding-top:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--10);"><?php echo esc_html($caption); ?></p>
  <!-- /wp:paragraph -->
  <?php endif; ?>
</div>
<!-- /wp:group -->
<?php endif; ?>