<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 *  @link              http://github.com/jbutland/js-data-visulization
 * @since      1.0.0
 *
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/public/partials
 */

   $instance_id = $atts['id'];
   //echo $instance_id;
   $data_get = new JS_Data_Visualization_Get_Data;
   $chart_options = $data_get->initilaizePublicChart($instance_id);
   //var_dump($chart_options);
   $i = 0;
   foreach($chart_options->questions as $option)
   {
      //echo $key." ".$value;
      $questions_array[$i]['id'] = $option->id;
      //$questions_array[$i]['segments'] = $option->segments;
      $questions_array[$i]['type'] = $option->type;
      $i++;
   }
   $name = $data_get->get_question_name($instance_id, $chart_options->questions[0]->id);
   ?>

<div id="jsdv_container">
<div class="fadeMe"></div>
<div id="chart_description"><p class="info"><?php echo $chart_options->instance_description; ?></p></div>
<div id="chart_and_info">
   <div id="chart_container">
      <canvas id="canvas"></canvas>
   </div>
   <div id="chart_info"></div>
</div>
<div id="chart_control">
<form id="populate_chart">
<input type="hidden" name="instance_id" value="<?php echo $instance_id ?>"/>
<div id="questions">
   <span class="chart_label">Chart survey data by selectiong a question.</span></br>
   <select class="question" name="question">
   <?php
   $i = 0;
      foreach ($questions_array as $question) {
          if (!empty($question['id'])) {
         ?>
         <option value='<?php echo serialize($question); ?>'><?php echo $data_get->get_question_name($instance_id, $question['id']); ?></option>
         <?php
         $i++;
          }
      } ?>
   </select>
</div>
<div id="segments">
</div>
</form>
</div>
</div>
