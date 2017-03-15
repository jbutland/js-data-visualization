<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 *  @link              http://github.com/jbutland/js-data-visulization
 * @since      1.0.0
 *
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/admin/partials
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'data-classes/class-js-data-visualization-import.php';
//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'data-classes/class-js-data-visualization-get.php';
        $import = new JS_Data_Visualization_Import_Data;
        $result = $import->handle_post();
        $instance_class = new JS_Data_Visualization_Get_Data;
        $instances = $instance_class->get_instances();
        //$questions = $instance_class->get_instance_questions(1);
        //$questions = $instance_class->populate_chart();


?>
  <div id="upload_files">
        <h2>Upload a File</h2>
        <?php echo $result;  ?>
        <p>Data must be in .csv format. Questions must be the first row.</p>
        <!-- Form to handle the upload - The enctype value here is very important -->
        <form  method="post" enctype="multipart/form-data" name="upload">
                <input type='file' id='upload_csv' name='upload_csv'></input>
                <?php submit_button('Upload') ?>
        </form>
  </div>
  <div id="chart_container" style="width:75%;">
      <canvas id="canvas"></canvas>
  </div>
  <form id="populate_chart">
  </form>
  <div id="manage_instances">
        <select name="instances" id="instances">
        <option value="">Please select and instance to edit</option>
        <?php foreach($instances as $instance)
        { ?>
          <option value="<?php echo $instance['instance_id']; ?>"><?php echo $instance['instance_name']; ?></option>
        <?php } ?>
        </select>
  </div>
  <form id="questions_display">

  </form>
<?php


?>
