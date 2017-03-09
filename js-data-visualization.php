<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://github.com/jbutland/js-data-visulization
 * @since             1.0.0
 * @package           JS_Data_Visualization
 *
 * @wordpress-plugin
 * Plugin Name:       JS Data Visualization
 * Plugin URI:        http://example.com/js-data-visualization-uri/
 * Description:       A WordPress plugin that ingests a .csv file of survey data and outputs it as an interactive chart via shortcode. This plugin utilizes Chartjs http:// http://www.chartjs.org/.
 * Version:           1.0.0
 * Author:            Jonathan Butland
 * Author URI:        http://github.com/jbutland
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       js-data-visualization
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-js-data-visualization-activator.php
 */
function activate_js_data_visualization() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-js-data-visualization-activator.php';
	JS_Data_Visualization_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-js-data-visualization-deactivator.php
 */
function deactivate_js_data_visualization() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-js-data-visualization-deactivator.php';
	JS_Data_Visualization_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_js_data_visualization' );
register_deactivation_hook( __FILE__, 'deactivate_js_data_visualization' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-js-data-visualization.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_js_data_visualization() {

	$plugin = new JS_Data_Visualization();
	$plugin->run();

}
run_js_data_visualization();
