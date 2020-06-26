/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";

/**
 * Internal dependencies
 */
import metadata from "./block.json";
import Edit from "./components/edit";

/**
 * Block constants
 */
const { name, category, attributes } = metadata;
const title = __("Breadcrumbs", "breadcrumbs-block");
const description = __(
	"Display breadcrumbs for the current post.",
	"breadcrumbs-block"
);
const keywords = [
	__("breadcrumbs", "breadcrumbs-block"),
	__("post", "breadcrumbs-block"),
	__("blog", "breadcrumbs-block"),
];

const settings = {
	title,
	description,
	icon: "excerpt-view",
	keywords,
	attributes,
	supports: {
		align: ["wide"],
	},
	edit() {
		return null;
	},
	save() {
		return null;
	},
};
export { name, category, settings };
