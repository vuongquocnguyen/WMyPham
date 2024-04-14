<?php
/**
 * Title: Header
 * Slug: organic-food-store/header
 * Categories: organic-food-store
 *
 * @package Organic Food Store
 * @since 1.0.0
 */

?>
<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20","right":"var:preset|spacing|20"}},"color":{"background":"#233f92"}},"layout":{"type":"constrained","wideSize":"90%"}} -->
<div class="wp-block-group has-background" style="background-color:#233f92;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"verticalAlignment":"center","width":"25%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%"><!-- wp:heading {"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}},"typography":{"fontSize":"15px"}},"textColor":"base"} -->
<h2 class="wp-block-heading has-base-color has-text-color has-link-color" style="font-size:15px">15% SPECIAL DISCOUNT*</h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%"><!-- wp:heading {"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}},"typography":{"fontSize":"15px"}},"textColor":"base"} -->
<h2 class="wp-block-heading has-base-color has-text-color has-link-color" style="font-size:15px">FLASH SALE: 60% OFF CAR BATTERIES | USE CODE "BATT60"</h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"><!-- wp:social-links {"layout":{"type":"flex","justifyContent":"right"}} -->
<ul class="wp-block-social-links"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"twitter"} /-->

<!-- wp:social-link {"url":"#","service":"linkedin"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","right":"20px","bottom":"0px","left":"20px"}}},"backgroundColor":"base","className":"upper-header","layout":{"type":"constrained","contentSize":"90%"}} -->
<div class="wp-block-group upper-header has-base-background-color has-background" style="padding-top:0px;padding-right:20px;padding-bottom:0px;padding-left:20px"><!-- wp:columns {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:column {"verticalAlignment":"center","width":"5%","className":"header-nav"} -->
<div class="wp-block-column is-vertically-aligned-center header-nav" style="flex-basis:5%"><!-- wp:navigation {"ref":4,"textColor":"heading","overlayMenu":"always","overlayBackgroundColor":"white","customOverlayTextColor":"#0b4d4a","layout":{"type":"flex","flexWrap":"wrap"},"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontFamily":"merriweather"} /--></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"20%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:20%"><!-- wp:group {"textColor":"primary","className":"logodiv","layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group logodiv has-primary-color has-text-color"><!-- wp:site-logo {"width":43,"shouldSyncIcon":true} /-->

<!-- wp:site-title {"style":{"elements":{"link":{"color":{"text":"#0b4d4a"}}},"color":{"text":"#0b4d4a"},"typography":{"textTransform":"capitalize","fontStyle":"normal","fontWeight":"700"}},"fontSize":"regular","fontFamily":"merriweather"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"55%","className":"header-search-box"} -->
<div class="wp-block-column is-vertically-aligned-center header-search-box" style="flex-basis:55%"><!-- wp:search {"label":"Search","showLabel":false,"placeholder":"What Are You Looking For","buttonText":"Search","buttonPosition":"button-inside","buttonUseIcon":true,"query":{"post_type":"product"},"style":{"border":{"color":"#4c4c4c33"}},"className":"header-search","fontSize":"extra-small"} /--></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"20%","className":"header-icons"} -->
<div class="wp-block-column is-vertically-aligned-center header-icons" style="flex-basis:20%"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
<div class="wp-block-group"><!-- wp:image {"id":175,"scale":"cover","sizeSlug":"full","linkDestination":"custom"} -->
<figure class="wp-block-image size-full"><a href="#"><img src="<?php echo esc_url( get_template_directory_uri() . '/images/wishlist.png'); ?>" alt="" class="wp-image-175" style="object-fit:cover"/></a></figure>
<!-- /wp:image -->

<!-- wp:woocommerce/mini-cart {"hasHiddenPrice":true,"iconColor":{"color":"#1b1c1e"},"style":{"layout":{"selfStretch":"fit","flexSize":null}}} /-->

<!-- wp:woocommerce/customer-account {"displayStyle":"icon_only","iconClass":"wc-block-customer-account__account-icon","style":{"elements":{"link":{"color":{"text":"#1b1c1e"}}},"color":{"text":"#1b1c1e"}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->