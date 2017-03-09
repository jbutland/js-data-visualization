<?php

/**
 * Fired during plugin deactivation
 *
 *  @link              http://github.com/jbutland/js-data-visulization
 * @since      1.0.0
 *
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/includes
 * @author     Jon Butland jonathan.butland@gmail.com
 */
class JS_Data_Visualization_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;
		$wpdb->query( "DROP TABLE IF EXISTS wp_jsdv_instance" );
		$wpdb->query( "DROP TABLE IF EXISTS wp_jsdv_instance_row" );
		$wpdb->query( "DROP TABLE IF EXISTS wp_jsdv_instance_row_values" );
	}

}
