<?php
/**
 * This file contains the definition of the Preloader_Bundle_Admin class, which
 * is used to load the plugin's admin-specific functionality.
 *
 * @package       Preloader_Bundle
 * @subpackage    Preloader_Bundle/admin
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    2.0.0
 */
class Preloader_Bundle_Admin {
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
	 * The plugin options api wrapper object.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       array $settings_api Holds the plugin options api wrapper class object.
	 */
	private $settings_api;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     string $plugin_name The name of this plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
		$this->settings_api = new Sajjad_Dev_Settings_API();
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_scripts() {
		$current_screen = get_current_screen();

		// check if current page is plugin settings page.
		if ( 'toplevel_page_preloader-bundle' === $current_screen->id ) {
			wp_enqueue_script( $this->plugin_name, PRELOADER_BUNDLE_PLUGIN_URL . 'admin/js/admin.js', array( 'jquery' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name,
				'PreloaderBundle',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);
		}
	}

	/**
	 * Adds a settings link to the plugin's action links on the plugin list table.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $links The existing array of plugin action links.
	 * @return    array $links The updated array of plugin action links, including the settings link.
	 */
	public function add_plugin_action_links( $links ) {
		$links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=preloader-bundle' ) ), __( 'Settings', 'preloader-bundle' ) );

		return $links;
	}

	/**
	 * Adds the plugin settings page to the WordPress dashboard menu.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function admin_menu() {
		add_menu_page(
			__( 'Preloader Bundle', 'preloader-bundle' ),
			__( 'Preloader Bundle', 'preloader-bundle' ),
			'manage_options',
			'preloader-bundle',
			array( $this, 'menu_page' ),
			'dashicons-update'
		);
	}

	/**
	 * Renders the plugin menu page content.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function menu_page() {
		$this->settings_api->show_forms();
	}

	/**
	 * Initializes admin-specific functionality.
	 *
	 * This function is hooked to the 'admin_init' action and is used to perform
	 * various administrative tasks, such as registering settings, enqueuing scripts,
	 * or adding admin notices.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function admin_init() {
		// set the settings.
		$this->settings_api->set_sections( $this->get_settings_sections() );

		$this->settings_api->set_fields( $this->get_settings_fields() );

		// initialize settings.
		$this->settings_api->admin_init();
	}

	/**
	 * Returns the settings sections for the plugin settings page.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    array An array of settings sections, where each section is an array
	 *                  with 'id' and 'title' keys.
	 */
	public function get_settings_sections() {
		$settings_sections = array(
			array(
				'id'    => 'preloader_bundle_basic_settings',
				'title' => __( 'General Settings', 'preloader-bundle' ),
			),
		);

		/**
		 * Filters the plugin settings sections.
		 *
		 * This filter allows you to modify the plugin settings sections.
		 * You can use this filter to add/remove/edit any settings sections.
		 *
		 * @since     2.0.0
		 * @param     array $settings_sections Default settings sections.
		 * @return    array $settings_sections Modified settings sections.
		 */
		return apply_filters( 'preloader_bundle_settings_sections', $settings_sections );
	}

	/**
	 * Returns all the settings fields for the plugin settings page.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    array An array of settings fields, organized by section ID.  Each
	 *                  section ID is a key in the array, and the value is an array
	 *                  of settings fields for that section. Each settings field is
	 *                  an array with 'name', 'label', 'type', 'desc', and other keys
	 *                  depending on the field type.
	 */
	public function get_settings_fields() {
		$gifs      = array();
		$directory = PRELOADER_BUNDLE_PLUGIN_PATH . 'public/gifs';

		if ( file_exists( $directory ) ) {
			foreach ( glob( "$directory/*.gif" ) as $file ) {
				$file_name          = basename( $file );
				$gifs[ $file_name ] = trailingslashit( PRELOADER_BUNDLE_PLUGIN_URL . 'public/gifs' ) . $file_name;
			}
		}

		$settings_fields = array(
			'preloader_bundle_basic_settings' => array(
				array(
					'name'  => 'enable_preloader',
					'label' => __( 'Enable Preloader', 'preloader-bundle' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Checking this box will enable the Preloader.', 'preloader-bundle' ),
				),
				array(
					'name'    => 'enable_preloader_page',
					'label'   => __( 'Enable Preloader For', 'preloader-bundle' ),
					'type'    => 'select',
					'options' => array(
						'all'  => 'All Pages',
						'home' => 'Only For Home Page',
					),
					'default' => 'all',
					'desc'    => __( 'Select the preloader loading page.', 'preloader-bundle' ),
				),
				array(
					'name'    => 'close_preloader',
					'label'   => __( 'Close Preloader', 'preloader-bundle' ),
					'type'    => 'select',
					'options' => array(
						'document_load' => 'After Page Loaded Completely',
						'seconds_later' => 'After Specific Seconds Later',
					),
					'default' => 'document_load',
					'desc'    => __( 'Select the preloader closing time. If you select <b>After Specific Seconds Later</b> then please add seconds value below. Default 10s.', 'preloader-bundle' ),
				),
				array(
					'name'    => 'seconds_to_close_the_preloader',
					'label'   => __( 'Specific Seconds', 'preloader-bundle' ),
					'type'    => 'number',
					'desc'    => __( 'How many seconds after preloader will be closed? Default 5s.', 'preloader-bundle' ),
					'default' => 5,
				),
				array(
					'name'    => 'preloader_style',
					'label'   => __( 'Preloader Style', 'preloader-bundle' ),
					'type'    => 'radio_image',
					'options' => $gifs,
				),
			),
		);

		/**
		 * Filters the plugin settings fields.
		 *
		 * This filter allows you to modify the plugin settings fields.
		 * You can use this filter to add/remove/edit any settings field.
		 *
		 * @since     2.0.0
		 * @param     array $settings_fields Default settings fields.
		 * @return    array $settings_fields Modified settings fields.
		 */
		return apply_filters( 'preloader_bundle_settings_fields', $settings_fields );
	}
}
