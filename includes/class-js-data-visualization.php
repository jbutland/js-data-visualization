<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 *  @link              http://github.com/jbutland/js-data-visulization
 * @since      1.0.0
 *
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/includes
 * @author     Jon Butland jonathan.butland@gmail.com
 */
class JS_Data_Visualization {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      JS_Data_Visualization_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $js_data_visualization    The string used to uniquely identify this plugin.
	 */
	protected $js_data_visualization;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->js_data_visualization = 'js-data-visualization';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - JS_Data_Visualization_Loader. Orchestrates the hooks of the plugin.
	 * - JS_Data_Visualization_i18n. Defines internationalization functionality.
	 * - JS_Data_Visualization_Admin. Defines all hooks for the admin area.
	 * - JS_Data_Visualization_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-js-data-visualization-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-js-data-visualization-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-js-data-visualization-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-js-data-visualization-public.php';

		$this->loader = new JS_Data_Visualization_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the JS_Data_Visualization_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new JS_Data_Visualization_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new JS_Data_Visualization_Admin( $this->get_js_data_visualization(), $this->get_version() );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'jsdv_menu' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'shared-files/data-classes/class-js-data-visualization-get.php';
		$data_get  = new JS_Data_Visualization_Get_Data;
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_instance_questions', $data_get, 'get_instance_questions' );
    	$this->loader->add_action( 'wp_ajax_get_instance_questions', $data_get, 'get_instance_questions' );
		$this->loader->add_action( 'wp_ajax_nopriv_populate_chart', $data_get, 'populate_chart' );
		$this->loader->add_action( 'wp_ajax_populate_chart', $data_get, 'populate_chart' );
		$this->loader->add_action( 'wp_ajax_nopriv_parse_chart_options', $data_get, 'parse_chart_options' );
		$this->loader->add_action( 'wp_ajax_parse_chart_options', $data_get, 'parse_chart_options' );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new JS_Data_Visualization_Public( $this->get_js_data_visualization(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'shared-files/data-classes/class-js-data-visualization-get.php';

		$data_get  = new JS_Data_Visualization_Get_Data;

		if ( is_admin() ) {
			$this->loader->add_action( 'wp_ajax_populate_chart', $data_get, 'populate_chart' );
			$this->loader->add_action( 'wp_ajax_nopriv_populate_chart', $data_get, 'populate_chart' );
			$this->loader->add_action( 'wp_ajax_populate_segments', $data_get, 'populate_segments' );
			$this->loader->add_action( 'wp_ajax_nopriv_populate_segments', $data_get, 'populate_segments' );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_js_data_visualization() {
		return $this->js_data_visualization;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    JS_Data_Visualization_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
