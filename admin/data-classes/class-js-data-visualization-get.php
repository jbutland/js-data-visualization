<?php
class JS_Data_Visualization_Get_Data
{


  public function __construct()
  {
     require_once plugin_dir_path( dirname( __FILE__ ) ) . '/data-classes/class-js-data-visualization-statistics.php';


  }

  public function get_instances()
  {
    global $wpdb;
    $instances = $wpdb->get_results("SELECT * FROM wp_jsdv_instance", ARRAY_A);
    return $instances;

  }

  public function get_instance_questions($instance_id)
  {
    if(!empty($_POST['instance_id']))
    {
      $instance_id = $_POST['instance_id'];
      global $wpdb;
      if(!empty($value_key_id))
      {

      }
      else
      {

      $questions = $wpdb->get_results("SELECT DISTINCT wp_jsdv_instance_row_values.value_key_id, wp_jsdv_instance_row_values.value_key
                                      FROM wp_jsdv_instance_row_values
                                      INNER JOIN wp_jsdv_instance_row
                                      ON wp_jsdv_instance_row.row_id = wp_jsdv_instance_row_values.row_id
                                      WHERE wp_jsdv_instance_row_values.instance_id = $instance_id AND wp_jsdv_instance_row.in_use = 1", ARRAY_A);
      ?>
      <h3>Which Questions do you want to Chart?</h3>
      <?php
      foreach ($questions as $question) {
      ?>
        <input type="checkbox" class="questions_to_chart" name="<?php echo $question['value_key'] ?>" value="<?php echo $question['value_key_id']; ?>"> <?php echo $question['value_key']; ?><br>
      <?php
      }
      }
      //print_r($questions);
    }

    die();
  }

  private function get_survey_data($instance_id, $value_key_id, $segments = NULL)
  {
     global $wpdb;
     if(!empty($segments))
     {
        $segments_query = $this->get_segments($segments);
     }
     else
     {
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
    foreach($segments as $key => $value)
    {
      $sql .= " INNER JOIN wp_jsdv_instance_row_values as $a
      ON $a.row_id = value_table.row_id AND $a.value_key_id = $key AND $a.value = '$value'";
      $a++;
    }
    return $sql;
  }

  public function populate_chart()
  {
     $process_data = new Process_Data_Functions;
     if(!empty($_POST['chart_data']))
     {
       parse_str($_POST['chart_data'], $chart_data);
        $holding_array = $this->get_survey_data($chart_data['instance_id'], $chart_data['question']);
        foreach ($holding_array as $array) {
           $data_array[] = $array[0];
        }

        $processed = $process_data->process_data($data_array);
        echo json_encode($processed);
     }
     die();
  }

}

 ?>
