<?php
/**
 * performs various statistical operations.
 *
 *  @link              http://github.com/jbutland/js-data-visulization
 * @since      1.0.0
 *
 *
 * @package    JS_Data_Visualization
 * @subpackage JS_Data_Visualization/shared-files/data-classes
 * @author     Jon Butland jonathan.butland@gmail.com
 */

class Process_Data_Functions {

  private $data_array;
  private $count;
  private $count_values;

  public function __construct()
  {

  }

  public function process_data($data_array)
  {
    $this->data_array = $data_array;
    natsort($this->data_array);
    $this->data_array = array_values($this->data_array);
    $this->count = count($data_array);
    $this->count_values = array_count_values($data_array);
    uksort($this->count_values, "strnatcmp");
    //$keys[] = "[";
    foreach ($this->count_values as $key => $value) {
      $keys[] = $key;
      $values[] = $value;
    }
    //$keys[] = "]";
    $keys_return = "[".implode(',', $keys)."]";
    $values_return = "[".implode(',', $values)."]";
    $processesed_data['keys'] = $keys;
    $processesed_data['values'] = $values;
    $processesed_data['count'] = $this->count;
    $processesed_data['mean'] = $this->mean();
    $processesed_data['median'] =$this->median();
    $processesed_data['mode'] = $this->mode();
    $processesed_data['st_dev'] = $this->standard_deviation();
    $processesed_data['q1'] = $this->quartiles(0.25);
    $processesed_data['q2'] = $this->quartiles(0.50);
    $processesed_data['q3'] = $this->quartiles(0.75);
    $processesed_data['q4'] = $this->quartiles(1);
    return $processesed_data;
  }

  /*
   *
   * Finds the statistical mean. It approximates for qualitative data sets.
   *
   */
  private function mean()
  {
    if(is_numeric($this->data_array[0]))
    {
      $sum = array_sum($this->data_array);
      $mean = $sum / $this->count;
    }
    else
    {
      $numerator = 0;
      $denominator = 0;
      $count = count($this->count_values);
      foreach($this->count_values as $value)
      {
        $numerator += $value * $count;
        $denominator += $value;
        $count--;
      }
      $mean = $numerator / $denominator;
    }
    return $mean;
  }

  /*
   *
   * Finds the median of the data set. If the set has an even number of values
   * and they are not the same value it returns two comma separated values.
   *
   */
  public function median()
  {
    $middleval = floor( ($this->count -1 )/ 2); // find the middle value, or the lowest middle value
    if($this->count % 2) { // odd number, middle is the median
        $median = $this->data_array[$middleval];
    } else
    { // even number, calculate avg of 2 medians
      $low = $this->data_array[$middleval];
      $high = $this->data_array[$middleval+1];
      if($low === $high)
      {
        $median = $low;
      }
      else
      {
        $median = $low.",".$high;
      }

    }
    return $median;
  }

  /*
   *
   * Finds the mode.
   *
   */

  private function mode()
  {
    $values = array_count_values($this->data_array);
    $mode = array_search(max($values), $values);
    return $mode;
  }

  /*
   *
   * Finds the Standard Deviation of the data set.
   *
   */

  //TODO double check the accuarcy of this function.

  private function standard_deviation()
  {
    if( count($this->data_array) < 2 ) {
      return;
    }

    $avg = $this->mean();

    $sum = 0;
    foreach($this->count_values as $value) {
      $sum += pow($value - $avg, 2);
    }

    return sqrt((1 / (count($this->count_values) - 1)) * $sum);
    }

 /*
  *
  * Calculates the last value in a quartile range.
  * Requires a percentage argument. Example 0.25, 0.50,  0.75
  *
  */
  public function quartiles($quartile)
  {
    $pos = (count($this->data_array) - 1) * $quartile;

    $base = floor($pos);
    $rest = $pos - $base;
    if( isset($this->data_array[$base+1]) && is_numeric($this->data_array[$base+1])) {
      return $this->data_array[$base] + $rest * ($this->data_array[$base+1] - $this->data_array[$base]);
    }
    else
    {
      return $this->data_array[$base];
    }

  }

}
?>
