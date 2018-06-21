<?php
/**
 * Plugin Name: Woocommerce Quick Buy (by uCAT)
 * Plugin URI: 
 * Description: Reduces standard 4-stage purchasing to one click. One click adds the product basket and automatically switches to the payment page. Your visitors will buy your product directly without being lost between pages.
 * Version: 1.0.1
 * Author: Elena Zhyvohliad
 * Author URI: http://ucat.biz/
 * Requires at least: 4.9.6
 * Tested up to: 4.9.6
 *
 * Text Domain: uwcqb
 * Domain Path: /i18n/languages/
 *
 * @package uWC_Quick_Buy
 * @category Core
 * @author Elena ZHyvohliad
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if (function_exists('is_multisite') && is_multisite()) {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) )
        return;
}else{
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
        return; // Check if WooCommerce is active
}

if ( ! class_exists( 'uWC_Quick_Buy' ) ) :

/**
 * Main uWC_Quick_Buy Class.
 *
 * @class uWC_Quick_Buy
 * @version	1.0.0
 */
final class uWC_Quick_Buy {

	/**
	 * uWC_Quick_Buy version.
	 *
	 * @var string
	 */
	public $version = '1.0.1';

	/**
	 * The single instance of the class.
	 *
	 * @var uWC_Quick_Buy
	 */
	protected static $_instance = null;

	
	/**
	 * Main uWC_Quick_Buy Instance.
	 *
	 * Ensures only one instance of uWC_Quick_Buy is loaded or can be loaded.
	 *
	 * @static
	 * @see uWCQB()
	 * @return uWC_Quick_Buy - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'uwcqb' ), '0.0.1' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'uwcqb' ), '0.0.1' );
	}

	/**
	 * uWC_Quick_Buy Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->frontend_includes();
		$this->init_hooks();

		do_action( 'uwcqb_loaded' );
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ), 0 );
	}



	/**
	 * Define uWC_Quick_Buy Constants.
	 */
	private function define_constants() {
		$upload_dir = wp_upload_dir();

		$this->define( 'UWCQB_PLUGIN_FILE', __FILE__ );
		$this->define( 'UWCQB_PLUGIN_PATH', $this->plugin_path() );
		$this->define( 'UWCQB_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'UWCQB_VERSION', $this->version );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}


	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		include_once( 'includes/class-uwcqb-autoloader.php' );
		include_once( 'includes/class-uwcqb-assets.php' );
		include_once( 'includes/uwcqb-functions-core.php' );
		#include_once( 'includes/class-uwcqb-ajax.php' );
		include_once( 'includes/class-uwcqb-form-handler.php' );
		#include_once( 'includes/class-uwcqb-template-loader.php' );
		include_once( 'includes/class-uwcqb-shortcodes.php' );

		if ( $this->is_request( 'admin' ) ) {
			#include_once( 'includes/admin/class-uwcqb-admin.php' );
		}
		
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes() {		
		/*include_once( 'includes/class-uwcqb-frontend-assets.php' );
		include_once( 'includes/class-uwcqb-my-account.php' );
		include_once( 'includes/class-uwcqb-products.php' );*/
	}


	/**
	 * Init uWC_Quick_Buy when WordPress Initialises.
	 */
	public function init() {		
		// Before init action.
		do_action( 'before_uwcqb_init' );

		// Set up localisation.
		$this->load_plugin_textdomain();

		// Init action.
		do_action( 'uwcqb_init' );
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/woocommerce-quick-buy/uwcqb-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/uwcqb-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'uwcqb' );

		load_textdomain( 'uwcqb', WP_LANG_DIR . '/woocommerce-quick-buy/uwcqb-' . $locale . '.mo' );
		load_plugin_textdomain( 'uwcqb', false, plugin_basename( dirname( __FILE__ ) ) . '/i18n/languages' );
	}

	/**
	 * Get the plugin url.
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get the template path.
	 *
	 * @return string
	 */
	public function template_path() {
		return apply_filters( 'uwcqb_template_path', 'uwcqb/' );
	}

	/**
	 * Get Ajax URL.
	 * @return string
	 */
	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

}

endif;

/**
 * Main instance of uWC_Quick_Buy.
 *
 * Returns the main instance of uWC_Quick_Buy to prevent the need to use globals.
 *
 * @return uWC_Quick_Buy
 */
function uWCQB() {
	return uWC_Quick_Buy::instance();
}

// Global for backwards compatibility.
$GLOBALS['uWCQB'] = uWCQB();


register_activation_hook( __FILE__, 'flush_rewrite_rules' );