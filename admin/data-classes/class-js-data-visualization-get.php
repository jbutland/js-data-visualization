<?php
class JS_Data_Visualization_Get_Data
{

  public function get_instances()
  {
    global $wpdb;
    $instances = $wpdb->get_results("SELECT * FROM wp_jsdv_instance", ARRAY_A);
    return $instances;

  }

  public function get_instance_questions($instance_id)
  {
    global $wpdb;
    $questions = $wpdb->get_results("SELECT DISTINCT wp_jsdv_instance_row_values.value_key_id, wp_jsdv_instance_row_values.value_key
                                    FROM wp_jsdv_instance_row_values
                                    INNER JOIN wp_jsdv_instance_row
                                    ON wp_jsdv_instance_row.row_id = wp_jsdv_instance_row_values.row_id
                                    WHERE wp_jsdv_instance_row_values.instance_id = $instance_id AND wp_jsdv_instance_row.in_use = 1", ARRAY_A);
    return $questions;
  }

}
 ?>
