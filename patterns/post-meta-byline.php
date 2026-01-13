<?php
/**
 * Title: Post Meta Byline
 * Slug: monitor-custom/post-meta-byline
 * Categories: text
 * Description: Date and author byline (hidden for category ID 22 - Agendas)
 */

// Don't show byline for category ID 22 (Agendas)
if ( has_category( 22 ) ) {
    return;
}
?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group">
<p><!-- wp:tm-authors/author-byline {"showAvatar":false,"bylinePrefix":"By"} /-->

    <!-- wp:post-date {"fontSize":"large"} /-->
</p>
</div>
<!-- /wp:group -->