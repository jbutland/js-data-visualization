<?php
/**
 * Retrieves chart related data
 *
 *  @link              http://github.com/jbutland/js-data-visulization
 * @since      1.0.0
 *
 *
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/shared-files/data-classes
 * @author     Jon Butland jonathan.butland@gmail.com
 */
class JS_Data_Visualization_Get_Data
{
    public function __construct()
    {
        require_once plugin_dir_path(dirname(__DIR__)) . 'shared-files/data-classes/class-js-data-visualization-statistics.php';
    }

    public function get_instances()
    {
        global $wpdb;
        $instances = $wpdb->get_results("SELECT * FROM wp_jsdv_instance", ARRAY_A);
        return $instances;
    }
    protected function get_question_name($instance_id, $value_key_id)
    {
        global $wpdb;
        $name = $wpdb->get_var("SELECT DISTINCT wp_jsdv_instance_row_values.value_key
        FROM wp_jsdv_instance_row_values
       INNER JOIN wp_jsdv_instance_row
       ON wp_jsdv_instance_row.row_id = wp_jsdv_instance_row_values.row_id
       WHERE wp_jsdv_instance_row_values.instance_id = $instance_id AND wp_jsdv_instance_row.in_use = 1 AND wp_jsdv_instance_row_values.value_key_id = $value_key_id");
        return $name;
    }
    protected function get_questions($instance_id, $value_key_ids = null)
    {
        global $wpdb;
        if ($value_key_ids == null) {
            $questions = $wpdb->get_results("SELECT DISTINCT wp_jsdv_instance_row_values.value_key_id, wp_jsdv_instance_row_values.value_key
           FROM wp_jsdv_instance_row_values
           INNER JOIN wp_jsdv_instance_row
           ON wp_jsdv_instance_row.row_id = wp_jsdv_instance_row_values.row_id
           WHERE wp_jsdv_instance_row_values.instance_id = $instance_id AND wp_jsdv_instance_row.in_use = 1", ARRAY_A);
        } else {
            $questions = $wpdb->get_results("SELECT DISTINCT wp_jsdv_instance_row_values.value_key_id, wp_jsdv_instance_row_values.value_key
             FROM wp_jsdv_instance_row_values
            INNER JOIN wp_jsdv_instance_row
            ON wp_jsdv_instance_row.row_id = wp_jsdv_instance_row_values.row_id
            WHERE wp_jsdv_instance_row_values.instance_id = $instance_id AND wp_jsdv_instance_row.in_use = 1 AND wp_jsdv_instance_row_values.value_key_id IN($value_key_ids)", ARRAY_A);
        }
        return $questions;
    }
    protected function get_question_responses($instance_id = null, $value_key_id = null)
    {
        global $wpdb;
        $responses = $wpdb->get_col("SELECT DISTINCT wp_jsdv_instance_row_values.value
        FROM wp_jsdv_instance_row_values
       INNER JOIN wp_jsdv_instance_row
       ON wp_jsdv_instance_row.row_id = wp_jsdv_instance_row_values.row_id
       WHERE wp_jsdv_instance_row_values.instance_id = $instance_id AND wp_jsdv_instance_row.in_use = 1 AND wp_jsdv_instance_row_values.value_key_id = $value_key_id");
        natsort($responses);
        return $responses;
    }

    protected function get_survey_data($instance_id, $value_key_id, $segments = null)
    {
        global $wpdb;
        if (!empty($segments)) {
            $segments_query = $this->get_segments($segments);
        } else {
            $segments_query = '';
        }
        $sql = "SELECT value_table.value
     FROM wp_jsdv_instance_row_values as value_table
     INNER JOIN wp_jsdv_instance_row as row_table
     ON row_table.row_id = value_table.row_id AND row_table.in_use = 1
     $segments_query
     WHERE value_table.instance_id = $instance_id
     AND value_table.value_key_id = $value_key_id
     order by value asc;";
        $results = $wpdb->get_results($sql, ARRAY_N);
        return $results;
    }

    private function get_segments($segments)
    {
        $a = "a";
        $sql = null;
        foreach ($segments as $key => $value) {
            if ($value != "") {
                $sql .= " INNER JOIN wp_jsdv_instance_row_values as $a
      ON $a.row_id = value_table.row_id AND $a.value_key_id = $key AND $a.value = '$value'";
            }

            $a++;
        }
        return $sql;
    }

    protected function getChartOptions($instance_id = null)
    {
        global $wpdb;
        $chart_options = $wpdb->get_results(" SELECT instance_options FROM wp_jsdv_instance WHERE instance_id = $instance_id ", ARRAY_A);
        return $chart_options;
    }

}
 ?>
