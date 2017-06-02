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
  <div id="chart_home" style="width:75%;">

  </div>

  <div id="manage_instances">
        <select name="instances" id="instances">
        <option value="">Please select and instance to edit</option>
        <?php foreach($instances as $instance)
        { ?>
          <option value="<?php echo $instance['instance_id']; ?>"><?php echo $instance['instance_name']; ?></option>
        <?php } ?>
        </select>
  </div>
  <form id="questions_display" method="post">

  </form>
<?php


?>
