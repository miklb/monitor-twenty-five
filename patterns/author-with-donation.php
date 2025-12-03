<?php
/**
 * Title: Author Bio
 * Slug: monitor-custom/author-with-donation
 * Categories: text
 * Description: Display author bio with custom image (hidden for TBJP syndicated content)
 */

// Don't show author bio for TBJP syndicated articles
if ( has_category( 'tbjp' ) ) {
    return;
}
?>

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|30","right":"var:preset|spacing|30"},"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"radius":"8px","width":"1px"},"shadow":"var:preset|shadow|natural"},"borderColor":"accent-6","backgroundColor":"accent-5","className":"monitor-author-bio","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color has-accent-6-border-color has-accent-5-background-color has-background monitor-author-bio" style="border-width:1px;border-radius:8px;margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--40);padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--30)">
  
  <!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|20"}}},"className":"monitor-author-bio__layout"} -->
  <div class="wp-block-columns are-vertically-aligned-center monitor-author-bio__layout">
    
    <!-- wp:column {"verticalAlignment":"center","width":"120px"} -->
    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:120px">
      <!-- wp:avatar {"size":120,"style":{"border":{"radius":"50%"}}} /-->
    </div>
    <!-- /wp:column -->
    
    <!-- wp:column {"verticalAlignment":"top","className":"monitor-author-bio__content"} -->
    <div class="wp-block-column is-vertically-aligned-top monitor-author-bio__content">
      
      <!-- wp:group {"style":{"spacing":{"blockGap":"8px"}},"className":"monitor-author-bio__meta","layout":{"type":"flex","orientation":"vertical","justifyContent":"flex-start"}} -->
      <div class="wp-block-group monitor-author-bio__meta">
        <!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--libre-franklin)","fontWeight":"600"}},"fontSize":"large"} -->
        <h3 class="wp-block-heading has-large-font-size" style="font-family:var(--wp--preset--font-family--libre-franklin);font-weight:600">About the Author</h3>
        <!-- /wp:heading -->
        
        <!-- wp:post-author-name {"isLink":true,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--libre-franklin)","fontWeight":"500"}},"fontSize":"medium"} /-->
        
        <!-- wp:post-author-biography {"style":{"typography":{"fontSize":"0.95rem","lineHeight":"1.6"}}} /-->
      </div>
      <!-- /wp:group -->

      <!-- wp:group {"className":"monitor-author-bio__actions","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"flex-start"}} -->
      <div class="wp-block-group monitor-author-bio__actions">
        <?php
        // Get author ID from the current post
        global $post;
        $author_id = null;
        
        if ( $post ) {
            $author_id = $post->post_author;
        }
        
        // Fallback to queried object
        if ( ! $author_id ) {
            $queried = get_queried_object();
            if ( $queried instanceof WP_Post ) {
                $author_id = $queried->post_author;
            }
        }
        
        // Get the current post author's social links from TM Authors plugin
        if ( $author_id && function_exists( 'tm_authors_get_social_links' ) ) {
            $social_links = tm_authors_get_social_links( $author_id );
            
            if ( ! empty( $social_links ) ) {
                // Build block markup and render it properly
                $block_content = '<!-- wp:social-links {"iconColor":"contrast","iconColorValue":"#314A59","iconBackgroundColor":"accent-5","iconBackgroundColorValue":"#F8F8F2","size":"has-normal-icon-size","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|10"}}},"className":"is-style-default"} --><ul class="wp-block-social-links has-icon-color has-icon-background-color has-normal-icon-size is-style-default">';
                
                foreach ( $social_links as $platform => $data ) {
                    $service = ( 'twitter' === $platform ) ? 'x' : $platform;
                    $block_content .= sprintf(
                        '<!-- wp:social-link {"url":"%s","service":"%s"} /-->',
                        esc_url( $data['url'] ),
                        esc_attr( $service )
                    );
                }
                
                $block_content .= '</ul><!-- /wp:social-links -->';
                
                // Parse and render the blocks
                echo do_blocks( $block_content );
            }
        }
        ?>
      </div>
      <!-- /wp:group -->

    </div>
    <!-- /wp:column -->
    
  </div>
  <!-- /wp:columns -->
  
  
</div>

<!-- /wp:group -->
 


