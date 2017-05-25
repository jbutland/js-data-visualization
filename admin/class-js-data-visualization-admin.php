<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://github.com/jbutland/js-data-visulization
 * @since      1.0.0
 *
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/admin
 * @author     Jon Butland jonathan.butland@gmail.com
 */
class JS_Data_Visualization_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $js_data_visualization    The ID of this plugin.
	 */
	private $js_data_visualization;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $js_data_visualization       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $js_data_visualization, $version ) {

		$this->js_data_visualization = $js_data_visualization;
		$this->version = $version;

		//add_action( 'admin_menu', array($this, 'jsdv_menu') );
	}

	public function jsdv_menu() {
		add_menu_page( 'JS Data Visualization Options', 'JS Data Visualization', 'manage_options', 'js-data-visulization', array($this, 'jsdv_plugin_options' ) );
	}


	public function jsdv_plugin_options() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/js-data-visualization-admin-display.php';
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->js_data_visualization, plugin_dir_url( __FILE__ ) . 'css/js-data-visualization-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->js_data_visualization, plugin_dir_url( __FILE__ ) . 'js/js-data-visualization-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'Chart.js', plugin_dir_url( __DIR__ ) . 'shared-files/js/Chart.js/Chart.bundle.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'utils.js', plugin_dir_url( __DIR__ ) . 'shared-files/js/Chart.js/utils.js', array( 'jquery' ), $this->version, false );
		//wp_localize_script( $this->js_data_visualization, 'js_data_visualization' , array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	}

}
