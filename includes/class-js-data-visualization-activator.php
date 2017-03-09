<?php

/**
 * Fired during plugin activation
 *
 *  @link              http://github.com/jbutland/js-data-visulization
 * @since      1.0.0
 *
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/includes
 * @author     Jon Butland jonathan.butland@gmail.com
 */
class JS_Data_Visualization_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		global $wpdb;

		//Adds database tables for storing survey data.
		$table_name = $wpdb->prefix . "jsdv_instance";
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
  	instance_id int(10) NOT NULL AUTO_INCREMENT,
 		instance_name varchar(55) DEFAULT '' NOT NULL,
  	PRIMARY KEY  (instance_id)
		) $charset_collate;";
		dbDelta( $sql );

		$table_name = $wpdb->prefix . "jsdv_instance_row";
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
		row_id int(10) not null AUTO_INCREMENT,
  	instance_id int(10) NOT NULL,
		row_counter int(10) NOT NULL,
 		row_hash varchar(256) NOT NULL,
		in_use int(1) NOT NULL,
  	PRIMARY KEY  (row_id)
		) $charset_collate;";
		dbDelta( $sql );

		$table_name = $wpdb->prefix . "jsdv_instance_row_values";
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
		id int(10) not null AUTO_INCREMENT,
  	instance_id int(10) NOT NULL,
		row_id int(10) NOT NULL,
		value_key_id int(10) NOT NULL,
 		value_key text DEFAULT '' NOT NULL,
		value varchar(256) DEFAULT '' NOT NULL,
  	PRIMARY KEY  (id)
		) $charset_collate;";
		dbDelta( $sql );

	}

}
