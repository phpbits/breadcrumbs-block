/**
 * Gutenberg Blocks
 *
 * All blocks related JavaScript files should be imported here.
 * You can create a new block folder in this dir and include code
 * for that block here as well.
 *
 * All blocks should be included here since this is the file that
 * Webpack is compiling as the input file.
 */

/**
 * WordPress dependencies
 */
import { registerBlockType } from "@wordpress/blocks";

// Register Blocks
import * as breadcrumbs from "./blocks/breadcrumbs";

export function registerBlocks() {
	[breadcrumbs].forEach((block) => {
		if (!block) {
			// return;
		}
		const { name, category, settings } = block;
		registerBlockType(`${name}`, { category, ...settings });
	});
}
registerBlocks();
