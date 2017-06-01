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
class JS_Data_Visualization_Get_Chart extends JS_Data_Visualization_Get_Data
{
   private $instance_id = null;
   private $questions;
   private $segments = array();
   private $description = null;

    public function __construct()
    {

    }

    // returns all necessary data for the chart to be populated via Javascript in JSON encoded array.
    public function initilaizeAdminChart()
    {
      if(!empty($_POST['instance_id']))
      {
        $instance_id = $_POST['instance_id'];
        echo 'To add this chart to a Page or Post use the shortcode [js-data-visualization id="'.$instance_id.'"]';
        $this->initilaizePublicChart($instance_id);

      }

      die();
    }
    public function initilaizePublicChart($instance_id)
    {
        $this->instance_id = $instance_id;
        $chart_options = $this->getChartOptions($instance_id);
        $chart_decoded = json_decode($chart_options[0]['instance_options']);
        if(empty($chart_decoded->questions))
        {
           die();
        }
        $this->questions = $chart_decoded->questions;
        foreach ( $this->questions as  $question ) {
           if(!empty($question->segments))
           {
             $this->segments[$question->id] = $question->segments;
          }

        }

       $this->description = $chart_decoded->instance_description;
       $this->create_public_chart($chart_decoded);
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
            if(!empty($data_array))
            {
               $processed = $process_data->process_data($data_array);

            }
            else
            {
               $processed['keys'] = null;
               $processed['values'] = null;
               $processed['count'] = 0;
               $processed['mean'] = 0;
               $processed['median'] = 0;
               $processed['mode'] = 0;
               $processed['st_dev'] = 0;
               $processed['q1'] = 0;
               $processed['q2'] = 0;
               $processed['q3'] = 0;
               $processed['q4'] = 0;
            }
            $processed['chart_type'] = $question_info['type'];
            echo json_encode($processed);
        }
        die();
    }

    public function populate_segments($instance_id = null, $question_data = null)
    {
        if (!empty($_POST['chart_data'])) {
            parse_str($_POST['chart_data'], $chart_data);
            $question_data = unserialize($chart_data['question']);
            if(!empty($question_data['segments'])){
               $segment_questions = $this->get_questions($chart_data['instance_id'], $question_data['segments']);
            }
            $instance_id = $chart_data['instance_id'];

         }
         else {
            $segment_questions = $this->get_questions($instance_id, $question_data);

         }
         if(!empty($question_data['segments'])){
            ?>

            <?php
            foreach ($segment_questions as $key => $segment_question) {
                ?>
               <span class="chart_label"><?php echo $segment_question['value_key']; ?></span>
               <select class="segment" name="segment[<?php echo $segment_question['value_key_id']; ?>]">
                  <option value>select</option>
                   <?php
                   $responses = $this->get_question_responses($instance_id, $segment_question['value_key_id']);
                   foreach ($responses as $response) {
                    ?>
                    <option value="<?php echo $response; ?>"><?php echo $response; ?></option>
                    <?php
                 }
                ?>
             </select>
            <?php

            }
         }
            if (!empty($_POST['chart_data'])) {
               die();
            }

    }

    public function create_public_chart($chart_options)
    {
      $i = 0;
	   foreach($chart_options->questions as $option)
	   {
	      //echo $key." ".$value;
	      $questions_array[$i]['id'] = $option->id;
         if(!empty($option->segments))
         {
	           $questions_array[$i]['segments'] = $option->segments;
         }
	      $questions_array[$i]['type'] = $option->type;
	      $i++;
	   }
	   $name = $this->get_question_name($this->instance_id, $chart_options->questions[0]->id);
      ?>

   <div id="jsdv_container">
   <div class="fadeMe"></div>
   <div id="chart_description"><p class="info"><?php echo $this->description; ?></p></div>
   <div id="chart_and_info">
      <div id="chart_container">
         <canvas id="canvas"></canvas>
      </div>
      <div id="chart_info"></div>
   </div>
   <div id="chart_control">
   <form id="populate_chart">
   <input type="hidden" name="instance_id" value="<?php echo $this->instance_id ?>"/>
   <span class="chart_label">Chart survey data by selecting a question.</span>
   <div id="questions">

      <?php
      if(count($questions_array) <= 1)
      {
         $style = 'style="display:none;"';
      }
      else
      {
         $style ='';
      }
      ?>
      <select class="question" name="question" <?php echo $style; ?>>
      <?php
      $i = 0;
         foreach ($questions_array as $question) {
             if (!empty($question['id'])) {
            ?>
            <option value='<?php echo serialize($question); ?>'><?php echo $this->get_question_name($this->instance_id, $question['id']); ?></option>
            <?php
            $i++;
             }
         } ?>
      </select>
      <?php

       ?>
   </div>
   <span class="chart_label">Narrow the range of the data by selecting additional data segments.</span>
   <div id="segments">
      <?php
      if(!empty($questions_array[0]['segments']))
      {
         $this->populate_segments($this->instance_id, $questions_array[0]['segments']);
      }

      ?>
   </div>
   </form>
   </div>
   </div>
      <?php
    }
}
 ?>
