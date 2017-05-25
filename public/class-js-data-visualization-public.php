<?php

/**
 * The public-facing functionality of the plugin.
 *
 *  @link              http://github.com/jbutland/js-data-visulization
 * @since      1.0.0
 *
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/public
 * @author     Jon Butland jonathan.butland@gmail.com
 */
class JS_Data_Visualization_Public {

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
	 * @param      string    $js_data_visualization       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $js_data_visualization, $version ) {

		$this->js_data_visualization = $js_data_visualization;
		$this->version = $version;


	}

	public function get_chart( $atts ){
	   $instance_id = $atts['id'];
	   echo $instce_id;
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function get_public_chart( $atts ){
		ob_start();
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/js-data-visualization-public-display.php';
		$ReturnString = ob_get_contents();
		ob_end_clean();
    	return $ReturnString;

 	}

	public function enqueue_styles() {


		wp_enqueue_style( $this->js_data_visualization, plugin_dir_url( __FILE__ ) . 'css/js-data-visualization-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->js_data_visualization, plugin_dir_url( __FILE__ ) . 'js/js-data-visualization-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->js_data_visualization, 'MyAjax' , array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'ajax-example-nonce' ) ) );
		wp_enqueue_script( 'Chart.js', plugin_dir_url( __DIR__ ) . 'shared-files/js/Chart.js/Chart.bundle.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'utils.js', plugin_dir_url( __DIR__ ) . 'shared-files/js/Chart.js/utils.js', array( 'jquery' ), $this->version, false );


	}
	public function register_shortcodes(){
		add_shortcode( 'js-data-visualization', array($this,'get_public_chart' ));
	}

}
