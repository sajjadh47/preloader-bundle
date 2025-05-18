<?php
/**
 * This file contains the definition of the Preloader_Bundle_Public class, which
 * is used to load the plugin's public-facing functionality.
 *
 * @package       Preloader_Bundle
 * @subpackage    Preloader_Bundle/public
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    2.0.0
 */
class Preloader_Bundle_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     string $plugin_name The name of the plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_styles() {
		if ( ! $this->should_plugin_load() ) {
			return;
		}

		wp_enqueue_style( $this->plugin_name, PRELOADER_BUNDLE_PLUGIN_URL . 'public/css/public.css', array(), $this->version, 'all' );

		$preloader_style = Preloader_Bundle::get_option( 'preloader_style', 'preloader_bundle_basic_settings', '12-segments.gif' );
		$gif             = trailingslashit( PRELOADER_BUNDLE_PLUGIN_URL . 'public/gifs' ) . $preloader_style;

		wp_add_inline_style( $this->plugin_name, "div#preloader-bundle { background-image:url( $gif ); }" );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_scripts() {
		if ( ! $this->should_plugin_load() ) {
			return;
		}

		$close_preloader                = Preloader_Bundle::get_option( 'close_preloader', 'preloader_bundle_basic_settings', 'document_load' );
		$seconds_to_close_the_preloader = Preloader_Bundle::get_option( 'seconds_to_close_the_preloader', 'preloader_bundle_basic_settings', 10 );

		wp_enqueue_script( $this->plugin_name, PRELOADER_BUNDLE_PLUGIN_URL . 'public/js/public.js', array( 'jquery' ), $this->version, false );

		wp_localize_script(
			$this->plugin_name,
			'PreloaderBundle',
			array(
				'ajaxurl'                    => admin_url( 'admin-ajax.php' ),
				'closePreloader'             => $close_preloader,
				'secondsToCloseThePreloader' => $seconds_to_close_the_preloader,
			)
		);
	}

	/**
	 * Add preloader html content in the site footer if enabled.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function wp_footer() {
		if ( $this->should_plugin_load() ) {
			echo wp_kses( '<div id="preloader-bundle"></div>', Sajjad_Dev_Settings_API::$allowed_html_tags );
		}
	}

	/**
	 * Check if plugins scripts should load based on different conditions.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function should_plugin_load() {
		static $load_plugin_scripts = null;

		if ( is_null( $load_plugin_scripts ) ) {
			$load_plugin_scripts   = false;
			$enabled               = Preloader_Bundle::get_option( 'enable_preloader', 'preloader_bundle_basic_settings', 'off' );
			$enable_preloader_page = Preloader_Bundle::get_option( 'enable_preloader_page', 'preloader_bundle_basic_settings', 'all' );

			if ( 'on' === $enabled ) {
				if ( 'home' === $enable_preloader_page ) {
					if ( ( is_front_page() && is_home() ) || ( is_front_page() ) || ( is_home() ) ) {
						$load_plugin_scripts = true;
					}
				} elseif ( 'all' === $enable_preloader_page ) {
					$load_plugin_scripts = true;
				}
			}
		}

		return $load_plugin_scripts;
	}
}
