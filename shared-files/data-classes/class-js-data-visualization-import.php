<?php
/**
 * Imposts chart related data from csv
 *
 *  @link              http://github.com/jbutland/js-data-visulization
 * @since      1.0.0
 *
 *
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/shared-files/data-classes
 * @author     Jon Butland jonathan.butland@gmail.com
 */

class JS_Data_Visualization_Import_Data
{
  public function handle_post(){
    // First check if the file appears on the _FILES array
    if(isset($_FILES['upload_csv']))
    {
      $csv = $_FILES['upload_csv'];
      $instance_name = $csv['name'];
      // Use the wordpress function to upload
      // test_upload_pdf corresponds to the position in the $_FILES array
      // 0 means the content is not associated with any other posts
      $uploaded = media_handle_upload('upload_csv', 0);
      //Error checking using WP functions
      if(is_wp_error($uploaded))
      {
        return "Error uploading file: " . $uploaded->get_error_message();
      }
      else
      {
        $fullsize_path = get_attached_file( $uploaded );
        //echo $fullsize_path;
        $this->parse_csv( $fullsize_path, $instance_name );
        //var_dump($survey);
        //echo $uploaded;
        return "File upload successful!";
        unlink( $fullsize_path );
      }
    }
  }

  private function parse_csv($uploaded, $instance_name)
  {

    $row = 0;
  if (($handle = fopen($uploaded, "r")) !== FALSE) {
      $instance_id = $this->handle_instance($instance_name);
      if(!empty($instance_id))
      {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            if($row === 0)
            {

              foreach ($data as $datum) {
                $question[] = $datum;
              }
              $row++;
            }
            else
            {
              //echo "<p> $num fields in line $row: <br /></p>\n";
              $row_hash = md5(serialize($data));
              $row_id = $this->handle_rows($instance_id, $data, $row, $row_hash);
              if($row_id !== 0)
              {
                for ($c=0; $c < $num; $c++)
                {
                  $this->handle_values($instance_id, $row_id, $c+1, $question[$c], $data[$c] );
                }

              }

              $row++;
            }
        }
        fclose($handle);
    }
  }
  }

  private function handle_instance($instance_name)
  {
    global $wpdb;
    $instance = $wpdb->get_row( "SELECT * FROM wp_jsdv_instance WHERE instance_name = '$instance_name' ", ARRAY_A );
    if(empty($instance['instance_id']))
    {
      $wpdb->insert( 'wp_jsdv_instance', array( 'instance_name' => $instance_name ));
      $instance = $wpdb->get_row( "SELECT * FROM wp_jsdv_instance WHERE instance_name = '$instance_name' ", ARRAY_A );
    }
    return $instance['instance_id'];

  }

  private function handle_rows($instance_id, $data, $row, $row_hash)
  {
    global $wpdb;
    $instance_row = $wpdb->get_row( "SELECT * FROM wp_jsdv_instance_row WHERE instance_id = $instance_id AND row_counter = $row AND in_use = 1", ARRAY_A );
    if(empty($instance_row))
    {
      $wpdb->insert( 'wp_jsdv_instance_row', array(
      'instance_id' => $instance_id,
      'row_counter' => $row,
      'row_hash' => $row_hash,
      'in_use' => 1
      ));
      return $row_id = $wpdb->insert_id;
    }
    elseif ($instance_row['row_hash'] != $row_hash) {
      $wpdb->update(
        'wp_jsdv_instance_row',
        array(
          'in_use' => 0
        ),
        array( 'instance_id' => $instance_id,
        'row_counter' => $row,
        'in_use' => 1)
      );
      $wpdb->insert( 'wp_jsdv_instance_row', array(
      'instance_id' => $instance_id,
      'row_counter' => $row,
      'row_hash' => $row_hash,
      'in_use' => 1
      ));
      return $row_id = $wpdb->insert_id;
    }
    else
    {
      return 0;
    }



  }

  private function handle_values($instance_id, $row_id, $key_id, $key, $value)
  {
    global $wpdb;
    $key = trim($key);
    $key = preg_replace('/[\x00-\x1F\x7F]/u', '', $key);
    $value = trim($value);
    $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
    $wpdb->insert( 'wp_jsdv_instance_row_values', array(
    'instance_id' => $instance_id,
    'row_id' => $row_id,
    'value_key_id' => $key_id,
    'value_key' => $key,
    'value' => $value
    ));
    return $row_id = $wpdb->insert_id;
  }


}

 ?>
