<?php
/**
 * Title: Latest Products
 * Slug: organic-food-store/latest-products
 * Categories: organic-food-store, latest-products
 */
?>
<!-- wp:group {"className":"product-section","layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group product-section"><!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"textTransform":"capitalize","fontStyle":"normal","fontWeight":"700"},"color":{"text":"#1b1c1e"}},"backgroundColor":"base","className":"productsec-heading","fontSize":"big","fontFamily":"josefin_sans"} -->
<h3 class="wp-block-heading has-text-align-center productsec-heading has-base-background-color has-text-color has-background has-josefin-sans-font-family has-big-font-size" style="color:#1b1c1e;font-style:normal;font-weight:700;text-transform:capitalize">Latest Trending</h3>
<!-- /wp:heading -->

<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"constrained","contentSize":"90%"}} -->
<div class="wp-block-group alignwide" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:query {"queryId":15,"query":{"perPage":"12","pages":0,"offset":0,"postType":"product","order":"asc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"","inherit":false,"__woocommerceAttributes":[],"__woocommerceStockStatus":["instock","outofstock","onbackorder"]},"namespace":"woocommerce/product-query","align":"wide"} -->
<div class="wp-block-query alignwide"><!-- wp:post-template {"className":"products-block-post-template","layout":{"type":"grid","columnCount":4},"__woocommerceNamespace":"woocommerce/product-query/product-template"} -->
<!-- wp:woocommerce/product-image {"showSaleBadge":false,"imageSizing":"thumbnail","isDescendentOfQueryLoop":true} /-->

<!-- wp:post-title {"textAlign":"center","level":3,"isLink":true,"style":{"spacing":{"margin":{"bottom":"0.75rem","top":"0"}}},"fontSize":"medium","__woocommerceNamespace":"woocommerce/product-query/product-title"} /-->

<!-- wp:woocommerce/product-rating {"isDescendentOfQueryLoop":true,"textAlign":"center"} /-->

<!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"textAlign":"center"} /-->

<!-- wp:woocommerce/product-button {"textAlign":"center","width":100,"isDescendentOfQueryLoop":true,"fontSize":"small"} /-->
<!-- /wp:post-template -->

<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"placeholder":"Add text or blocks that will display when a query returns no results."} -->
<p>No Products Found</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->