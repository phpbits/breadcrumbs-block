<?php
/**
 * Server-side rendering of the `phpbits/breadcrumbs` block.
 *
 * @package Breadcrumbs_Block
 */

/**
 * Renders the block on server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string Returns the block content.
 */
function phpbits_render_breadcrumbs_block( $attributes ) {
	
	return 'This is the content';
}
/**
 * Registers the block on server.
 */
function phpbits_register_breadcrumbs_block() {
	// Return early if this function does not exist.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	// Load attributes from block.json.
	ob_start();
	include BREADCRUMBSBLOCK_PLUGIN_DIR . 'src/blocks/breadcrumbs/block.json';
	$metadata = json_decode( ob_get_clean(), true );
	register_block_type(
		$metadata['name'],
		array(
			'editor_script'   => 'breadcrumbs-block-editor',
			'editor_style'    => 'breadcrumbs-block-editor-css',
			'style'           => 'breadcrumbs-block-frontend',
			'attributes'      => $metadata['attributes'],
			'render_callback' => 'phpbits_render_breadcrumbs_block',
		)
	);
}

add_action( 'init', 'phpbits_register_breadcrumbs_block' );