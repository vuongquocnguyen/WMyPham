<?php
/**
 * Title: Footer
 * Slug: organic-food-store/footer
 * Categories: organic-food-store
 *
 * @package Organic Food Store
 * @since 1.0.0
 */

?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"60px","right":"20px","bottom":"60px","left":"20px"},"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"backgroundColor":"primary","textColor":"white","layout":{"type":"constrained","contentSize":"90%"},"fontSize":"small"} -->
<div class="wp-block-group has-white-color has-primary-background-color has-text-color has-background has-small-font-size" style="margin-top:0;margin-bottom:0;padding-top:60px;padding-right:20px;padding-bottom:60px;padding-left:20px"><!-- wp:group {"align":"wide","style":{"elements":{"link":{"color":{"text":"var:preset|color|Background"}}},"spacing":{"margin":{"top":"0px","bottom":"0px"}}},"textColor":"Background","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide has-background-color has-text-color has-link-color" style="margin-top:0px;margin-bottom:0px"><!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:site-logo /-->

<!-- wp:site-title {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white","fontSize":"extra-large","fontFamily":"merriweather"} /--></div>
<!-- /wp:group -->

<!-- wp:paragraph {"align":"left"} -->
<p class="has-text-align-left">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:image {"id":1993,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() );?>/images/telephone.png" alt="" class="wp-image-1993"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"fontFamily":"merriweather"} -->
<p class="has-merriweather-font-family">(124) 2154 248</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:image {"id":1990,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() );?>/images/envelope.png" alt="" class="wp-image-1990"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph -->
<p><a href="mailto:mail@example.com">mail@example.com</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":5,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","letterSpacing":"1px"}},"textColor":"white","fontFamily":"merriweather"} -->
<h5 class="wp-block-heading has-white-color has-text-color has-merriweather-font-family" style="font-style:normal;font-weight:600;letter-spacing:1px">Recent Posts</h5>
<!-- /wp:heading -->

<!-- wp:separator {"style":{"spacing":{"margin":{"top":"10px"}},"color":{"background":"#0B4D4A"}},"className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-alpha-channel-opacity has-background is-style-wide" style="margin-top:10px;background-color:#0B4D4A;color:#0B4D4A"/>
<!-- /wp:separator -->

<!-- wp:query {"queryId":22,"query":{"perPage":"3","pages":"1","offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false}} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"default","columnCount":3}} -->
<!-- wp:post-title {"level":6,"isLink":true,"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"spacing":{"margin":{"top":"0px","right":"0px","bottom":"0px","left":"0px"}}},"textColor":"white","fontSize":"small"} /-->

<!-- wp:post-excerpt {"showMoreOnNewLine":false,"style":{"spacing":{"margin":{"top":"5px","right":"0px","bottom":"0px","left":"0px"}}},"className":"footer-post-excerpt","fontSize":"extra-small"} /-->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"placeholder":"Add text or blocks that will display when a query returns no results."} -->
<p>There is no posts to display</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"top"} -->
<div class="wp-block-column is-vertically-aligned-top"><!-- wp:heading {"level":5,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","letterSpacing":"1px"}},"textColor":"white","fontFamily":"merriweather"} -->
<h5 class="wp-block-heading has-white-color has-text-color has-merriweather-font-family" style="font-style:normal;font-weight:600;letter-spacing:1px">Quick Links</h5>
<!-- /wp:heading -->

<!-- wp:separator {"style":{"spacing":{"margin":{"top":"10px"}},"color":{"background":"#0B4D4A"}},"className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-alpha-channel-opacity has-background is-style-wide" style="margin-top:10px;background-color:#0B4D4A;color:#0B4D4A"/>
<!-- /wp:separator -->

<!-- wp:navigation-link {"label":"Home","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"My Account","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"Checkout","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"Cart","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"Contact Us","type":"","url":"#","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"Customer Support","type":"","url":"#","kind":"custom","isTopLevelLink":true} /--></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":5,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","letterSpacing":"1px"}},"textColor":"white","fontFamily":"merriweather"} -->
<h5 class="wp-block-heading has-white-color has-text-color has-merriweather-font-family" style="font-style:normal;font-weight:600;letter-spacing:1px">Contact Info</h5>
<!-- /wp:heading -->

<!-- wp:separator {"style":{"spacing":{"margin":{"top":"10px"}},"color":{"background":"#0B4D4A"}},"className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-alpha-channel-opacity has-background is-style-wide" style="margin-top:10px;background-color:#0B4D4A;color:#0B4D4A"/>
<!-- /wp:separator -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:image {"id":1993,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() );?>/images/telephone.png" alt="" class="wp-image-1993"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"fontFamily":"merriweather"} -->
<p class="has-merriweather-font-family">(124) 2154 248</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:image {"id":1990,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() );?>/images/envelope.png" alt="" class="wp-image-1990"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph -->
<p><a href="mailto:mail@example.com">mail@example.com</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:social-links {"iconColor":"white","iconColorValue":"#ffffff","customIconBackgroundColor":"#0B4D4A","iconBackgroundColorValue":"#0B4D4A","size":"has-normal-icon-size","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"className":"is-style-default"} -->
<ul class="wp-block-social-links has-normal-icon-size has-icon-color has-icon-background-color is-style-default"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"twitter"} /-->

<!-- wp:social-link {"url":"#","service":"instagram"} /-->

<!-- wp:social-link {"url":"#","service":"linkedin"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"20px","right":"20px","bottom":"20px","left":"20px"}},"color":{"background":"#3b8419"}},"textColor":"white","layout":{"type":"constrained","contentSize":"90%"}} -->
<div class="wp-block-group has-white-color has-text-color has-background" style="background-color:#3b8419;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:group {"align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"className":"copyright-text","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group copyright-text"><!-- wp:paragraph {"textColor":"Background","fontFamily":"merriweather"} -->
<p class="has-background-color has-text-color has-merriweather-font-family">© Copyright 2024. All Rights Reserved.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|Background"}}},"layout":{"selfStretch":"fit","flexSize":null}},"textColor":"Background","fontFamily":"merriweather"} -->
<p class="has-background-color has-text-color has-link-color has-merriweather-font-family">Proudly powered by <a rel="noreferrer noopener" href="https://templatehouse.net/" target="_blank">Organic Food Store Themes</a> and <a href="https://wordpress.org">WordPress</a>.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->