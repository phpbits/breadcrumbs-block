<?php
/**
 * Load assets for our blocks.
 *
 * @package Breadcrumbs_Block

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load general assets for our blocks.
 *
 * @since 1.0.0
 */
class Breadcrumbs_Block_Assets {


	/**
	 * This plugin's instance.
	 *
	 * @var Breadcrumbs_Block_Assets
	 */
	private static $instance;

	/**
	 * Registers the plugin.
	 */
	public static function register() {
		if ( null === self::$instance ) {
			self::$instance = new Breadcrumbs_Block_Assets();
		}
	}

	/**
	 * The base URL path (without trailing slash).
	 *
	 * @var string $url
	 */
	private $url;

	/**
	 * The plugin version.
	 *
	 * @var string $slug
	 */
	private $slug;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->slug = 'breadcrumbs-block';
		$this->url  = untrailingslashit( plugins_url( '/', dirname( __FILE__ ) ) );

		add_action( 'enqueue_block_assets', array( $this, 'block_assets' ) );
		add_action( 'init', array( $this, 'editor_assets' ) );
	}

	/**
	 * Enqueue block assets for use within Gutenberg.
	 *
	 * @access public
	 */
	public function block_assets() {

		// Styles.
		wp_enqueue_style(
			$this->slug . '-frontend',
			$this->url . '/build/style.build.css',
			array(),
			BREADCRUMBSBLOCK_VERSION
		);
	}

	/**
	 * Enqueue block assets for use within Gutenberg.
	 *
	 * @access public
	 */
	public function editor_assets() {

		if ( ! is_admin() ) {
			return;
		}
		if ( ! $this->is_edit_or_new_admin_page() ) {
			return;
		}

		// Styles.
		wp_register_style(
			$this->slug . '-editor',
			$this->url . '/build/editor.build.css',
			array(),
			BREADCRUMBSBLOCK_VERSION
		);

		// Scripts.
		wp_register_script(
			$this->slug . '-editor',
			$this->url . '/build/index.js',
			array_merge( $this->asset_file( 'editor', 'dependencies' ), array( 'wp-api', 'wp-compose' ) ),
			BREADCRUMBSBLOCK_VERSION,
			false
		);

	}

	/**
	 * Get asset file.
	 *
	 * @param string $handle Ass handle to reference.
	 * @param string $key What do we want to return: version or dependencies.
	 */
	public static function asset_file( $handle, $key ) {
		$default_asset_file = array(
			'dependencies' => array(),
			'version'      => BREADCRUMBSBLOCK_VERSION,
		);

		$asset_filepath = BREADCRUMBSBLOCK_PLUGIN_DIR . "/build/{$handle}.asset.php";
		$asset_file     = file_exists( $asset_filepath ) ? include $asset_filepath : $default_asset_file;

		if ( 'version' === $key ) {
			return $asset_file['version'];
		}

		if ( 'dependencies' === $key ) {
			return $asset_file['dependencies'];
		}
	}

	/**
	 * Checks if admin page is the 'edit' or 'new-post' screen.
	 *
	 * @return bool true or false
	 */
	public function is_edit_or_new_admin_page() {
		global $pagenow;
		// phpcs:ignore
		return ( is_admin() && ( $pagenow === 'post.php' || $pagenow === 'post-new.php' ) );
	}

}

Breadcrumbs_Block_Assets::register();