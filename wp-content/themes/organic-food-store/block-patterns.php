<?php
/**
 * Organic Food Store: Block Patterns
 *
 * @since Organic Food Store 1.0.1
 */

/**
 * Registers block patterns and categories.
 *
 * @since Organic Food Store 1.0.1
 *
 * @return void
 */
function organic_food_store_register_block_patterns() {
	$block_pattern_categories = array(
		'organic-food-store'    => array( 'label' => __( 'Organic Food Store', 'organic-food-store' ) ),
	);

	$block_pattern_categories = apply_filters( 'organic_food_store_block_pattern_categories', $block_pattern_categories );

	foreach ( $block_pattern_categories as $name => $properties ) {
		if ( ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $name ) ) {
			register_block_pattern_category( $name, $properties );
		}
	}
}
add_action( 'init', 'organic_food_store_register_block_patterns', 9 );
