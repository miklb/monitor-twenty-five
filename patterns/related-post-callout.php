<?php
/**
 * Title: Related Post Callout
 * Slug: monitor-twenty-five/related-post-callout
 * Categories: text, call-to-action
 * Description: A styled callout box to highlight a related post with more in-depth coverage of a topic.
 */
?>

<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}},"border":{"left":{"color":"var:preset|color|primary","width":"4px"},"top":{},"right":{},"bottom":{}}},"backgroundColor":"contrast","textColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-color has-contrast-background-color has-text-color has-background" style="border-left-color:var(--wp--preset--color--primary);border-left-width:4px;margin-top:var(--wp--preset--spacing--60);margin-bottom:var(--wp--preset--spacing--60);padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
	
	<!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"0","left":"0"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-center">
		
		<!-- wp:column {"verticalAlignment":"center","width":"160px"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:160px">
			<!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"thumbnail","linkDestination":"none"} -->
			<figure class="wp-block-image size-thumbnail"><img src="" alt="" style="aspect-ratio:1;object-fit:cover"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-column is-vertically-aligned-center" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--50)">
			
			<!-- wp:heading {"level":4,"style":{"typography":{"fontWeight":"600","textTransform":"uppercase","letterSpacing":"0.05em"}},"fontSize":"small"} -->
			<h4 class="wp-block-heading has-small-font-size" style="font-weight:600;letter-spacing:0.05em;text-transform:uppercase">Related Reading</h4>
			<!-- /wp:heading -->

			<!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}}} -->
			<h3 class="wp-block-heading" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--30)"><a href="#">Title of Related Post</a></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>A brief description or excerpt explaining what readers will find in this related post and why it provides more in-depth coverage of the topic.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
			<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
				<!-- wp:button {"className":"is-style-outline"} -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#">Read the full article →</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
