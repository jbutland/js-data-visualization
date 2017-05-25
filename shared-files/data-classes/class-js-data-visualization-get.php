<?php
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
    public function get_question_name($instance_id, $value_key_id)
    {
        global $wpdb;
        $name = $wpdb->get_var("SELECT DISTINCT wp_jsdv_instance_row_values.value_key
        FROM wp_jsdv_instance_row_values
       INNER JOIN wp_jsdv_instance_row
       ON wp_jsdv_instance_row.row_id = wp_jsdv_instance_row_values.row_id
       WHERE wp_jsdv_instance_row_values.instance_id = $instance_id AND wp_jsdv_instance_row.in_use = 1 AND wp_jsdv_instance_row_values.value_key_id = $value_key_id");
        return $name;
    }
    private function get_questions($instance_id, $value_key_ids = null)
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
    public function get_question_responses($instance_id = null, $value_key_id = null)
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
    public function get_instance_questions($instance_id = null, $value_key_ids = null)
    {
        $instance_id = $_POST['instance_id'];

        if ($value_key_ids == null) {
            $questions = $this->get_questions($instance_id); ?>
            <h3>Which Question do you want to Chart?</h3>
            <?php
            $i = 0;
            foreach ($questions as $question) {
                ?>
               <input type="hidden" name="questions[instance_id]" value="<?php echo $instance_id; ?>"/>
               <input type="hidden" name="questions[<?php echo $i; ?>][name]" value="<?php echo $question['value_key']; ?>">
               <input type="checkbox" class="questions_to_chart" name="questions[<?php echo $i; ?>][id]" value="<?php echo $question['value_key_id']; ?>"> <?php  echo $question['value_key']; ?>
               <label>Choose chart type</label>
               <select class="chart_type" name="questions[<?php echo $i; ?>][type]">
                  <option value="bar" selected>Bar</option>
                  <option value="line">Line</option>
                  <option value="pie">Pie</option>
               </select><br>
               <div style="padding-left:5em;">
               <label>Question Segments</label><br>
                  <?php

                      foreach ($questions as $segment) {
                          if ($question['value_key_id'] != $segment['value_key_id']) {
                              ?>
                         <input type="checkbox" class="questions_to_chart" name="questions[<?php echo $i; ?>][segments][]" value="<?php echo $segment['value_key_id']; ?>" > <?php  echo $segment['value_key']; ?>
                         <br>

                  <?php

                          }
                      } ?>
                </div>
                  <br>
          <?php
               $i++;
            } ?>
            <h4> Describe this instance</h4>
            <textarea name="instance_description" id="instance_description" rows="4" cols="50"></textarea><br>
            <input type="submit" value="Preview">
            <?php

        }

        die();
    }

    private function get_survey_data($instance_id, $value_key_id, $segments = null)
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

    public function populate_chart()
    {
        $process_data = new Process_Data_Functions;
        if (!empty($_POST['chart_data'])) {
            parse_str($_POST['chart_data'], $chart_data);
            $question_info = unserialize($chart_data['question']);
            if (!empty($chart_data['segment'])) {
                $segments = $chart_data['segment'];
            } else {
                $segments = null;
            }
            $holding_array = $this->get_survey_data($chart_data['instance_id'], $question_info['id'], $segments);

            foreach ($holding_array as $array) {
                $data_array[] = $array[0];
            }

            $processed = $process_data->process_data($data_array);
            $processed['chart_type'] = $question_info['type'];
            echo json_encode($processed);
        }
        die();
    }
    public function parse_chart_options()
    {
        if (!empty($_POST['settings_data'])) {
            parse_str($_POST['settings_data'], $settings);
            $questions = $settings['questions'];
            $chart_options['instance_id'] = $questions['instance_id']; ?>

         <input type="hidden" name="instance_id" value="<?php echo $questions['instance_id'] ?>"/>
         <select class="question" name="question">
         <?php
         $i = 0;
            foreach ($questions as $question) {
                if (!empty($question['id'])) {
                    $chart_options['questions'][$i]['id'] = $question['id'];
                    if (!empty($question['segments'])) {
                        $chart_options['questions'][$i]['segments'] =  implode($question['segments'], ",");
                    }

                    $chart_options['questions'][$i]['type'] = $question['type']; ?>
               <option value='<?php echo serialize($chart_options['questions'][$i]); ?>'><?php echo $question['name']; ?></option>
               <?php
               $i++;
                }
            } ?>
         </select>

         <?php

         $chart_options['instance_description'] = $settings['instance_description'];
            $this->saveInstanceOptions($questions['instance_id'], json_encode($chart_options));
        }
        die();
    }

    private function saveInstanceOptions($instance_id, $options_string)
    {
        global $wpdb;
        $wpdb->update(
         'wp_jsdv_instance',
         array( 'instance_options' => $options_string),
         array( 'instance_id' => $instance_id)
      );
    }
    public function initilaizePublicChart($instance_id)
    {
        $chart_options = $this->getChartOptions($instance_id);
        $chart_decoded = json_decode($chart_options[0]['instance_options']);
        return $chart_decoded;
    }

    private function getChartOptions($instance_id)
    {
        global $wpdb;
        $chart_options = $wpdb->get_results(" SELECT instance_options FROM wp_jsdv_instance WHERE instance_id = $instance_id ", ARRAY_A);
        return $chart_options;
    }


    public function populate_segments()
    {
        $test = 1;
        if (!empty($_POST['chart_data'])) {
            parse_str($_POST['chart_data'], $chart_data);
            $question_data = unserialize($chart_data['question']);
            $segment_questions = $this->get_questions($chart_data['instance_id'], $question_data['segments']);
            ?>
            <span class="chart_label">Narrow the range of the data by selectiong additional data segments.</span></br>
            <?php
            foreach ($segment_questions as $key => $segment_question) {
                ?>

               <select class="segment" name="segment[<?php echo $segment_question['value_key_id']; ?>]">
                  <option value><?php echo $segment_question['value_key']; ?></option>
                   <?php
                   $responses = $this->get_question_responses($chart_data['instance_id'], $segment_question['value_key_id']);
                   foreach ($responses as $response) {
                    ?>
                    <option value="<?php echo $response; ?>"><?php echo $response; ?></option>
                    <?php
                 }
                ?>
             </select><br>
            <?php

            }
        }
        die();
    }
}
 ?>
