<?php
/**
 * Plugin Name: Breadcrumbs Block
 * Plugin URI: http://wordpress.org/plugins/breadcrumbs-block
 * Description: Breadcrumbs block for WordPress Gutenberg block editor.
 * Version: 1.0
 * Author: Jeffrey Carandang
 * Author URI: https://jeffreycarandang.com/
 * Text Domain: breadcrumbs-block
 * Domain Path: languages
 *
 * @category Gutenberg
 * @author Jeffrey Carandang
 * @version 1.0
 * @package Breadcrumbs_Block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Breadcrumbs_Block' ) ) :

	/**
	 * Main Breadcrumbs Block Class.
	 *
	 * @since  1.0
	 */
	final class Breadcrumbs_Block {
		/**
		 * This plugin's instance.
		 *
		 * @var Breadcrumbs_Block The one true Breadcrumbs_Block
		 * @since  1.0
		 */
		private static $instance;
		/**
		 * Main Breadcrumbs_Block Instance.
		 *
		 * Insures that only one instance of Breadcrumbs_Block exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.0
		 * @static
		 * @return object|Breadcrumbs_Block The one true Breadcrumbs_Block
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Breadcrumbs_Block ) ) {
				self::$instance = new Breadcrumbs_Block();
				self::$instance->init();
				self::$instance->setup_constants();
				self::$instance->asset_suffix();
				self::$instance->includes();
			}
			return self::$instance;
		}
		/**
		 * Throw error on object clone.
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.0.0
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheating huh?', 'breadcrumbs-block' ), '1.0' );
		}
		/**
		 * Disable unserializing of the class.
		 *
		 * @since 1.0.0
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheating huh?', 'breadcrumbs-block' ), '1.0' );
		}
		/**
		 * Setup plugin constants.
		 *
		 * @access private
		 * @since 1.0.0
		 * @return void
		 */
		private function setup_constants() {
			$this->define( 'BREADCRUMBSBLOCK_DEBUG', true );
			$this->define( 'BREADCRUMBSBLOCK_VERSION', '1.0' );
			$this->define( 'BREADCRUMBSBLOCK_HAS_PRO', false );
			$this->define( 'BREADCRUMBSBLOCK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			$this->define( 'BREADCRUMBSBLOCK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			$this->define( 'BREADCRUMBSBLOCK_PLUGIN_FILE', __FILE__ );
			$this->define( 'BREADCRUMBSBLOCK_PLUGIN_BASE', plugin_basename( __FILE__ ) );
		}
		/**
		 * Define constant if not already set.
		 *
		 * @param  string|string $name Name of the definition.
		 * @param  string|bool   $value Default value.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}
		/**
		 * Include required files.
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function includes() {
			require_once BREADCRUMBSBLOCK_PLUGIN_DIR . 'includes/class-breadcrumbs-block-assets.php';
			// require_once BREADCRUMBSBLOCK_PLUGIN_DIR . 'includes/get-dynamic-blocks.php';
		}
		/**
		 * Load actions
		 *
		 * @return void
		 */
		private function init() {
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ), 99 );
			add_action( 'enqueue_block_editor_assets', array( $this, 'block_localization' ) );
		}

		/**
		 * Change the plugin's minified or src file name, based on debug mode.
		 *
		 * @since 1.0.0
		 */
		public function asset_suffix() {
			if ( true === BREADCRUMBSBLOCK_DEBUG ) {
				define( 'BREADCRUMBSBLOCK_ASSET_SUFFIX', null );
			} else {
				define( 'BREADCRUMBSBLOCK_ASSET_SUFFIX', '.min' );
			}
		}

		/**
		 * If debug is on, serve unminified source assets.
		 *
		 * @since 1.0.0
		 * @param string|string $type The type of resource.
		 * @param string|string $directory Any extra directories needed.
		 */
		public function asset_source( $type = 'js', $directory = null ) {
			if ( 'js' !== $type ) {
				return BREADCRUMBSBLOCK_PLUGIN_URL . 'build/css/' . $directory;
			}
			if ( true === BREADCRUMBSBLOCK_DEBUG ) {
				return BREADCRUMBSBLOCK_PLUGIN_URL . 'src/' . $type . '/' . $directory;
			}
			return BREADCRUMBSBLOCK_PLUGIN_URL . 'build/' . $type . '/' . $directory;
		}
		/**
		 * Loads the plugin language files.
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'breadcrumbs-block', false, dirname( plugin_basename( BREADCRUMBSBLOCK_PLUGIN_DIR ) ) . '/languages/' );
		}
		/**
		 * Enqueue localization data for our blocks.
		 *
		 * @access public
		 */
		public function block_localization() {
			if ( function_exists( 'wp_set_script_translations' ) ) {
				wp_set_script_translations( 'breadcrumbs-block' );
			}
		}
	}
endif; // End if class_exists check.
/**
 * The main function for that returns Breadcrumbs_Block
 *
 * The main function responsible for returning the one true Breadcrumbs_Block
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $blockopts = Breadcrumbs_Block(); ?>
 *
 * @since 1.0
 * @return object|Breadcrumbs_Block The one true Breadcrumbs_Block Instance.
 */
function breadcrumbsblock_fn() {
	return Breadcrumbs_Block::instance();
}
// Get Plugin Running.
if ( function_exists( 'is_multisite' ) && is_multisite() ) {
	// Get Plugin Running. Load on plugins_loaded action to avoid issue on multisite.
	add_action( 'plugins_loaded', 'breadcrumbsblock_fn' );
} else {
	breadcrumbsblock_fn();
}