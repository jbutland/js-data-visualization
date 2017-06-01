<?php
/**
 * Sets chart related data
 *
 *  @link              http://github.com/jbutland/js-data-visulization
 * @since      1.0.0
 *
 *
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/shared-files/data-classes
 * @author     Jon Butland jonathan.butland@gmail.com
 */
class JS_Data_Visualization_Set_Data extends JS_Data_Visualization_Get_Data
{

   private $instance_id =  null;
   private $questions = null;
   private $description = null;
    public function __construct()
    {

    }

    public function parse_chart_options()
    {
        if (!empty($_POST['settings_data'])) {
            parse_str($_POST['settings_data'], $settings);
            $questions = $settings['questions'];
            $chart_options['instance_id'] = $questions['instance_id'];


         $i = 0;
            foreach ($questions as $question) {
                if (!empty($question['id'])) {
                    $chart_options['questions'][$i]['id'] = $question['id'];
                    if (!empty($question['segments'])) {
                        $chart_options['questions'][$i]['segments'] =  implode($question['segments'], ",");
                    }

                    $chart_options['questions'][$i]['type'] = $question['type'];

               $i++;
                }
            }

         $chart_options['instance_description'] = $settings['instance_description'];
            $this->saveInstanceOptions($questions['instance_id'], json_encode($chart_options));
        }
        echo $chart_options['instance_id'];
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
  public function get_instance_questions()
  {
     if(!empty($_POST['instance_id']))
     {
      $instance_id = $_POST['instance_id'];
      $chart_options = $this->getChartOptions($instance_id);
      $chart_decoded = json_decode($chart_options[0]['instance_options']);
      if(!empty($chart_decoded))
      {
         $this->instance_id = $chart_decoded->instance_id;
         $this->questions = $chart_decoded->questions;
         $this->description = $chart_decoded->instance_description;
         ?>
         <input type="hidden" id="saved" name="saved" value="yes"/>
         <?php
      }
          $questions = $this->get_questions($instance_id); ?>
          <h3>Which Question do you want to Chart?</h3>
          <?php
          $i = 0;
          ?>
          <input type="hidden" id="instanceId" name="questions[instance_id]" value="<?php echo $instance_id; ?>"/>
          <?php
          foreach ($questions as $question) {
             if(!empty($this->questions[$i]) && $question['value_key_id'] == $this->questions[$i]->id)
             {
                $checked = "Checked";
                $type = $this->questions[$i]->type;
                if(!empty($this->questions[$i]->segments))
                {
                    $stored_segments = explode(',', $this->questions[$i]->segments);
                }
                else
                {
                   $stored_segments = null;
                }
                switch ($type) {
                  case 'bar':
                     $bar = "selected";
                     $line = '';
                     $pie = '';
                  break;
                  case 'line':
                     $bar = '';
                     $line = 'selected';
                     $pie = '';
                  break;
                  case 'pie':
                     $bar = '';
                     $line = '';
                     $pie = 'selected';
                  break;
               }
             }
             else
             {
                $checked = '';
                $bar = "selected";
                $line = '';
                $pie = '';
                $stored_segments = null;
             }
             ?>

             <input type="hidden" name="questions[<?php echo $i; ?>][name]" value="<?php echo $question['value_key']; ?>">
             <input type="checkbox" class="questions_to_chart" name="questions[<?php echo $i; ?>][id]" value="<?php echo $question['value_key_id']; ?>" <?php echo $checked; ?> > <?php  echo $question['value_key']; ?>
             <label>Choose chart type</label>
             <select class="chart_type" name="questions[<?php echo $i; ?>][type]">
                <option value="bar" <?php echo $bar; ?>>Bar</option>
                <option value="line" <?php echo $line; ?>>Line</option>
                <option value="pie" <?php echo $pie; ?>>Pie</option>
             </select><br>
             <div style="padding-left:5em;">
             <label>Question Segments</label><br>
                <?php
                    foreach ($questions as $segment) {
                       if ($question['value_key_id'] != $segment['value_key_id']) {
                         if(!empty($stored_segments) && array_search($segment['value_key_id'], $stored_segments) !== false)
                         {
                            $checked = "checked";
                         }
                         else {
                            $checked = '';
                         }
                            ?>
                       <input type="checkbox" class="questions_to_chart" name="questions[<?php echo $i; ?>][segments][]" value="<?php echo $segment['value_key_id']; ?>" <?php  echo $checked; ?>> <?php  echo $segment['value_key']; ?>
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
          <textarea name="instance_description" id="instance_description" rows="4" cols="50" ><?php echo $this->description; ?></textarea><br>
          <input type="submit" value="Save">
          <?php


   }
      die();
  }

}
?>
